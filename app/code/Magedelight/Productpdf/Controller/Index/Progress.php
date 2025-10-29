<?php
namespace Magedelight\Productpdf\Controller\Index;

class Progress extends \Magento\Framework\App\Action\Action
{
    protected $storeManager;
    protected $sessionStorage;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\Session\Storage $sessionStorage
    ) {
        $this->storeManager = $storeManager;
        $this->sessionStorage = $sessionStorage;
        parent::__construct($context);
    }
   
    public function execute()
    {
        $category_id = $this->getRequest()->getParam('category');
        $uniqid = $this->getRequest()->getParam('id');
        //$myFile = $this->storeManager->getStore()->getBaseMediaDir().'/md/product-print/'. $category_id . '-progress-' . $uniqid . '.txt';
        $data = 1;
        if (is_array($category_id)) {
            $myFile = $this->storeManager->getStore()->getBaseMediaDir().'/md/product-print/'. implode("_", $category_id) . '-progress-' . $uniqid . '.txt';
        } else {
            $myFile = $this->storeManager->getStore()->getBaseMediaDir().'/md/product-print/'. $category_id . '-progress-' . $uniqid . '.txt';
        }
        if (is_file($myFile)) {
            $fh = fopen($myFile, 'r');
            $data = fread($fh, filesize($myFile));
            fclose($fh);
        }
        if ($data == '100') {
            unlink($myFile);
            $url = $this->storeManager->getStore()->getUrl('md_productpdf/index/download', ['_secure' => true]). '?file=' .$category_id . '-' . $uniqid . '.pdf';
            $this->getResponse()->setHeader('Content-type', 'text/html');
            $this->getResponse()->setBody($url);
            $this->getResponse()->sendResponse();
          
        } else {
            $this->getResponse()->setHeader('Content-type', 'text/html');
            $this->getResponse()->setBody($data);
            $this->getResponse()->sendResponse();
        }
        $this->_eventManager->dispatch('productpdf_download_send', []);
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Product PDF'));
    }
}
