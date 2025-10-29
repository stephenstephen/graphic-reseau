<?php

namespace Magedelight\Productpdf\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;

class Generate extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $storeManager;
    protected $_rtlLanguages = ['ar_DZ','ar_EG','ar_KW','ar_MA','ar_SA','he_IL','fa_IR'];
    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }
    
    public function execute()
    {
        $storeLanguage = $this->storeManager->getStore()->getConfig('general/locale/code');
        
        $params = $this->getRequest()->getParams();
       
        $productModel = $this->_objectManager->create(\Magedelight\Productpdf\Model\Products::class);
        if (in_array($storeLanguage, $this->_rtlLanguages)) {
          
            $pdfGenerateModel = $this->_objectManager->create(\Magedelight\Productpdf\Model\Product\Pdf\Rtl::class);
        } else {
           
            $pdfGenerateModel = $this->_objectManager->create(\Magedelight\Productpdf\Model\Product\Pdf::class);
        }
        $appEmulation = $this->_objectManager->create(\Magento\Store\Model\App\Emulation::class);
        $store = '';
        if (isset($params['store']) && !empty($params['store'])) {
           
            $store = $params['store'];
        } else {
            $store = 0;
        }
        if ($store == 0) {
                $store = $this->storeManager->getDefaultStoreView()->getStoreId();
        }
        $data = $productModel->prepareProductdata([$params['product']], $store);
        try {
           
                    ini_set('memory_limit', '2048M');
                   //ini_set('memory_limit', -1);
        } catch (\Exception $e) {
           
        }
        $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($store);
        
        $pdf = $pdfGenerateModel->getPdf($data[$params['product']]);
        $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
    }
}
