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
namespace Ced\MauticIntegration\Controller\Adminhtml\ErrorLog;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Ced\MauticIntegration\Model\ResourceModel\ErrorLog\CollectionFactory;
use Ced\MauticIntegration\Helper\ConnectionManager;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassDelete
 * @package Ced\MauticIntegration\Controller\Adminhtml\ErrorLog
 */
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var string
     */
    protected $redirectUrl = 'mauticintegration/errorlog/index';

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @var object
     */
    protected $collectionFactory;

    /** @var \Ced\MauticIntegration\Helper\ConnectionManager  */
    public $connectionManager;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ConnectionManager $connectionManager
    )
    {
        parent::__construct($context);
        $this->filter = $filter;
        $this->connectionManager=$connectionManager;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            if (!$this->connectionManager->getModuleStatus()) {
                $this->messageManager->addErrorMessage(
                    'Mautic integration is disabled. Please enable it from system configuration.'
                );
                $this->_redirect('admin');
            }
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $totalCount = $collection->getSize();
            $collection->walk('delete');
            $this->messageManager->addSuccessMessage(__("%1 Error Log(s) have been deleted.", $totalCount));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath($this->redirectUrl);
    }

    /**
     * IsALLowed
     * @return boolean
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ced_MauticIntegration::error_log_mass_delete');
    }
}
