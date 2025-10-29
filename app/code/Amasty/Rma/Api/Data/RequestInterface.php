<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Api\Data;

/**
 * Interface RequestInterface
 */
interface RequestInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const REQUEST_ID = 'request_id';
    const ORDER_ID = 'order_id';
    const STORE_ID = 'store_id';
    const CREATED_AT = 'created_at';
    const MODIFIED_AT = 'modified_at';
    const STATUS = 'status';
    const CUSTOMER_ID = 'customer_id';
    const CUSTOMER_NAME = 'customer_name';
    const URL_HASH = 'url_hash';
    const MANAGER_ID = 'manager_id';
    const CUSTOM_FIELDS = 'custom_fields';
    const RATING = 'rating';
    const RATING_COMMENT = 'rating_comment';
    const NOTE = 'note';
    const REQUEST_ITEMS = 'request_items';
    const TRACKING_NUMBERS = 'tracking_numbers';
    const SHIPPING_LABEL = 'shipping_label';
    /**#@-*/

    /**
     * @param int $requestId
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setRequestId($requestId);

    /**
     * @return int
     */
    public function getRequestId();

    /**
     * @param int $orderId
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setOrderId($orderId);

    /**
     * @return int
     */
    public function getOrderId();

    /**
     * @param int $storeId
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setStoreId($storeId);

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param string $createdAt
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setCreatedAt(string $createdAt);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $modifiedAt
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setModifiedAt(string $modifiedAt);

    /**
     * @return string
     */
    public function getModifiedAt();

    /**
     * @param int $status
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setStatus($status);

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @param int $customerId
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setCustomerId($customerId);

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @param string $customerName
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setCustomerName($customerName);

    /**
     * @return string
     */
    public function getCustomerName();

    /**
     * @param string $urlHash
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setUrlHash($urlHash);

    /**
     * @return string
     */
    public function getUrlHash();

    /**
     * @param int $managerId
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setManagerId($managerId);

    /**
     * @return int
     */
    public function getManagerId();

    /**
     * @param \Amasty\Rma\Api\Data\RequestCustomFieldInterface[] $customFields
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setCustomFields($customFields);

    /**
     * @return \Amasty\Rma\Api\Data\RequestCustomFieldInterface[]
     */
    public function getCustomFields(): array;

    /**
     * @param int $rating
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setRating($rating);

    /**
     * @return int
     */
    public function getRating();

    /**
     * @param string $ratingComment
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setRatingComment($ratingComment);

    /**
     * @return string
     */
    public function getRatingComment();

    /**
     * @param string $note
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setNote($note);

    /**
     * @return string
     */
    public function getNote();

    /**
     * @param string $label
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setShippingLabel($label);

    /**
     * @return string
     */
    public function getShippingLabel();

    /**
     * @param \Amasty\Rma\Api\Data\RequestItemInterface[] $requestItems
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setRequestItems($requestItems);

    /**
     * @return \Amasty\Rma\Api\Data\RequestItemInterface[]
     */
    public function getRequestItems();

    /**
     * @param \Amasty\Rma\Api\Data\TrackingInterface[] $trackingNumbers
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function setTrackingNumbers($trackingNumbers);

    /**
     * @return \Amasty\Rma\Api\Data\TrackingInterface[]
     */
    public function getTrackingNumbers();
}
