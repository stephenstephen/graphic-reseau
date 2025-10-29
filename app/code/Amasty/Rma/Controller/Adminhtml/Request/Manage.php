<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Controller\Adminhtml\Request;

use Magento\Framework\Controller\ResultFactory;

class Manage extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Amasty_Rma::manage';

    /**
     * @inheritdoc
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Amasty_Rma::pending');
        $resultPage->addBreadcrumb(__('RMA'), __('RMA'));
        $resultPage->addBreadcrumb(__('Manage Requests'), __('Manage Requests'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Requests'));

        return $resultPage;
    }
}
