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

class ExportBulkCustomers extends \Magento\Backend\App\Action
{
    const CHUNK_SIZE = 50;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    public $customerCollection;

    /**
     * @var \Magento\Backend\Model\Session
     */
    public $session;

    /**
     * @var \Magento\Framework\Registry
     */
    public $registry;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;

    public $exportCustomers;

    /**
     * ExportBulkCustomers constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $collection
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $collection,
        \Ced\MauticIntegration\Helper\ExportCustomers $exportCustomers
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->customerCollection = $collection;
        $this->session =  $context->getSession();
        $this->registry = $registry;
        $this->exportCustomers = $exportCustomers;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        // case 1 ajax request for chunk processing
        $batchId = $this->getRequest()->getParam('batchid');
        if (isset($batchId)) {
            $resultJson = $this->resultJsonFactory->create();
            $customerIds = $this->session->getCustomerToExport();
            try {
                $response = $this->exportCustomers->createBulkContacts($customerIds[$batchId]);
            } catch (\Exception $e) {
                return $resultJson->setData([
                    'error' => count($customerIds) . " Customer Export Failed",
                    'exceptionmessages' => ['errors' => $e->getMessage()],
                ]);
            }
            if (isset($customerIds[$batchId]) && empty($response)) {
                return $resultJson->setData([
                    'success' => count($customerIds[$batchId]) . " Customer Exported Successfully",
                    'messages' => "Customer Exported Successfully"
                ]);
            }
            return $resultJson->setData([
                'error' => count($customerIds[$batchId]) . " Customer Export Failed",
                'messages' =>  $response,
            ]);
        }

        $customerIds = $this->customerCollection->create()->getAllIds();

        if (count($customerIds) == 0) {
            $this->messageManager->addErrorMessage('No Customer Found to Export.');
            $resultRedirect = $this->resultFactory->create('redirect');
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }

        // case 2 normal uploading if current ids are more than chunk size.
        $customerIds = array_chunk($customerIds, self::CHUNK_SIZE);
        $this->registry->register('customerids', count($customerIds));
        $this->session->setCustomerToExport($customerIds);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ced_MauticIntegration::mautic');
        $resultPage->getConfig()->getTitle()->prepend(__('Export Customers'));
        return $resultPage;
    }
}
