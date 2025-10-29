<?php
/**
 * Chronopost
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category  Chronopost
 * @package   Chronopost_Chronorelais
 * @copyright Copyright (c) 2021 Chronopost
 */
declare(strict_types=1);

namespace Chronopost\Chronorelais\Controller\Adminhtml\Sales\Impression;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Chronopost\Chronorelais\Model\OrderExportStatusFactory;
use Chronopost\Chronorelais\Helper\Data as HelperData;

/**
 * Class LivraisonSamediStatusMass
 *
 * @package Chronopost\Chronorelais\Controller\Adminhtml\Sales\Impression
 */
class LivraisonSamediStatusMass extends \Magento\Backend\App\Action
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var OrderExportStatusFactory
     */
    protected $orderExportStatusFactory;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * LivraisonSamediStatusMass constructor.
     *
     * @param Context                  $context
     * @param PageFactory              $resultPageFactory
     * @param OrderExportStatusFactory $exportStatusFactory
     * @param Filter                   $filter
     * @param CollectionFactory        $collectionFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        OrderExportStatusFactory $exportStatusFactory,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->orderExportStatusFactory = $exportStatusFactory;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Check is the current user is allowed to access this section
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Chronopost_Chronorelais::sales');
    }

    /**
     * Execute action
     *
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $status = $this->getRequest()->getParam('status');

        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            foreach ($collection->getItems() as $order) {
                $shippingMethod = explode('_', $order->getShippingMethod());
                $shippingMethod = isset($shippingMethod[1]) ? $shippingMethod[1] : $shippingMethod[0];

                $shippingMethodsAllowed = HelperData::SHIPPING_METHODS_SATURDAY_ALLOWED;
                if (!in_array($shippingMethod, $shippingMethodsAllowed)) {
                    $this->messageManager->addErrorMessage(
                        __('The Saturday option is not available for order %1', $order->getIncrementId())
                    );

                    continue;
                }

                $orderStatus = $this->orderExportStatusFactory->create()->load($order->getId(), 'order_id');
                $orderStatus
                    ->setData('order_id', $order->getId())
                    ->setData('livraison_le_samedi', $status)
                    ->save();

                $this->messageManager->addSuccessMessage(
                    __('The Saturday option has been forced for the order %1', $order->getIncrementId())
                );
            }
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__($exception->getMessage()));
        }

        $resultRedirect->setPath('chronorelais/sales/impression');

        return $resultRedirect;
    }
}
