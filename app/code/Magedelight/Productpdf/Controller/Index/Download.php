<?php
namespace Magedelight\Productpdf\Controller\Index;

class Download extends \Magento\Framework\App\Action\Action
{
    protected $sessionStorage;
    protected $_coreRegistry;
    protected $storeManager;
    protected $fileFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        // \Magento\Framework\Session\Storage $sessionStorage,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Session\Storage $sessionStorage
    ) {
        $this->storeManager = $storeManager;
        $this->sessionStorage = $sessionStorage;
        $this->fileFactory = $fileFactory;
        parent::__construct($context);
    }
    
    public function execute()
    {
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Product PDF'));
        $file = $this->getRequest()->getParam('file');
        if (isset($file) && !empty($file)) {
            $fileName = str_replace([' ', '\n', '\r', '&', '\\', '/', ':', '"', '*', '?', '|', '<', '>'], '_', $file) . '.pdf';
            $rFileName = preg_replace('/[_]{2,}/', '_', $fileName);
            $filePath = $this->storeManager->getStore()->getBaseMediaDir().'/md/product-print/'. $file;
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="'.$rFileName.'"');
            readfile($filePath);
            unlink($filePath);
        }
    }
}
