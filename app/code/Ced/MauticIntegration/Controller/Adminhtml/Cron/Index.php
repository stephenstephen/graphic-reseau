<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_MauticIntegration
 * @author 		CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license     http://cedcommerce.com/license-agreement.txt
 */
namespace Ced\MauticIntegration\Controller\Adminhtml\Cron;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;
use Ced\MauticIntegration\Helper\ConnectionManager;


class Index extends Action implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /** @var \Ced\MauticIntegration\Helper\ConnectionManager  */
    public $connectionManager;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        ConnectionManager $connectionManager,
        PageFactory $resultPageFactory
        
    ){
        $this->connectionManager=$connectionManager;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|
     * \Magento\Framework\App\ResponseInterface|
     * \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if ($this->connectionManager->getModuleStatus()) {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->setActiveMenu('Ced_MauticIntegration::mautic');
            $resultPage->getConfig()->getTitle()->prepend(__('Cron Status'));
            return $resultPage;
        } else {
            $this->messageManager->addErrorMessage(
                'Mautic integration is disabled. Please enable it from system configuration.'
            );
            $this->_redirect('admin');
        }
    }

     /**
     * IsALLowed
     * @return boolean
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ced_MauticIntegration::cron_status');
    }
}
