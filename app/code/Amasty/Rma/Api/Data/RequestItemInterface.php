<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Api\Data;

/**
 * Interface RequestItemInterface
 */
interface RequestItemInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const REQUEST_ITEM_ID = 'request_item_id';
    const REQUEST_ID = 'request_id';
    const ORDER_ITEM_ID = 'order_item_id';
    const QTY = 'qty';
    const REQUEST_QTY = 'request_qty';
    const REASON_ID = 'reason_id';
    const CONDITION_ID = 'condition_id';
    const RESOLUTION_ID = 'resolution_id';
    const ITEM_STATUS = 'item_status';
    /**#@-*/

    /**
     * @param int $requestItemId
     *
     * @return \Amasty\Rma\Api\Data\RequestItemInterface
     */
    public function setRequestItemId($requestItemId);

    /**
     * @return int
     */
    public function getRequestItemId();

    /**
     * @param int $requestId
     *
     * @return \Amasty\Rma\Api\Data\RequestItemInterface
     */
    public function setRequestId($requestId);

    /**
     * @return int
     */
    public function getRequestId();

    /**
     * @param int $orderItemId
     *
     * @return \Amasty\Rma\Api\Data\RequestItemInterface
     */
    public function setOrderItemId($orderItemId);

    /**
     * @return int
     */
    public function getOrderItemId();

    /**
     * @param double $qty
     *
     * @return \Amasty\Rma\Api\Data\RequestItemInterface
     */
    public function setQty($qty);

    /**
     * @return double
     */
    public function getQty();

    /**
     * @param double $requestQty
     *
     * @return \Amasty\Rma\Api\Data\RequestItemInterface
     */
    public function setRequestQty($requestQty);

    /**
     * @return double
     */
    public function getRequestQty();

    /**
     * @param int $reasonId
     *
     * @return \Amasty\Rma\Api\Data\RequestItemInterface
     */
    public function setReasonId($reasonId);

    /**
     * @return int
     */
    public function getReasonId();

    /**
     * @param int $conditionId
     *
     * @return \Amasty\Rma\Api\Data\RequestItemInterface
     */
    public function setConditionId($conditionId);

    /**
     * @return int
     */
    public function getConditionId();

    /**
     * @param int $resolutionId
     *
     * @return \Amasty\Rma\Api\Data\RequestItemInterface
     */
    public function setResolutionId($resolutionId);

    /**
     * @return int
     */
    public function getResolutionId();

    /**
     * @param int $itemStatus
     *
     * @return \Amasty\Rma\Api\Data\RequestItemInterface
     */
    public function setItemStatus($itemStatus);

    /**
     * @return int
     */
    public function getItemStatus();
}
