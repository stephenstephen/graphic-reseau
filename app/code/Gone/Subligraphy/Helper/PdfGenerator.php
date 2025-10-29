<?php

namespace Gone\Subligraphy\Helper;

use Gone\Subligraphy\Helper\SubligraphyConfig;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Archive\Zip;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use ZipArchive;

class PdfGenerator extends AbstractHelper
{

    /*Send the document to a given destination: string, local file or browser.
    * Dest can be :
    *  I : send the file inline to the browser (default). The plug-in is used if available. The name given by name is used when one selects the 'Save as' option on the link generating the PDF.
    *  D : send to the browser and force a file download with the name given by name.
    *  F : save to a local server file with the name given by name.
    *  S : return the document as a string (name is ignored).
    *  FI: equivalent to F + I option
    *  FD: equivalent to F + D option
    *  E : return the document as base64 mime multi-part email attachment (RFC 2045)*/
    public const SAVING_MODE = 'F';

    public const CHECK_PATH = 'subligraphie/certificate/check';

    protected DirectoryList $_mediaDirectory;
    protected MessageManagerInterface $_messageManager;
    protected StoreManagerInterface $_storeManager;
    protected Zip $_zip;
    protected Filesystem $_filesystem;
    protected array $filesToZip;

    public function __construct(
        Context $context,
        MessageManagerInterface $messageManager,
        DirectoryList $mediaDirectory,
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        Zip $zip
    ) {
        parent::__construct($context);
        $this->_messageManager = $messageManager;
        $this->_mediaDirectory = $mediaDirectory;
        $this->_storeManager = $storeManager;
        $this->_filesystem = $filesystem;
        $this->_zip = $zip;
        $this->filesToZip = [];
    }


    /**
     * @param string $certificateNumber
     * @param array $data
     * @param int $numero
     * @return string
     * @throws NoSuchEntityException
     */
    private function getCertificateTemplate(string $certificateNumber, array $data, int $numero)
    {
        $template = '
        <page backtop="20mm" backbottom="20mm" backleft="10mm" backright="10mm" style="text-align:center;">
            <page_header style="text-align:center;">
                  <img src="'.SubligraphyConfig::SUBLIGRAPHY_LOGO_B64.'" style="width: 300px;"/>
            </page_header>

             <h1 style="text-align:center;margin-top:50px;">'. __('Authenticity certificate') .'</h1>
             <p style="display:flex;text-align:center;">
                <img src="'.$data['b64_file'].'" style="height: 300px;"/>
                 <table style="text-align:left;margin-top:20px;font-size: 20px;margin-left:auto;margin-right:auto;">
                    <tr>
                        <td>'. __('Title') .' :&nbsp;</td>
                        <td>'.strtoupper($data['title']).'</td>
                    </tr>
                    <tr>
                        <td>'. __('Date') .':&nbsp;</td>
                        <td>'.$data['date'].'</td>
                    </tr>
                    <tr>
                        <td>'. __('Format') .':&nbsp;</td>
                        <td>'.$data['width'].' x '.$data['height'].' cm</td>
                    </tr>
                    <tr>
                        <td>'. __('Author') .' :&nbsp;</td>
                        <td>'.strtoupper($data['author']).'</td>
                    </tr>
                 </table>
            </p>
            <p>'.__('Printing and certificate are verified under logotype Subligraphie® and are referenced under id').'</p>
            <h2>'.$certificateNumber.'</h2>
            <p>'.__('This printing is number %1 on limited production of %2.', $numero, $data['nbr']).'<br/>'
            .__('It has been made by %1 on ChromaLuxe® aluminium plate.', $data['manufacturer']).'<br/>'
            .__('Made in Paris, on %1.', $data['date']).'
                </p>
                <qrcode value="'.$this->_storeManager->getStore()->getBaseUrl().self::CHECK_PATH.'/?n='.base64_encode($certificateNumber).'" ec="H" style="width: 50mm; background-color: white; color: black;"></qrcode>
            <page_footer style="text-align:center;">
            '.__('Control authenticity on this link').' :
               <a style="text-decoration: none;" href="'.$this->_storeManager->getStore()->getBaseUrl().self::CHECK_PATH.'/?n='.base64_encode($certificateNumber).'">'.$this->_storeManager->getStore()->getBaseUrl().'subligraphie/certificate/check</a>
            </page_footer>
         </page>';

        return $template;
    }

