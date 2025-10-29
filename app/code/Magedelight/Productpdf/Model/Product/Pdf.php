<?php
namespace Magedelight\Productpdf\Model\Product;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magedelight\Productpdf\Model\Product\MYPDF;

class Pdf extends \Magento\Framework\DataObject
{
    protected $printHelper = null;
    protected $scopeConfig;
    protected $storeManager;
    protected $_coreHelper = null;
    protected $_taxHelper = null;
    
    protected $optionHeadingFontSize = 15;
    protected $productTitleColor = '#0A263C';
    protected $linksColor = '#3399CC';
    protected $headingColor = '#E26703';
    protected $enableAdditionalImages = true;
    protected $enableRelatedProducts = true;
    protected $enableUpsellProducts = true;
    protected $enableQrCodes = true;
    protected $enableWishlistLink = true;
    protected $enableCompareLink = true;
    protected $enableReviewesLink = true;
    protected $mediaSectionHeight = 0;
    protected $headerLogo = null;
    protected $logoHeight = 0;
    protected $regularFontFile = null;
    protected $boldFontFile = null;
    protected $regularFontName = 'kozminproregular';
    protected $boldFontName = 'kozminproregular';
    protected $_logFile;
    protected $_pdfFile;
    private $mediaDirectory;
    protected $_imageFactory;
    protected $writer;
    protected $logger;
    
    protected $relatedProductCache = 'PRODUCTPDF_BLOCK_RELATED_{{key}}';
    protected $upsellProductsCache = 'PRODUCTPDF_BLOCK_UPSELL_{{key}}';
    protected $priceCache = 'PRODUCTPDF_BLOCK_PRICE_{{key}}';
    protected $additionalInfoCache = 'PRODUCTPDF_BLOCK_ADDITIONAL_{{key}}';
    protected $_cache = null;
    protected $k = 0;
    protected $_totalProducts = 0;
    protected $_optionsMap = [
            'drop_down'=>'Any One',
            'radio'=>'Any One',
            'checkbox'=>'Multiple Selections',
            'multiple'=>'Multiple Selections',
            'select'=>'Any One'
    ];
    protected $_requestedStore = 0;
    protected $_coverPage = false;
    protected $_uniqueId = null;
    protected $_logger;
    private $filesystem;
    protected $_storeBaseDir;
    protected $_mediaBaseDir;
    protected $tidyConfig = ['indent'=>true,'output-xhtml'=>true,'wrap'=>200];
    
    public function __construct(\Magento\Framework\Stdlib\StringUtils $string, \Magento\Framework\Escaper $_escaper, \Magento\Cms\Model\Template\FilterProvider $filterProvider, \Magento\Store\Model\StoreManager $storeManager, \Magento\Framework\App\Config\ScopeConfigInterface $_scopeConfig, \Magedelight\Productpdf\Helper\Data $printHelper, \Magento\Framework\Image\Factory $imageFactory, \Psr\Log\LoggerInterface $logger, Filesystem $fileSystem, array $data = [])
    {
        $this->string = $string;
        $this->_escaper=$_escaper;
        $this->_filterprovider = $filterProvider;
        $this->filesystem = $fileSystem;
        $this->scopeConfig = $_scopeConfig;
        $this->storeManager = $storeManager;
        $this->printHelper = $printHelper;
        $this->_imageFactory = $imageFactory;
        $this->mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->_logger = $logger;
        
        $this->enableAdditionalImages = (boolean)$this->scopeConfig->getValue('md_productpdf/general/additional_images');
        $this->enableRelatedProducts = (boolean)$this->scopeConfig->getValue('md_productpdf/general/enable_related');
        $this->enableUpsellProducts = (boolean)$this->scopeConfig->getValue('md_productpdf/general/enable_upsell');
        $this->enableQrCodes = (boolean)$this->scopeConfig->getValue('md_productpdf/general/enable_qrcodes');
        //$this->enableWishlistLink = (boolean)$this->scopeConfig->getValue('md_productpdf/general/enable_wishlist');
        //$this->enableCompareLink = (boolean)$this->scopeConfig->getValue('md_productpdf/general/enable_compare');
        $this->enableReviewesLink = (boolean)$this->scopeConfig->getValue('md_productpdf/general/enable_reviewes');
        $this->headerLogo = $this->printHelper->getHeaderLogo();
        $this->footerText = (string)$this->scopeConfig->getValue('md_productpdf/header_footer/pdf_footer_text');
        if ($this->scopeConfig->getValue('md_productpdf/formating_options/product_title') != '') {
            $this->productTitleColor = '#'.str_replace('#', '', $this->scopeConfig->getValue('md_productpdf/formating_options/product_title'));
        }
            
        if ($this->scopeConfig->getValue('md_productpdf/formating_options/link_color') != '') {
            $this->linksColor = '#'.str_replace('#', '', $this->scopeConfig->getValue('md_productpdf/formating_options/link_color'));
        }
            
        if ($this->scopeConfig->getValue('md_productpdf/formating_options/heading_color') != '') {
            $this->headingColor = '#'.str_replace('#', '', $this->scopeConfig->getValue('md_productpdf/formating_options/heading_color'));
        }
            $this->regularFontFile = $this->storeManager->getStore()->getBaseMediaDir() . $this->scopeConfig->getValue('md_productpdf/fonts/regular_font');
            
            
            $this->boldFontFile = $this->storeManager->getStore()->getBaseMediaDir() . $this->scopeConfig->getValue('md_productpdf/fonts/bold_font');
            
        
        parent::__construct($data);
    }
    
