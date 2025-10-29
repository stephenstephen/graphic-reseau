<?php

namespace Gone\Subligraphy\Controller\Certificate;

use Exception;
use Gone\Subligraphy\Helper\PdfGenerator;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory as ResultRedirectFactory;
use Gone\Subligraphy\Helper\SubligraphyConfig;
use Gone\Subligraphy\Model\CertificateRepository;
use Gone\Subligraphy\Api\Data\CertificateInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Gone\Base\Helper\CoreConfigData;
use Gone\Base\Helper\FileUploader;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Exception\LocalizedException;

class CreatePost implements HttpPostActionInterface
{

    public const MAX_IMG_SIZE = 5; //Mo

    private CertificateRepository $_certificatesRepository;
    private CertificateInterfaceFactory $_certificatesInterfaceFactory;
    private SearchCriteriaBuilder $_searchCriteriaBuilder;
    private Session $_customerSession;
    protected FileUploader $_fileUploader;
    protected PdfGenerator $_pdfGenerator;
    protected SubligraphyConfig $_subligraphyConfig;
    protected CoreConfigData $_coreConfigData;
    protected RequestInterface $_request;
    protected ResultRedirectFactory $_resultFactory;
    protected DateTime $_date;
    protected MessageManagerInterface $_messageManager;
    protected Validator $_formKeyValidator;

    public function __construct(
        CertificateRepository $certificatesRepository,
        CertificateInterfaceFactory $certificatesInterfaceFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Session $customerSession,
        SubligraphyConfig $subligraphyConfig,
        CoreConfigData $coreConfigData,
        ResultRedirectFactory $resultFactory,
        MessageManagerInterface $messageManager,
        FileUploader $fileUploader,
        DateTime $date,
        PdfGenerator $pdfGenerator,
        Validator $formKeyValidator,
        DirectoryList $filesystem,
        RequestInterface $request
    ) {
        $this->_customerSession = $customerSession;
        $this->_request=$request;
        $this->_searchCriteriaBuilder=$searchCriteriaBuilder;
        $this->_certificatesInterfaceFactory = $certificatesInterfaceFactory;
        $this->_certificatesRepository = $certificatesRepository;
        $this->_resultFactory = $resultFactory;
        $this->_messageManager = $messageManager;
        $this->_subligraphyConfig = $subligraphyConfig;
        $this->_coreConfigData=$coreConfigData;
        $this->_date = $date;
        $this->_fileUploader=$fileUploader;
        $this->_formKeyValidator=$formKeyValidator;
        $this->_filesystem = $filesystem;
        $this->_pdfGenerator = $pdfGenerator;
    }

    /**
     * Post user question
     *
     * @return Redirect
     */
    public function execute()
    {
        $validFormKey = $this->_formKeyValidator->validate($this->_request);
        if (!$validFormKey || !$this->_request->isPost()) {
            $this->_messageManager->addErrorMessage(
                __('Request is not valid. Please Try again.')
            );
            return $this->_resultFactory->create()->setPath('*/*/');
        }

        try {
            $data = $this->validatedParams();
            $files = $this->_request->getFiles();
            $certificateMediaDir = $this->_subligraphyConfig->getCertificateDirUrl();
            $certificateNumber = $this->_subligraphyConfig->getNewCertificateNumber();
            //upload image
            $upload = $this->_fileUploader->upload(
                $files,
                'file',
                $certificateMediaDir['uploadTo'],
                ['jpg', 'jpeg'],
                SubligraphyConfig::MAX_IMG_SIZE
            );

            if ($upload) {

                $data['image'] = $certificateMediaDir['displayFrom'].'/'.$upload;
                $data['date'] = $this->_date->date('d-M-Y');
                //generate PDF certificate
                $pdfCertificate = $this->_pdfGenerator->genCertificatePdf($certificateNumber, $certificateMediaDir['uploadTo'], $data);
                $pdfCartouche = $this->_pdfGenerator->genCartouchePdf($certificateNumber, $certificateMediaDir['uploadTo'], $data);
                $zip = $this->_pdfGenerator->zipCertificate($certificateNumber, $certificateMediaDir['uploadTo']);

                $newCertificate = $this->_certificatesInterfaceFactory->create()
                    ->setCustomerId($this->_customerSession->getCustomerId())
                    ->setTitle($this->_request->getParam('title'))
                    ->setAuthor($this->_request->getParam('author'))
                    ->setManufacturer($this->_request->getParam('manufacturer'))
                    ->setWidth($this->_request->getParam('width'))
                    ->setHeight($this->_request->getParam('height'))
                    ->setCount($this->_request->getParam('nbr'))
                    ->setNumber($certificateNumber)
                    ->setImage($certificateMediaDir['displayFrom'].'/'.$upload)
                    ->setFilename($zip)
                    ->setPublisher($this->_request->getParam('publisher'));
                if ($pdfCertificate && $pdfCartouche && $this->_certificatesRepository->save($newCertificate)) {
                    $this->_messageManager->addSuccessMessage(
                        __('Certificate has been generated and saved.')
                    );
                }
            }

        } catch (LocalizedException $e) {
            $this->_messageManager->addErrorMessage($e->getMessage());
        } catch (Exception $e) {
            $this->_messageManager->addErrorMessage(
                __('An error occurred while processing your form. Please try again later.')
            );
        }
        return $this->_resultFactory->create()->setPath('*/*/');
    }


    /**
     * @return array
     * @throws LocalizedException
     */
    private function validatedParams()
    {
        if (empty($this->_request->getParam('title'))) {
            throw new LocalizedException(__('Enter the title and try again.'));
        }
        if (empty($this->_request->getParam('author'))) {
            throw new LocalizedException(__('Enter the author and try again.'));
        }

        if (empty($this->_request->getParam('manufacturer'))) {
            throw new LocalizedException(__('Enter the manufacturer and try again.'));
        }

        if (empty($this->_request->getParam('width'))) {
            throw new LocalizedException(__('Enter the width and try again.'));
        }
        if (empty($this->_request->getParam('height'))) {
            throw new LocalizedException(__('Enter the height and try again.'));
        }
        if (empty($this->_request->getParam('nbr'))) {
            throw new LocalizedException(__('Enter number of copies and try again.'));
        }
        if ($this->_request->getParam('nbr') <= 0 || $this->_request->getParam('nbr') > $this->_coreConfigData->getValueFromCoreConfig(SubligraphyConfig::MAX_CERTIFICATE_COPY_COUNT)) {
            throw new LocalizedException(__('Enter number of copies between 1 and '.$this->_coreConfigData->getValueFromCoreConfig(SubligraphyConfig::MAX_CERTIFICATE_COPY_COUNT)));
        }

        if (empty($this->_request->getFiles())) {
            throw new LocalizedException(__('Select image of product.'));
        }

        if (trim($this->_request->getParam('hideit')) !== '') {
            throw new Exception();
        }

        return $this->_request->getParams();
    }
}
