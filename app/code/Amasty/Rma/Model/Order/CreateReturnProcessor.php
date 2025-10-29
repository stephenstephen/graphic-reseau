<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Model\Order;

use Amasty\Rma\Api\CreateReturnProcessorInterface;
use Amasty\Rma\Api\Data\RequestInterface;
use Amasty\Rma\Api\Data\RequestItemInterface;
use Amasty\Rma\Api\Data\ReturnOrderInterfaceFactory;
use Amasty\Rma\Api\Data\ReturnOrderItemInterfaceFactory;
use Amasty\Rma\Api\Data\StatusInterface;
use Amasty\Rma\Api\RequestRepositoryInterface;
use Amasty\Rma\Model\ConfigProvider;
use Amasty\Rma\Model\OptionSource\ItemStatus;
use Amasty\Rma\Model\OptionSource\NoReturnableReasons;
use Amasty\Rma\Model\OptionSource\State;
use Amasty\Rma\Model\Request\ResourceModel\Request;
use Amasty\Rma\Model\Request\ResourceModel\RequestItemCollectionFactory;
use Amasty\Rma\Model\ReturnRules\ReturnRulesProcessor;
use Amasty\Rma\Model\Status\ResourceModel\CollectionFactory as StatusCollectionFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;

class CreateReturnProcessor implements CreateReturnProcessorInterface
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var ReturnOrderInterfaceFactory
     */
    private $returnOrderFactory;

    /**
     * @var ReturnOrderItemInterfaceFactory
     */
    private $returnOrderItemFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var RequestRepositoryInterface
     */
    private $requestRepository;

    /**
     * @var RequestItemCollectionFactory
     */
    private $requestItemCollectionFactory;

    /**
     * @var StatusCollectionFactory
     */
    private $statusCollectionFactory;

    /**
     * @var ReturnRulesProcessor
     */
    private $returnRulesProcessor;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ReturnOrderInterfaceFactory $returnOrderFactory,
        ProductRepositoryInterface $productRepository,
        RequestRepositoryInterface $requestRepository,
        ConfigProvider $configProvider,
        StatusCollectionFactory $statusCollectionFactory,
        RequestItemCollectionFactory $requestItemCollectionFactory,
        ReturnOrderItemInterfaceFactory $returnOrderItemFactory,
        ReturnRulesProcessor $returnRulesProcessor
    ) {
        $this->orderRepository = $orderRepository;
        $this->returnOrderFactory = $returnOrderFactory;
        $this->returnOrderItemFactory = $returnOrderItemFactory;
        $this->productRepository = $productRepository;
        $this->configProvider = $configProvider;
        $this->requestRepository = $requestRepository;
        $this->requestItemCollectionFactory = $requestItemCollectionFactory;
        $this->statusCollectionFactory = $statusCollectionFactory;
        $this->returnRulesProcessor = $returnRulesProcessor;
    }

    /**
     * @inheritdoc
     */
    public function process($orderId, $isAdmin = false)
    {
        /** @var \Amasty\Rma\Api\Data\ReturnOrderInterface $returnOrder */
        $returnOrder = $this->returnOrderFactory->create();
        $order = $this->orderRepository->get((int)$orderId);

        if ($statuses = $this->configProvider->getAllowedOrderStatuses()) {
            if (!in_array($order->getStatus(), $statuses)) {
                return false;
            }
        }

        $returnOrder->setOrder($order);
        $items = [];
        $alreadyRequestedItem = $this->getAlreadyRequestedItems($order->getEntityId());

        foreach ($order->getItems() as $item) {
            if ($this->isDisallowProductType($item)) {
                continue;
            }

            /** @var \Amasty\Rma\Api\Data\ReturnOrderItemInterface $returnItem */
            $returnItem = $this->returnOrderItemFactory->create();

            try {
                $product = $this->productRepository->get($item->getSku());
            } catch (NoSuchEntityException $exception) {
                $product = false;
            }

            if (!$item->getParentItemId()) {
                $qtyShipped = $item->getQtyShipped();
                $qtyCanceled = $item->getQtyCanceled();
                $qtyRefunded = $item->getQtyRefunded();
            } else {
                $qtyShipped = $item->getParentItem()->getQtyShipped();
                $qtyCanceled = $item->getParentItem()->getQtyCanceled();
                $qtyRefunded = $item->getParentItem()->getQtyRefunded();
            }

            $returnItem->setItem($item)
                ->setProductItem($product)
                ->setPurchasedQty($qtyShipped);

            $rmaQty = 0;

            if (isset($alreadyRequestedItem[$item->getItemId()]['qty'])) {
                $rmaQty = $alreadyRequestedItem[$item->getItemId()]['qty'];
            }

            if ($qtyShipped < 0.0001) {
                $returnItem->setIsReturnable(false)
                    ->setNoReturnableReason(NoReturnableReasons::ITEM_WASNT_SHIPPED);
                $items[] = $returnItem;

                continue;
            }

            $orderAvailableQty = $qtyShipped - $qtyCanceled;
            if (!$isAdmin) {
                $orderAvailableQty -= $qtyRefunded;
            }
            if ($orderAvailableQty - $rmaQty <= 0.0001) {
                if ($rmaQty == 0) {
                    $returnItem->setIsReturnable(false)
                        ->setNoReturnableReason(NoReturnableReasons::REFUNDED);
                } else {
                    $returnItem->setIsReturnable(false)
                        ->setNoReturnableReason(NoReturnableReasons::ALREADY_RETURNED)
                        ->setNoReturnableData($alreadyRequestedItem[$item->getItemId()]['requests']);
                }
            } else {
                $isAllowedParentProduct = true;

                if ($item->getParentItemId()) {
                    try {
                        $parentProduct = $this->productRepository->getById($item->getParentItem()->getProductId());
                    } catch (NoSuchEntityException $exception) {
                        $parentProduct = false;
                    }

                    if ($parentProduct) { // no reason to apply rules if no product, order will be checked with child
                        $parentReturnItem = $this->returnOrderItemFactory->create();
                        $parentReturnItem->setItem($item->getParentItem())
                            ->setProductItem($parentProduct)
                            ->setPurchasedQty($qtyShipped);
                        $isAllowedParentProduct = $this->returnRulesProcessor
                            ->processReturn($returnOrder, $parentReturnItem);
                    }
                }

                if ($isAdmin
                    || ($isAllowedParentProduct
                        && $this->returnRulesProcessor->processReturn($returnOrder, $returnItem))
                ) {
                    $returnItem->setIsReturnable(true)
                        ->setAvailableQty($orderAvailableQty - $rmaQty);
                } elseif ($returnItem->getItem()->getPrice() !== (float)$returnItem->getItem()->getOriginalPrice()) {
                    $returnItem->setIsReturnable(false)
                        ->setNoReturnableReason(NoReturnableReasons::ITEM_WAS_ON_SALE);
                } else {
                    $returnItem->setIsReturnable(false)
                        ->setNoReturnableReason(NoReturnableReasons::EXPIRED_PERIOD);
                }
            }

            $items[] = $returnItem;
        }

        $returnOrder->setItems($items);

        return $returnOrder;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderItemInterface $orderItem
     *
     * @return bool
     */
    public function isDisallowProductType(\Magento\Sales\Api\Data\OrderItemInterface $item)
    {
        if ($item->getData('has_children') || $item->getProductType() === 'downloadable') { //TODO check is virtual
            return true;
        }

        return false;
    }

    /**
     * @param int $orderId
     *
     * @return array
     */
    public function getAlreadyRequestedItems($orderId)
    {
        $requestItemCollection = $this->requestItemCollectionFactory->create();
        $requestItemCollection->join(
            Request::TABLE_NAME,
            'main_table.' . RequestItemInterface::REQUEST_ID
            . ' = ' . Request::TABLE_NAME . '.' . RequestInterface::REQUEST_ID,
            [RequestInterface::URL_HASH]
        )->addFieldToFilter(
            Request::TABLE_NAME . '.' . RequestInterface::ORDER_ID,
            (int)$orderId
        )->addFieldToSelect(
            [RequestItemInterface::ORDER_ITEM_ID, RequestItemInterface::REQUEST_ID, RequestItemInterface::QTY]
        )->addFieldToFilter(RequestItemInterface::ITEM_STATUS, ['neq' => ItemStatus::REJECTED]);

        if ($canceledStatuses = $this->getCancelStatusesId()) {
            $requestItemCollection->addFieldToFilter(
                Request::TABLE_NAME . '.' . RequestInterface::STATUS,
                ['nin' => $canceledStatuses]
            );
        }

        $previousItems = [];

        /** @var \Amasty\Rma\Api\Data\RequestItemInterface $requestItem */
        foreach ($requestItemCollection->getData() as $requestItem) {
            if (!isset($previousItems[$requestItem[RequestItemInterface::ORDER_ITEM_ID]]['qty'])) {
                $previousItems[$requestItem[RequestItemInterface::ORDER_ITEM_ID]]['qty'] = 0;
            }

            $previousItems[$requestItem[RequestItemInterface::ORDER_ITEM_ID]]['qty'] +=
                $requestItem[RequestItemInterface::QTY];

            if (!isset($previousItems[$requestItem[RequestItemInterface::ORDER_ITEM_ID]]['requests'])) {
                $previousItems[$requestItem[RequestItemInterface::ORDER_ITEM_ID]]['requests'] = [];
            }

            $previousItems[$requestItem[RequestItemInterface::ORDER_ITEM_ID]]
                ['requests'][$requestItem[RequestItemInterface::REQUEST_ID]] = [
                    RequestInterface::REQUEST_ID => $requestItem[RequestItemInterface::REQUEST_ID],
                    RequestInterface::URL_HASH => $requestItem[RequestInterface::URL_HASH]
                ];
        }

        return $previousItems;
    }

    /**
     * @return array
     */
    public function getCancelStatusesId()
    {
        $canceledStatuses = $this->statusCollectionFactory->create()
            ->addFieldToFilter(StatusInterface::IS_ENABLED, 1)
            ->addFieldToFilter(StatusInterface::STATE, State::CANCELED)
            ->addFieldToSelect(StatusInterface::STATUS_ID)
            ->getData();

        foreach ($canceledStatuses as &$canceledStatus) {
            $canceledStatus = $canceledStatus[StatusInterface::STATUS_ID];
        }

        return $canceledStatuses;
    }
}