    public function getPdf($collection = [], $isBroucher = false, $type = "none")
    {
        
        try {
            $response = 'D';
            $this->_pdfFile = 'product-pdf.pdf';

            if (isset($collection['uniqid'])) {
               
                $this->setFileNames('categories', $collection['uniqid']);
                $response = 'F';
                $this->_uniqueId = $collection['uniqid'];
                unset($collection['uniqid']);
            }
            
            if (isset($collection['total_products'])) {
               
                $this->_totalProducts = $collection['total_products'];
            }
            
            if ($isBroucher && $type == 'categories') {
               
                $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, true, $this->storeManager->getStore(), $this->scopeConfig);
            } else {
                
                $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, false, $this->storeManager->getStore(), $this->scopeConfig);
            }
            $pdf->SetFont('kozminproregular', '', 9);
           
            /*if (is_file($this->regularFontFile)) {
                $this->regularFontName = \TCPDF_FONTS::addTTFfont($this->regularFontFile, 'TrueTypeUnicode', '', 32);
            }
            if (is_file($this->boldFontFile)) {

                $this->boldFontName = \TCPDF_FONTS::addTTFfont($this->boldFontFile, 'TrueTypeUnicode', '', 32);
            }*/
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->setPrintHeader(false);
            $pdf->setFooterData([0,64,0], [0,64,128]);
            $pdf->setHeaderFont([$this->boldFontName, '', 12]);
            $pdf->setFooterFont([$this->boldFontName, '', 12]);
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(0);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM + 3);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            //$pdf->setFontSubsetting(true);
            
            if (!$isBroucher) {
               
                $this->_requestedStore = (isset($collection['store_id'])) ? $collection['store_id'] : 0;
                $fileName = str_replace([' ','\n','\r','&','\\','/',':','"','*','?','|','<','>'], '_', $collection['name']).'.pdf';
                $this->_pdfFile = preg_replace('/[_]{2,}/', '_', $fileName);
                $this->drawProducts($collection, $pdf);
            } elseif ($isBroucher && $type == 'categories') {
               
                $this->insertCoverPage($pdf);
                
                foreach ($collection as $i => $data) {
                    
                    $index=0;
                    $this->_requestedStore = isset($data['store_id']) ? ($data['store_id']) : 0;
                    if (isset($data['products'])) {
                        foreach ($data['products'] as $j => $printProducts) {
                            if (is_array($printProducts) && count($printProducts) > 0) {
                                $this->k++;
                                $bookmark = ($index == 0) ? $data['name']: null;

                                $this->drawProducts($printProducts, $pdf, $bookmark, false);

                                $stringData = ($this->k * 100) / $this->_totalProducts;

                                $roundedFile = (int)$stringData;
                                if ($stringData != '100') {
                                    file_put_contents($this->_logFile, $stringData);
                                }
                                unset($data['products'][$j]);
                                $index++;
                               
                            }
                        }
                    }
                    unset($collection[$i]);
                }
                
                $this->insertTOCPage($pdf);
                
            }
            ob_end_clean();
            $pdf->Output($this->_pdfFile, $response);
            //set 100% progress
            if ($response == 'F') {
               
                file_put_contents($this->_logFile, '100');
            }
            unset($pdf);
        } catch (\Exception $e) {
            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test1.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $logger->info('Error Log'.print_r($e->getMessage(), true));
        }
    }
    
    protected function setFileNames($catId, $uniqid)
    {
        $folder = $this->mediaDirectory->getAbsolutePath('md'.DIRECTORY_SEPARATOR.'product-print');
        
        $io = new \Magento\Framework\Filesystem\Io\File();
        $this->_pdfFile = $folder . DIRECTORY_SEPARATOR . $catId . '-' . $uniqid . '.pdf';
        $this->_logFile = $folder . DIRECTORY_SEPARATOR . $catId . '-progress-' . $uniqid . '.txt';
        try {
            
            if (!is_dir($folder)) {
                $io->mkdir($folder, 0777, true);
            }
        } catch (\Exception $e) {
           
            return $e->getMessage();
        }
    }
    
    public function insertTOCPage(&$pdf)
    {
        
        $tocPageNo = ($this->_coverPage) ? 2: 1;
        $pdf->setPrintFooter(false);
        $pdf->addTOCPage();
        $pdf->SetFont('kozminproregular', 'B', 16);
        $pdf->MultiCell(0, 0, __('Table Of Content'), 0, 'C', 0, 1, '', '', true, 0);
        $pdf->Ln();
        $pdf->SetFont('kozminproregular', '', 10);
        $pdf->addTOC($tocPageNo, $this->boldFontName, '.', __('Table Of Content'), 'B', [10,38,0]);
        
        // end of TOC page
        $pdf->endTOCPage();
    }
    
    public function insertCoverPage(&$pdf)
    {
        
        $bgImagePath = $this->storeManager->getStore()->getBaseMediaDir().'/theme/productpdf/cover_page/'.$this->scopeConfig->getValue('md_productpdf/general/cover_image');
        if ($this->scopeConfig->getValue('md_productpdf/general/cover_image') && is_file($bgImagePath)) {
          
            $pdf->setFooterData([255,255,255], [255,255,255]);
            $pdf->AddPage('', '', '', true);

            $imageWidth = $pdf->getPageWidth();
            $imageHeight = $pdf->getPageHeight();
            $pdf->Image($bgImagePath, '', '', $imageWidth, $imageHeight, '', $this->storeManager->getStore()->getBaseUrl(), '', true, 300, '', false, false, 0, false, false, false);
            $this->_coverPage = true;
           
            //$pdf->setPrintFooter(true);
        }
    }
    
    public function drawProducts($collection, &$pdf, $bookmarkCategory = null, $bookmarkAsFirstLevel = true)
    {
      
        $str = strpos($collection['base_image'], '.');
        if ($str != true) {
          
               $collection['base_image'] = '';
        }
        $pdf->setFooterData([0,64,0], [0,64,128]);
        $keyContainer = [$collection['id'],$collection['store_id']];
        $pdf->setPrintFooter(true);
        $pdf->AddPage('', '', '', true);
       
        if (!$bookmarkCategory) {
           
            if ($bookmarkAsFirstLevel) {
              
                $pdf->Bookmark($collection['name'], 0, 0, $pdf->PageNo(), 'B', [179,92,61]);
            } else {
               
                $pdf->Bookmark($collection['name'], 1, 0, $pdf->PageNo(), 'B', [153,153,153]);
            }
        } else {
           
            $pdf->Bookmark($bookmarkCategory, 0, 0, $pdf->PageNo(), 'B', [179,92,61]);
            $pdf->Bookmark($collection['name'], 1, 0, $pdf->PageNo(), 'B', [153,153,153]);
        }
       
        $pdf->setFont('kozminproregular', '', 10);
        $pdf->setTextShadow(['enabled'=>false, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>[196,196,196], 'opacity'=>1, 'blend_mode'=>'Normal']);
        $statusLabel = [0=>__('OUT OF STOCK'),1=>__('IN STOCK')];
            $statusColor  = [0=>'red',1=>'#11b400'];
            $headerLogo = is_file($this->headerLogo) ? '<img src="'.$this->headerLogo.'" style="border:none;"/><br />': '';
       
            $ratingsHtml = ($this->enableReviewesLink) ? $this->_drawRatingsBlock($collection['review'], false, $pdf) : '';
            $priceHtml = $this->_drawPriceBlock($collection['price'], $pdf, $keyContainer);
            $mediaGallaryHtml = '';
        if (isset($collection['media_gallery'])) {
           
            $mediaGallaryHtml = ($this->enableAdditionalImages) ? $this->_drawMediaGalleryBox($collection['media_gallery'], $pdf) : '';
        }
      
            $quickViewHtml = (strlen($collection['short_description']) > 0) ? $this->_drawQuickViewBlock($collection['short_description'], $pdf) : '';
            $tierPriceHtml = '';
        if (isset($collection['tier_prices'])) {
          
            $tierPriceHtml = $this->_drawTierPriceBlock($collection['tier_prices'], $pdf);
        }
       
            //$wishlistCompareHtml = $this->_drawWishlistCompareProductLink($collection, $pdf);
            $descriptionHtml = (isset($collection['description']) && strlen($collection['description']) > 0) ? $this->_drawDescriptionBlock($collection['description'], $pdf) : '';
            
            $additionalHtml = '';
          
        if (isset($collection['additional_data'])) {
           
            $additionalHtml = $this->_drawAdditionalDataBox($collection['additional_data'], $pdf, $keyContainer);
        }
       
            $bundleProductOptionsHtml = '';
            $configurableOptionsHtml = '';
            $customOptionsHtml = '';
            $groupedProductOptionsHtml = '';
        if (isset($collection['custom_options'])) {
           
            $customOptionsHtml = $this->_getCustomOptions($collection['custom_options'], $pdf);
        }
       
        if (isset($collection['bundle_products'])) {
           
            $bundleProductOptionsHtml = $this->_getBundleProductOptions($collection['bundle_products'], $pdf);
        }
        
        if (isset($collection['configurable_products'])) {
           
            $configurableOptionsHtml = $this->_getConfigurableOptions($collection['configurable_products'], $pdf);
        }
      
        if (isset($collection['grouped_products'])) {
          
            $groupedProductOptionsHtml = $this->_getGroupedOptions($collection['grouped_products'], $pdf);
        }
       
        $html = <<<EOD
<style>
.product-main-title{font-family:{$this->boldFontName};color:{$this->productTitleColor};text-decoration:none;font-size:20px;}
.wishlist-link,.compare-link{color:{$this->linksColor};font-size:12px;font-weight:normal;text-decoration:none;}
.attribute-label{font-family:{$this->boldFontName};color:{$this->headingColor};font-size:14px;font-weight:bold;text-decoration:none;}
.attribute-values{color:#000000;font-size:12px;font-weight:normal;text-decoration:none;text-align: justify;}
.additional-label{font-family:{$this->boldFontName};color:#2f2f2f;font-size:12px;font-weight:bold;}
.additional-value{color:#2f2f2f;font-size:12px;font-weight:normal;}
.additional-data{width:100%;border:1px solid #bebcb7;}
.additional-data th{border-bottom:1px solid #d9dde3;border-right: 1px solid #d9dde3;border-top:1px solid #d9dde3;}
.additional-data td{border-bottom:1px solid #d9dde3;border-top:1px solid #d9dde3;}
.additional-data tr.even{background-color:#ffffff;}.additional-data tr.odd{background-color:#ffffff;}
.related-products-table td{width:25%;}
.product-name{font-family: {$this->boldFontName};color:#0a263c;font-size:12px;font-weight:bold;text-decoration:none;}
.set-wishlist-link,.set-compare-link{color:{$this->linksColor};font-size:10px;font-weight:normal;text-decoration:none;}
.related-products-table td.even{background-color:#ffffff;}
.related-products-table td.odd{background-color:#ffffff;}
.lg{color:{$this->linksColor};font-size:12px;font-weight:normal;text-decoration:none;}
.sm{color:{$this->linksColor};font-size:10px;font-weight:normal;text-decoration:none;}
.option-label{font-size:12px;color:#0a263c;}
.option-selection{font-size:12px;color:#2f2f2f;font-weight:normal;}
.option-selection-price{font-size:12px;color:#c76200;font-weight:normal;}
</style>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <td align="left" height="60">{$headerLogo}</td><td align="right"></td>
        </tr>
        <tr>
            <td align="left">
                <table cellpadding="0" cellspacing="0">
                <tr><td align="left">
                <img src="{$collection['base_image']}" style="border:border:1px solid #c1c1c1;margin:0;padding:0;"/>
                </td></tr>
                {$mediaGallaryHtml}
                </table>
            </td>
            <td>
                <table border="0" cellpadding="0" width="100%" cellspacing="2">
                <tr>
                <td height="25" valign="top" style="border-bottom:2px solid #cccccc;"><b><a class="product-main-title" href="{$collection['url']}">{$collection['name']}</a></b></td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                <td>{$priceHtml}</td>
                </tr>
                {$ratingsHtml}
                <tr>
                <td><span style="color:{$statusColor[$collection['stock_status']]};font-size:12px;">{$statusLabel[$collection['stock_status']]}</span></td>
                </tr>
                {$tierPriceHtml}{$quickViewHtml}
               
                </table>
            </td>
        </tr>
            <tr>
           <td colspan="2">
                {$customOptionsHtml}
                {$bundleProductOptionsHtml}
                {$configurableOptionsHtml}
                {$groupedProductOptionsHtml}
                {$descriptionHtml}<br /><br />
                {$additionalHtml}
           </td>
        </tr>
    </table>
EOD;
       
        if ($this->enableQrCodes) {
           
            $pdf->write2DBarcode($collection['url'], 'QRCODE,L', $pdf->getPageWidth()- 20, '7', '30', '30');
        }
       
        $pdf->writeHTML($html, true, false, true, false, '');


        $relatedProducts = [];
        $upsellProducts = [];
        if (isset($collection['related_products'])) {
          
            $relatedProducts = array_chunk($collection['related_products'], 4);
        }
        if (isset($collection['upsell_products'])) {
          
            $upsellProducts = array_chunk($collection['upsell_products'], 4);
        }
        if ($this->enableRelatedProducts) {
          
            foreach ($relatedProducts as $i => $products) {
                $keyContainer[2] = $i;
                $drawTitle = ($i == 0) ? true: false;
                $relatedHtml = $this->_drawListProducts($products, 'related_products', $pdf, $drawTitle, $keyContainer);
                $html = <<<EOD
<style>
.wishlist-link,.compare-link{color:{$this->linksColor};font-size:12px;font-weight:normal;text-decoration:underline;}
.attribute-label{font-family: {$this->boldFontName};color:{$this->headingColor};font-size:14px;font-weight:bold;text-decoration:none;}
.attribute-values{color:#000000;font-size:12px;font-weight:normal;text-decoration:none;text-align: justify;}
.additional-label{font-family: {$this->boldFontName};color:#2f2f2f;font-size:12px;font-weight:bold;}
.additional-value{color:#2f2f2f;font-size:12px;font-weight:normal;}
.additional-data{width:100%;border:1px solid #bebcb7;}
.additional-data th{border-bottom:1px solid #d9dde3;border-right: 1px solid #d9dde3;}
.additional-data td{border-bottom:1px solid #d9dde3;}
.additional-data tr.even{background-color:#f8f7f5;}.additional-data tr.odd{background-color:#eeeded;}
.related-products-table td{width:25%;}
.product-name{font-family: {$this->boldFontName};color:#0a263c;font-size:12px;text-decoration:none;}
.set-wishlist-link,.set-compare-link{color:{$this->linksColor};font-size:10px;font-weight:normal;text-decoration:none;}
.related-products-table td.even{background-color:#ffffff;border-right:1px solid #c1c1c1;}
.related-products-table td.odd{background-color:#ffffff;border-right:1px solid #c1c1c1;}
.related-products-table td.last{border-right:none !important;}
.related-products-table tr.bottom-seperator{display:block;border-bottom:1px solid #ededed;}
.lg{color:{$this->linksColor};font-size:12px;font-weight:normal;text-decoration:none;}
.sm{color:{$this->linksColor};font-size:10px;font-weight:normal;text-decoration:none;}
</style>
{$relatedHtml}
EOD;
                $pdf->writeHTML($html, true, false, true, false, '');
            }
        }
       
        if ($this->enableUpsellProducts) {
           
            foreach ($upsellProducts as $i => $products) {
                $keyContainer[2] = $i;
                $drawTitle = ($i == 0) ? true: false;
                $upsellHtml = $this->_drawListProducts($products, 'upsell_products', $pdf, $drawTitle, $keyContainer);

                $html = <<<EOD
<style>
.wishlist-link,.compare-link{color:{$this->linksColor};font-size:12px;font-weight:normal;text-decoration:none;}
.attribute-label{font-family: {$this->boldFontName};color:{$this->headingColor};font-size:14px;font-weight:bold;text-decoration:none;}
.attribute-values{color:#000000;font-size:12px;font-weight:normal;text-decoration:none;text-align: justify;}
.additional-label{font-family: {$this->boldFontName};color:#2f2f2f;font-size:12px;font-weight:bold;}
.additional-value{color:#2f2f2f;font-size:12px;font-weight:normal;}
.additional-data{width:100%;border:1px solid #bebcb7;}
.additional-data th{border-bottom:1px solid #d9dde3;border-right: 1px solid #d9dde3;border-top:1px solid #d9dde3;}
.additional-data td{border-bottom:1px solid #d9dde3;border-top:1px solid #d9dde3;}
.additional-data tr.even{background-color:#f8f7f5;}.additional-data tr.odd{background-color:#eeeded;}
.related-products-table td{width:25%;}
.product-name{font-family: {$this->boldFontName};color:#0a263c;font-size:12px;text-decoration:none;}
.set-wishlist-link,.set-compare-link{color:{$this->linksColor};font-size:10px;font-weight:normal;text-decoration:none;}
.related-products-table td.even{background-color:#ffffff;border-right:1px solid #c1c1c1;}
.related-products-table td.odd{background-color:#ffffff;border-right:1px solid #c1c1c1;}
.related-products-table tr.bottom-seperator{display:block;border-bottom:1px solid #ededed;}
.lg{color:{$this->linksColor};font-size:12px;font-weight:normal;text-decoration:none;}
.sm{color:{$this->linksColor};font-size:10px;font-weight:normal;text-decoration:none;}
</style>
{$upsellHtml}
EOD;
                $pdf->writeHTML($html, true, false, true, false, '');
            }
        }
       
//write progress
    }
    
    protected function _drawPriceBlock($data, &$pdf, $uniqueKey = null)
    {
       
        $cachedHtml = '';
        $html = '';
            $html .= '<table cellpadding="0" cellspacing="0" width="100%" border="0">';
            $exclText = __('Excl. Tax: ');
            $inclText = __('Incl. Tax: ');
            $fromText = __('From:');
            $toText = __('To:');
            $regularText = __('Regular Price: ');
            $specialText = __('Special Price: ');
            $startingText = __('Starting at: ');
           
        if (is_array($data) && isset($data['from']) && isset($data['to'])) {
           
            $from = $data['from'];
             $to = $data['to'];
             $html .= '<tr>';
             $html .= '<td style="width:45px;">';
            $html .= '<span style="color:#575757;font-weight:normal;font-size:14px;float:left;">'.$fromText.'</span>';
            $html .= '</td>';
            $html .= '<td>';
            if (is_array($from) && isset($from['excl_tax']) && isset($from['incl_tax'])) {
               
                $html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" border="0">';
                $html .= '<tr>';
                $html .= '<td align="left">';
                $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$exclText.'</span>';
                $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$from['excl_tax'].'</span>';
                $html .= '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td align="left">';
                $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$inclText.'</span>';
                $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$from['incl_tax'].'</span>';
                $html .= '</td>';
                $html .= '</tr>';
                $html .= '</table>';
              
            } else {
               
                if (!empty($from)) {
                    $html .= '<span style="color:#575757;font-weight:normal;font-size:14px;">'.$from.'</span>';
                }
            }
           
            $html .= '</td></tr>';
            $html .= '<tr>';
            $html .= '<td style="width:25px;">';
            $html .= '<span style="color:#575757;font-weight:normal;font-size:14px;">'.$toText.'</span>';
            $html .= '</td>';
            $html .= '<td>';
          
            if (is_array($to) && isset($to['excl_tax']) && isset($to['incl_tax'])) {
                $html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" border="0">';
                $html .= '<tr>';
                $html .= '<td>';
                $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$exclText.'</span>';

                $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$to['excl_tax'].'</span>';
                $html .= '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td>';
                $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$inclText.'</span>';
                $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$to['incl_tax'].'</span>';
                $html .= '</td>';
                $html .= '</tr>';
                $html .= '</table>';
               
            } else {
               
                if (!empty($to)) {
                  
                    $html .= '&nbsp;<span style="color:#575757;font-weight:normal;font-size:14px;">'.$to.'</span>';
                }
            }
            $html .= '</td>';
            $html .= '</tr>';
          
        } elseif (is_array($data) && isset($data['starting'])) {
           
            $html .= '<tr>';
            $html .= '<td>';
            $starting = $data['starting'];
            $html .= '<span style="color:#575757;font-weight:normal;font-size:14px;">'.$startingText.'</span>';
            if (is_array($starting) && isset($starting['excl_tax']) && isset($starting['incl_tax'])) {
               
                $html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" border="0">';
                $html .= '<tr>';
                $html .= '<td>';
                $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$exclText.'</span>';
                $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$starting['excl_tax'].'</span>';
                $html .= '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td>';
                $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$inclText.'</span>';
                $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$starting['incl_tax'].'</span>';
                $html .= '</td>';
                $html .= '</tr>';
                $html .= '</table>';
            } else {
              
                if (!empty($starting)) {
                   
                    $html .= '<span style="color:#575757;font-weight:normal;font-size:14px;">'.$starting.'</span>';
                }
            }
            $html .= '</td>';
            $html .= '</tr>';
           
        } else {
           
            if (is_array($data) && isset($data['regular']) && isset($data['special'])) {
              
                $special = $data['special'];
                $html .= '<tr>';
                 $html .= '<td>';
                $html .= '<span style="text-decoration:line-through;font-size:14px;color:#a0a0a0;">'.$data['regular'].'</span>&nbsp;';
                if (is_array($special) && isset($special['excl_tax']) && isset($special['incl_tax'])) {
                  
                    $html .= '</td>';
                    $html .= '</tr>';
                    $html .= '<tr>';
                    $html .= '<td>';
                    $html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" border="0">';
                    $html .= '<tr>';
                    $html .= '<td>';
                    $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$exclText.'</span>';
                    $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$special['excl_tax'].'</span>';
                    $html .= '</td>';
                    $html .= '</tr>';
                    $html .= '<tr>';
                    $html .= '<td>';
                    $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$inclText.'</span>';
                    $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$special['incl_tax'].'</span>';
                    $html .= '</td>';
                    $html .= '</tr>';
                    $html .= '</table>';

                } else {
                  
                    if (!empty($special)) {
                      
                        $html .= '<span style="color:#575757;font-weight:normal;font-size:16px;">'.$special.'</span>';
                    }
                }
                $html .= '</td>';
                $html .= '</tr>';
            } else {
                   
                if (is_array($data) && isset($data['excl_tax']) && isset($data['incl_tax'])) {
                   
                    $html .= '<tr>';
                    $html .= '<td>';
                    $html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" border="0">';
                    $html .= '<tr>';
                    $html .= '<td>';
                    $html .= '<span style="color:#575757;font-weight:bold;font-size:12px;">'.$exclText.'</span>';
                    $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$data['excl_tax'].'</span>';
                    $html .= '</td>';
                    $html .= '</tr>';
                    $html .= '<tr>';
                    $html .= '<td>';
                    $html .= '<span style="color:#575757;font-weight:bold;font-size:12px;">'.$inclText.'</span>';
                    $html .= '<span style="color:#575757;font-weight:normal;font-size:12px;">'.$data['incl_tax'].'</span>';
                    $html .= '</td>';
                    $html .= '</tr>';
                    $html .= '</table>';
                } else {
                  
                    $html .= '<tr>';
                    $html .= '<td>';
                    if (!empty($data)) {
                        $html .= '<span style="color:#575757;font-weight:bold;font-size:12px;">'.$data.'</span>';
                    }
                }
                $html .= '</td>';
                $html .= '</tr>';
            }
        }
            $html .= '</table>';
           
        return $html;
    }
    
    protected function _drawMediaGalleryBox($mediaImages, &$pdf)
    {
      
        $mediaHtml = '';
        $counter = 0;
        if (count($mediaImages) > 0) {
          
            $mediaHtml .= '<tr>';
            $mediaHtml .= '<td align="left">&nbsp;';
            foreach ($mediaImages as $idx => $mediaG) {
                if ($counter > 3) {
                    $mediaHtml .= '</td></tr><tr><td>&nbsp;';
                    $counter = 0;
                }
                $mediaHtml .= '<img src="'.$mediaG.'" height="75" width="75" style="border:1px solid #c1c1c1;margin-right:5px;" />&nbsp;&nbsp;';
                    $counter++;
            }
            $mediaHtml .= '</td>';
            $mediaHtml .= '</tr>';
        }
       
        return $mediaHtml;
    }
    
    protected function _drawQuickViewBlock($description, &$pdf)
    {
        
        $html = '';
        if (strlen($description) > 0) {
           
            $x = new \DOMDocument;
            $x->loadHTML($description);
            $description = $x->saveXML();

            $html .= '<tr><td style="border-bottom:2px solid #cccccc"><span class="attribute-label">'.__('Quick Overview').'</span></td></tr>';
            $html .= '<tr><td><span class="attribute-values">'.$description.'</span></td></tr>';
        }
        
        return $html;
    }
    
    protected function _drawTierPriceBlock($tirePrices, &$pdf)
    {
       
        $html = '';
        if (count($tirePrices) > 0) {
          
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<span style="background-color:#FBF4DE;font-size:12px;font-weight:normal;text-decoration:none;">';
            $html .= '<table cellspacing="0" cellpadding="5"><tr><td>'.implode('<br />', $tirePrices).'</td></tr></table>';
            $html .= '</span>';
            $html .= '</td>';
            $html .= '</tr>';
        }
       
        return $html;
    }
    
    protected function _drawWishlistCompareProductLink($data, &$pdf)
    {
        $html = '';
        $wishlistText = __('Add to Wishlist');
            $compareText = __('Add to Compare');
        $resultArray = [];
        if ($this->enableWishlistLink) {
            $resultArray[] = '<a class="wishlist-link" href="'.$data['wishlist_url'].'">'.$wishlistText.'</a>';
        }
        if ($this->enableCompareLink) {
            $resultArray[] = '<a class="compare-link"  href="'.$data['compare_url'].'">'.$compareText.'</a>';
        }
        
        
        $html .= implode(' | ', $resultArray);
        return $html;
    }
    protected function strip_tags_content($text)
    {
        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    }

    protected function _drawDescriptionBlock($description, &$pdf)
    {
       // echo "<pre>";print_r($description);
        $filterManager =  $this->_filterprovider->getPageFilter()->filter($description);
    
        $description = preg_replace('</br>', 'br/', $filterManager);
        $html = '';
        if (strlen($description) > 0) {

            $x = new \DOMDocument;
            $x->loadHTML($description);
            $description = $x->saveXML();
            //echo htmlspecialchars_decode($description); exit;
            $html .= '<table cellpadding="0" cellspacing="0" width="100%"><tr><td style="border-bottom:2px solid #cccccc;"><span class="attribute-label">'.__('Details').'</span></td></tr>';
            $html .= '<tr><td><span class="attribute-values">'.htmlspecialchars_decode($description).'</span></td></tr></table>';
        }
     
        return $html;
    }
    
    protected function _drawAdditionalDataBox($additionalInfo, &$pdf, &$uniqueKey)
    {
       
        $cacheKey = preg_replace('/{{key}}/', implode('_', $uniqueKey), $this->additionalInfoCache);
        
        $html = '';
        if (count($additionalInfo) > 0) {
            
                $html .= '<table width="100%">';
                $html .= '<tr><td style="border-bottom:2px solid #cccccc;"><span class="attribute-label">'.__('Additional Information').'</span></td></tr>';
                $html .= '<tr><td>&nbsp;</td></tr>';
                $html .= '<tr><td><table class="additional-data" cellpadding="5">';
                $i=1;
                $class="";
            foreach ($additionalInfo as $data) {
                if (($i % 2) == 0) {
                   
                    $class ='even';
                } else {
                     
                    $class = 'odd';
                }
                $html .= '<tr class="'.$class.'"><th width="25%"><span class="additional-label">'.$data['label'].'</span></th><td width="75%"><span class="additional-value">'.$data['value'].'</span></td></tr>';
                $i++;
            }
                $html .= '</table></td></tr>';
                $html .= '</table>';
        }
      
        return $html;
    }
    
    protected function _drawListProducts($products, $type = 'related_products', &$pdf, $title = false, &$uniqueKey)
    {
       
        $y = $pdf->GetY();
        if ($y >= $pdf->getPageHeight() - 180) {
           
            $pdf->AddPage();
        }
        $labels = [
            'related_products'=>__('Related Products'),
            'upsell_products'=>__('You may also be interested in the following product(s).'),
        ];
       
        $cacheKey = [
            'related_products'=> preg_replace('/{{key}}/', implode('_', $uniqueKey), $this->relatedProductCache),
            'upsell_products'=> preg_replace('/{{key}}/', implode('_', $uniqueKey), $this->upsellProductsCache)
        ];
       
        $html = '';
        $wishlistText = __('Add to Wishlist');
            $compareText = __('Add to Compare');
        if (count($products) > 0 && (($type == 'related_products' && $this->enableRelatedProducts) || ($type == 'upsell_products' && $this->enableUpsellProducts))) {
           
                $html .= '';
                $html .= '<table class="related-products-table" width="100%" cellspacing="0" cellpadding="0">';
            if ($title) {
              
                $html .= '<tr><td width="100%" style="border-bottom:2px solid #cccccc;"><span class="attribute-label">'.$labels[$type].'</span></td></tr>';
                $html .= '<tr><td>&nbsp;</td></tr>';
            }
           
                $html .= '<tr>';
                $i=1;
                $class="";
            foreach ($products as $pId => $product) {
               
                $keyContainer = [$pId,$this->_requestedStore];
                if ($i == 5) {
                    $i=1;
                    $html .= '</tr><tr><td colspan="4"></td></tr><tr>';
                }
                if (($i % 2) == 0) {
                    $class='even';
                } else {
                    $class='odd';
                }
                $class .= (($i == 4) || ($pId == count($products) - 1)) ? 'last': '';
                $html .= '<td class="'.$class.'">';
                $html .= '<table cellpadding="5" cellspacing="0">';
                $html .= '<tr>';
                $html .= '<td align="center">';
                $html .= '<a href="'.$product['url'].'"><img src="'.$product['image_path'].'" style="border:1px solid #c1c1c1;"/></a>';
                $html .= '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td align="center">';
                $html .= '<b><a class="product-name" href="'.$product['url'].'">'.$product['name'].'</a></b>';
                $html .= '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td valign="top" align="center">';
                $html .= $this->_drawPriceBlock($product['price'], $pdf, $keyContainer);
                $html .= '</td>';
                $html .= '</tr>';
                if (isset($product['review']['reviews_count']) && isset($product['review']['rating_summary']) && $this->enableReviewesLink) {
                   
                    $html .= $this->_drawRatingsBlock($product['review'], true, $pdf);
                }
             
                /*$html .= '<tr>';
                $html .= '<td align="center">';
                if ($this->enableWishlistLink) {
                    $html .= '<a class="set-wishlist-link" href="'.$product['wishlist_url'].'">'.$wishlistText.'</a><br />';
                }
                if ($this->enableCompareLink) {
                    $html .= '<a class="set-compare-link"  href="'.$product['compare_url'].'">'.$compareText.'</a>';
                }
                $html .= '</td>';
                $html .= '</tr>';*/
                $html .= '</table>';
                $html .= '</td>';
                $i++;
                    
            }
                $html .= '</tr>';
                $html .= '</table>';
        }
        unset($uniqueKey[2]);
       
        return $html;
    }
    
    protected function _drawRatingsBlock($ratings, $inBox = false, &$pdf)
    {
      
        $html = '';
        $additionalClass = '';
        if (isset($ratings['reviews_count']) && isset($ratings['rating_summary']) && $ratings['reviews_count'] > 0) {
           
            $html .= '<tr>';
            if (!$inBox) {
              
                $html .= '<td>';
                $src = $this->storeManager->getStore()->getBaseMediaDir().DIRECTORY_SEPARATOR.'md_product_print'.DIRECTORY_SEPARATOR.'products-ratings.png';
                $destinationfile = $this->storeManager->getStore()->getBaseMediaDir().DIRECTORY_SEPARATOR.'md'.DIRECTORY_SEPARATOR.'product-print'.DIRECTORY_SEPARATOR.'review-tmp'.DIRECTORY_SEPARATOR.$ratings['entity_pk_value'].'-large.png';
                $whitebg = $this->storeManager->getStore()->getBaseMediaDir().DIRECTORY_SEPARATOR.'white-bg-large.jpg';
                $additionalClass = 'lg';
            } else {
             
                $html .= '<td align="center">';
                $src = $this->storeManager->getStore()->getBaseMediaDir().DIRECTORY_SEPARATOR.'md_product_print'.DIRECTORY_SEPARATOR.'products-ratings-small.png';
                $destinationfile = $this->storeManager->getStore()->getBaseMediaDir().DIRECTORY_SEPARATOR.'md'.DIRECTORY_SEPARATOR.'product-print'.DIRECTORY_SEPARATOR.'review-tmp'.DIRECTORY_SEPARATOR.$ratings['entity_pk_value'].'-small.png';
                $whitebg = $this->storeManager->getStore()->getBaseMediaDir().DIRECTORY_SEPARATOR.'white-bg-small.jpg';
                $additionalClass = 'sm';
            }
           
            if ($ratings['rating_summary'] < 100) {
              
                if (!is_dir($this->storeManager->getStore()->getBaseMediaDir().DIRECTORY_SEPARATOR.'md'.DIRECTORY_SEPARATOR.'product-print'.DIRECTORY_SEPARATOR.'review-tmp'.DIRECTORY_SEPARATOR)) {
                  
                    mkdir($this->storeManager->getStore()->getBaseMediaDir().DIRECTORY_SEPARATOR.'md'.DIRECTORY_SEPARATOR.'product-print'.DIRECTORY_SEPARATOR.'review-tmp'.DIRECTORY_SEPARATOR, 0777, true);
                }
               
                $varien = $this->_imageFactory->create($src);
                $width=$varien->getOriginalWidth();
                $calculatedWidth = ($ratings['rating_summary'] * $width) / 100;
                $varien->keepAspectRatio(true);
                $varien->crop(0, 0, $width - $calculatedWidth, 0);
                $varien->save($destinationfile);
                $src = $destinationfile;
            }
          
            $reviewText = __(sprintf('%d Review(s)', $ratings['reviews_count']));
            $addReviewText = __('Add Your Review');
            $html .= '<img src="'.$src.'" /><br /><a class="ratings-links '.$additionalClass.'" href="'.$ratings['review_url'].'">'.$reviewText.'</a>&nbsp;|&nbsp;<a class="ratings-links '.$additionalClass.'" href="'.$ratings['review_url'].'">'.$addReviewText.'</a>';
            $html .= '</td>';
            $html .= '</tr>';
           
        } else {
            $html .= '<tr>';
            $html .= '<td align="center">';
            
            $addReviewText = __('Add Your Review');
            $html .= '<a class="ratings-links" href="'.$ratings['review_url'].'">'.$addReviewText.'</a>';
            $html .= '</td>';
            $html .= '</tr>';

        }
       
        return $html;
    }
    
    protected function _getBundleProductOptions($bunbdleOptions, &$pdf)
    {
      
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr><td style="border-bottom:2px solid #cccccc;"><span class="attribute-label">'.__('Product Options').'</span></td></tr>';
        $html .= '<tr><td>&nbsp;</td></tr>';
        foreach ($bunbdleOptions as $option) {
           
            $additionalText = isset($this->_optionsMap[$option['type']]) ? '<span style="font-size:8px;"> ('.__($this->_optionsMap[$option['type']]).')</span>': '';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<b><span class="option-label">&nbsp;&nbsp;&nbsp;'.$option['default_title'].$additionalText.'</span></b><br />';
            foreach ($option['selections'] as $selection) {
              
                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="option-selection">'.$selection['title'].'</span>';
                if (strlen($selection['price']) > 0) {
                    
                    $html .= '&nbsp;<span class="option-selection-price">+&nbsp;'.$selection['price'].'</span>';
                }
                $html .= '<br />';
            }
            
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</table><br /><br />';
       
        return $html;
    }
    
    protected function _getConfigurableOptions($configurableOptions, &$pdf)
    {
        
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr><td style="border-bottom:2px solid #cccccc;"><span class="attribute-label">'.__('Configurable Product Options').'</span></td></tr>';
        $html .= '<tr><td>&nbsp;</td></tr>';
        foreach ($configurableOptions as $option) {
           
            $additionalText = '<span style="font-size:8px;"> ('.__($this->_optionsMap['drop_down']).')</span>';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<b><span class="option-label">&nbsp;&nbsp;&nbsp;'.$option['label'].$additionalText.'</span></b><br />';
            foreach ($option['values'] as $selection) {
              
                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="option-selection">'.$selection['label'].'</span>';
                if (isset($selection['pricing_value']) && $selection['pricing_value'] > 0) {
                  
                    $html .= '&nbsp;<span class="option-selection-price">+&nbsp;'.$this->_coreHelper->formatPrice($selection['pricing_value'], false).'</span>';
                }
                $html .= '<br />';
            }
           
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</table><br /><br />';
       
        return $html;
    }
    
    protected function _getCustomOptions($customOptions, &$pdf)
    {
       
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr><td style="border-bottom:2px solid #cccccc;"><span class="attribute-label">'.__('Product Options').'</span></td></tr>';
        $html .= '<tr><td>&nbsp;</td></tr>';
        foreach ($customOptions as $option) {
           
            $additionalText = isset($this->_optionsMap[$option['type']]) ? '<span style="font-size:8px;"> ('.__($this->_optionsMap[$option['type']]).')</span>': '';
            $title = (isset($option['store_title']) && $option['store_title'] != '') ? $option['store_title']: $option['default_title'];
                    $price = (isset($option['store_price']) && $option['store_price'] != '') ? $option['store_price']: $option['price'];
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<b><span class="option-label">&nbsp;&nbsp;&nbsp;'.$title.$additionalText.'</span></b>';
            if (strlen($price) > 0) {
               
                $html .= '&nbsp;<span class="option-selection-price">+&nbsp;'.$price.'</span>';
            }
          
            $html .= '<br />';
            if (isset($option['option_values'])) {
             
                foreach ($option['option_values'] as $selection) {
                
                    $title = (isset($selection['store_title']) && $selection['store_title'] != '') ? $selection['store_title']: $selection['default_title'];
                    $price = (isset($selection['store_price']) && $selection['store_price'] != '') ? $selection['store_price']: $selection['default_price'];
                    $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="option-selection">'.$title.'</span>';
                    if (strlen($price) > 0) {
                     
                        $html .= '&nbsp;<span class="option-selection-price">+&nbsp;'.$price.'</span>';
                    }
                    $html .= '<br />';
                }
            }
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</table><br /><br />';
       
        return $html;
    }
    
    protected function _getGroupedOptions($groupedProducts, &$pdf)
    {
       
        $html = '';
        $statusLabel = [0=>__('OUT OF STOCK'),1=>__('IN STOCK')];
                $statusColor  = [0=>'red',1=>'#11b400'];
        $html .= '<table width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr><td style="border-bottom:2px solid #cccccc;"><span class="attribute-label">'.__('Grouped Product Options').'</span></td></tr>';
        $html .= '<tr><td>&nbsp;</td></tr>';
        $html .= '<tr>';
        $html .= '<td>';
        $html .= '<table class="additional-data" cellpadding="5" cellspacing="0">';
        $i=1;
            $class="";
           
        foreach ($groupedProducts as $option) {
          
            if (($i % 2) == 0) {
             
                    $class='even';
            } else {
               
                $class='odd';
            }
            $html .= '<tr class="'.$class.'" align="center">';
            $html .= '<td valign="middle">';
            $html .= '<img src="'.$option['image'].'" />';
            $html .= '</td>';
            $html .= '<td valign="middle">';
            $html .= '<a class="product-name" href="'.$option['url'].'">'.$option['name'].'</a>';
            $html .= '</td>';
            $html .= '<td valign="middle" align="center">';
            $html .= '<span style="color:'.$statusColor[$option['stock_status']].';font-size:12px;">'.$statusLabel[$option['stock_status']].'</span>';
            $html .= '</td>';
            $html .= '<td valign="middle" align="center">';
            if (isset($option['price'])) {
             
                $html .= $this->_drawPriceBlock($option['price'], $pdf);
            }
            $html .= '</td>';
            $html .= '</tr>';
            $i++;
        }
      
        $html .= '</table>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table><br /><br />';
      
        return $html;
    }
}