    /**
     * @param string $certificateNumber
     * @param array $data
     * @return string
     * @throws NoSuchEntityException
     */
    private function getCartoucheTemplate(string $certificateNumber, array $data)
    {
        $template = '
        <page backtop="0mm" backbottom="20mm" backleft="0" backright="0">
            <table style="background-color:#000000;text-align:center;margin-left:auto;margin-right:auto;">
                <tr>
                    <td style="width:810px;height: 400px;line-height:200px;text-align:center;">
                        <img src="'.SubligraphyConfig::SUBLIGRAPHY_LOGO_CARTOUCHE_B64.'" style="width: 700px;"/>
                    </td>
                    <td style="width:300px;height: 400px;line-height:200px;text-align:center;">
                        <qrcode value="'.$this->_storeManager->getStore()->getBaseUrl().self::CHECK_PATH.'/?n='.base64_encode($certificateNumber).'" ec="H" style="width: 60mm; background-color: white; color: black;"></qrcode>
                    </td>
                </tr>
            </table>
            <h1 style="text-align:center;margin-top:50px;font-size: 40px">'.$data['title'].'</h1>
            <p style="margin-left:50px;margin-top:80px;font-size: 24px">'.__('Authenticity certificate').' : '.$certificateNumber.'</p>
            <p style="margin-left:50px;margin-top:10px;font-size: 24px">Copyright © '.$data['date'].' - '.$data['author'].'</p>
         </page>';

        return $template;
    }

    /**
     * @param string $certificateNumber
     * @param string $saveDirectory
     * @param array $data
     * @return bool
     * @throws FileSystemException
     */
    public function genCertificatePdf(string $certificateNumber, string $saveDirectory, array $data)
    {
        try {

            $pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', [10,10,10,10]);

            $page = 1;
            while ($page <= $data['nbr']) {
                $template = $this->getCertificateTemplate($certificateNumber, $data, $page);
                $pdf->writeHTML($template);
                $page++;
            }
            ob_clean();
            $path = $this->_filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
            $fullPath = $path->getAbsolutePath($saveDirectory);

            $filename = $certificateNumber.'_Certificate.pdf';

            $this->filesToZip['certificate']['path'] =$fullPath.'/'.$filename;
            $this->filesToZip['certificate']['filename'] =$filename;

            $pdf->output($fullPath.DIRECTORY_SEPARATOR.$filename, self::SAVING_MODE);
            return true;

        } catch (Html2PdfException $e) {
            $pdf->clean();
            $formatter = new ExceptionFormatter($e);
            $this->_messageManager->addErrorMessage($formatter->getHtmlMessage());
        }
    }

    /**
     * @param string $certificateNumber
     * @param string $saveDirectory
     * @param array $data
     * @return bool
     * @throws FileSystemException
     * @throws NoSuchEntityException
     */
    public function genCartouchePdf(string $certificateNumber, string $saveDirectory, array $data)
    {
        try {

            $pdf = new Html2Pdf('L', 'A4', 'fr', true, 'UTF-8', [0,0,0,0]);
            $template = $this->getCartoucheTemplate($certificateNumber, $data);
            $pdf->writeHTML($template);

            ob_clean();

            $path = $this->_filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
            $fullPath = $path->getAbsolutePath($saveDirectory);
            $filename = $certificateNumber.'_Cartouche.pdf';

            $this->filesToZip['cartouche']['path'] =$fullPath.'/'.$filename;
            $this->filesToZip['cartouche']['filename'] =$filename;

            $pdf->output($fullPath.DIRECTORY_SEPARATOR.$filename, self::SAVING_MODE);

            return true;

        } catch (Html2PdfException $e) {
            $pdf->clean();
            $formatter = new ExceptionFormatter($e);
            $this->_messageManager->addErrorMessage($formatter->getHtmlMessage());
        }
    }

    /**
     * @param string $certificateNumber
     * @param string $saveDirectory
     * @return string|false
     * @throws FileSystemException
     */
    public function zipCertificate(string $certificateNumber, string $saveDirectory)
    {
        $zip = new ZipArchive();
        $path = $this->_filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $fullPath = $path->getAbsolutePath($saveDirectory);

        if ($zip->open($fullPath.DIRECTORY_SEPARATOR.$certificateNumber.'.zip', ZipArchive::CREATE) === true) {
            $zip->addFile($this->filesToZip['certificate']['path'], $this->filesToZip['certificate']['filename']);
            $zip->addFile($this->filesToZip['cartouche']['path'], $this->filesToZip['cartouche']['filename']);
            $zip->close();

            //delete pdf origin files
            $path->delete($this->filesToZip['certificate']['path']);
            $path->delete($this->filesToZip['cartouche']['path']);

            return $saveDirectory.DIRECTORY_SEPARATOR.$certificateNumber.'.zip';
        }
        return false;
    }
}
