<?php
namespace Magedelight\Productpdf\Controller\Index;

class GenerateCategories extends \Magento\Framework\App\Action\Action
{
    protected $storeManager;
    protected $productCollection;
    protected $printModel;
    protected $categoryModel;
    protected $_logger;
    protected $_rtlLanguages = ['ar_DZ','ar_EG','ar_KW','ar_MA','ar_SA','he_IL','fa_IR'];
    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Store\Model\StoreManager $storeManager, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollection, \Magento\Catalog\Model\Category $categoryModel, \Magedelight\Productpdf\Model\Products $printModel, \Psr\Log\LoggerInterface $logger)
    {
        $this->storeManager = $storeManager;
        $this->productCollection = $productCollection;
        $this->categoryModel = $categoryModel;
        $this->printModel = $printModel;
        $this->_logger = $logger;
        parent::__construct($context);
    }
    
    public function execute()
    {
       
        try {
            ini_set('memory_limit', '2048M');
            //ini_set('memory_limit', -1);
        } catch (\Exception $e) {
            return $e->message();
        }
        $storeLanguage = $this->storeManager->getStore()->getConfig('general/locale/code');
        $categoryIds = [];
        $categoryIds = $this->getRequest()->getParam('category');
        $uniqid = $this->getRequest()->getParam('id');
        $storeId = $this->getRequest()->getParam('store', 0);
        $categoryData = [];
        $categoryData['uniqid'] = $uniqid;
        $total_products = 0;
       
        foreach ($categoryIds as $categoryId) {
          
            if (is_numeric($categoryId)) {
              
                $category = $this->categoryModel->setStoreId($storeId)->load($categoryId);
                $productCollection = $this->productCollection->create()->addCategoryFilter($category);
                $productIds = $productCollection->getAllIds();
                $total_products += count($productIds);
                $categoryData[$categoryId]['name'] = $category->getName();
                $categoryData[$categoryId]['store_id'] = $storeId;
                $categoryData[$categoryId]['products'] = $this->printModel->prepareProductdata($productIds, $storeId, $categoryId);
            }
        }
      
        $categoryData['total_products'] = $total_products;
        if (in_array($storeLanguage, $this->_rtlLanguages)) {
            
            $pdfGenerateModel = $this->_objectManager->create(\Magedelight\Productpdf\Model\Product\Pdf\Rtl::class);
        } else {
           
            $pdfGenerateModel = $this->_objectManager->create(\Magedelight\Productpdf\Model\Product\Pdf::class);
        }
        $appEmulation = $this->_objectManager->create(\Magento\Store\Model\App\Emulation::class);
        $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId);
        $pdf = $pdfGenerateModel->getPdf($categoryData, true, 'categories');
        $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
    }
}
