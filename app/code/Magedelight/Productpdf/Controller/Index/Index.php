<?php
namespace Magedelight\Productpdf\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $storeManager;
    protected $_coreRegistry;
    protected $sessionStorage;
    protected $_logger;
    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Session\Storage $sessionStorage,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->storeManager = $storeManager;
        $this->_coreRegistry = $coreRegistry;
        $this->sessionStorage = $sessionStorage;
        $this->_logger = $logger;
        parent::__construct($context);
    }
    
    public function execInBackground($url)
    {
        $this->_logger->log(\Psr\Log\LogLevel::DEBUG, $url);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
        try {
            curl_exec($ch);
                
            curl_close($ch);
        } catch (\Exception $e) {
            //Mage::getSingleton("core/session")->addError($e->getMessage());
            //Mage::register('pdf_generate_url', str_replace("\\","",$url));
            $this->_coreRegistry->register('pdf_generate_url', str_replace("\\", "", $url));
        }
    }
    
    public function getQueryString($posts)
    {
        $qstr = '';
                $posts['store'] = !array_key_exists('store', $posts) ? $this->storeManager->getStore()->getId() : $posts['store'];
                $posts['store_code'] = !array_key_exists('store_code', $posts) ? $this->storeManager->getStore()->getCode() : $posts['store_code'];
        $count = count($posts);
        $index = 1;
        
        
        foreach ($posts as $key => $value) {
            if ($key == 'category' && strstr($value, ",") !== false) {
                $cIndex = 1;
                $array = array_unique(explode(",", $value));
                foreach ($array as $categoryId) {
                    if ($categoryId != '') {
                        $qstr .= $key."[]=".$categoryId;
                        if ($cIndex < count($array) - 1) {
                            $qstr .= '\&';
                            $cIndex++;
                        }
                    }
                }
            } else {
                $qstr .= $key."=".$value;
            }
            if ($index < $count) {
                $qstr .= '\&';
                $index++;
            }
        }
        return $qstr;
    }
    
    public function execute()
    {

        $posts = $this->getRequest()->getParams();

        $id = uniqid();
        $posts['id'] = $id;
        $this->getRequest()->setParams($posts);
        $this->sessionStorage->setData('uniqid', $id);
        $Qstr = $this->getQueryString($posts);
        $url = $this->storeManager->getStore()->getUrl('md_productpdf/index/generateCategories', ['_secure' => false]).'?'.$Qstr.'\&id='. $id;
        $this->execInBackground(str_replace("\\", "", $url));
        $this->_view->loadLayout();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('PDF Catalog Print'));
        $this->_view->renderLayout();
    }
}
