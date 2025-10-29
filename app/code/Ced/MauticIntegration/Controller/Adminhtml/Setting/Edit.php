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
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license     http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\MauticIntegration\Controller\Adminhtml\Setting;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * ResultPageFactory
     * @var PageFactory
     */
    public $resultPageFactory;

    /** @var \Ced\MauticIntegration\Helper\ConnectionManager  */
    public $connectionManager;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager
    ) {
        $this->connectionManager=$connectionManager;
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Execute
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        if ($this->connectionManager->getModuleStatus()) {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->setActiveMenu('Ced_MauticIntegration::mautic');
            $resultPage->getConfig()->getTitle()->prepend(__('Manage Mautic Settings'));
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
        return $this->_authorization->isAllowed('Ced_MauticIntegration::mautic');
    }
}
