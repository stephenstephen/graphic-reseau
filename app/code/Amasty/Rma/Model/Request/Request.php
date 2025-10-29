<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Model\Request;

use Amasty\Rma\Api\Data\RequestInterface;
use Magento\Framework\Model\AbstractModel;

class Request extends AbstractModel implements RequestInterface
{
    /**
     * @var \Amasty\Base\Model\Serializer
     */
    private $serializer;

    /**
     * @var \Amasty\Rma\Api\Data\RequestCustomFieldInterfaceFactory
     */
    private $customFieldFactory;

    public function __construct(
        \Amasty\Base\Model\Serializer $serializer,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Amasty\Rma\Api\Data\RequestCustomFieldInterfaceFactory $customFieldFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->serializer = $serializer;
        $this->customFieldFactory = $customFieldFactory;
    }

    public function _construct()
    {
        parent::_construct();
        $this->_init(\Amasty\Rma\Model\Request\ResourceModel\Request::class);
        $this->setIdFieldName(RequestInterface::REQUEST_ID);
    }

    /**
     * @inheritdoc
     */
    public function setRequestId($requestId)
    {
        return $this->setData(RequestInterface::REQUEST_ID, (int)$requestId);
    }

    /**
     * @inheritdoc
     */
    public function getRequestId()
    {
        return (int)$this->_getData(RequestInterface::REQUEST_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrderId($orderId)
    {
        return $this->setData(RequestInterface::ORDER_ID, (int)$orderId);
    }

    /**
     * @inheritdoc
     */
    public function getOrderId()
    {
        return (int)$this->_getData(RequestInterface::ORDER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setStoreId($storeId)
    {
        return $this->setData(RequestInterface::STORE_ID, (int)$storeId);
    }

    /**
     * @inheritdoc
     */
    public function getStoreId()
    {
        return (int)$this->_getData(RequestInterface::STORE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->setData(RequestInterface::CREATED_AT, $createdAt);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt()
    {
        return $this->_getData(RequestInterface::CREATED_AT);
    }

    /**
     * @inheritdoc
     */
    public function setModifiedAt(string $modifiedAt)
    {
        $this->setData(RequestInterface::MODIFIED_AT, $modifiedAt);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getModifiedAt()
    {
        return $this->_getData(RequestInterface::MODIFIED_AT);
    }

    /**
     * @inheritdoc
     */
    public function setStatus($status)
    {
        return $this->setData(RequestInterface::STATUS, (int)$status);
    }

    /**
     * @inheritdoc
     */
    public function getStatus()
    {
        return (int)$this->_getData(RequestInterface::STATUS);
    }

    /**
     * @inheritdoc
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(RequestInterface::CUSTOMER_ID, (int)$customerId);
    }

    /**
     * @inheritdoc
     */
    public function getCustomerId()
    {
        return (int)$this->_getData(RequestInterface::CUSTOMER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerName($customerName)
    {
        return $this->setData(RequestInterface::CUSTOMER_NAME, $customerName);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerName()
    {
        return $this->_getData(RequestInterface::CUSTOMER_NAME);
    }

    /**
     * @inheritdoc
     */
    public function setUrlHash($urlHash)
    {
        return $this->setData(RequestInterface::URL_HASH, $urlHash);
    }

    /**
     * @inheritdoc
     */
    public function getUrlHash()
    {
        return $this->_getData(RequestInterface::URL_HASH);
    }

    /**
     * @inheritdoc
     */
    public function setManagerId($managerId)
    {
        return $this->setData(RequestInterface::MANAGER_ID, (int)$managerId);
    }

    /**
     * @inheritdoc
     */
    public function getManagerId()
    {
        return (int)$this->_getData(RequestInterface::MANAGER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCustomFields($customFields)
    {
        if (is_array($customFields)) {
            $fieldsData = [];
            foreach ($customFields as $field) {
                $fieldsData[$field->getKey()] = $field->getValue();
            }

            return $this->setData(RequestInterface::CUSTOM_FIELDS, $this->serializer->serialize($fieldsData));
        }

        return $this->setData(RequestInterface::CUSTOM_FIELDS, (string)$customFields);
    }

    /**
     * @inheritdoc
     */
    public function getCustomFields(): array
    {
        $customFields = $this->_getData(RequestInterface::CUSTOM_FIELDS);
        if (!empty($customFields)) {
            $customFieldsData = $this->serializer->unserialize($customFields);
            $notEmpty = false;
            if (is_array($customFieldsData)) {
                foreach ($customFieldsData as $customFieldValue) {
                    if (!empty($customFieldValue)) {
                        $notEmpty = true;
                        break;
                    }
                }
            }

            if ($notEmpty) {
                $customFields = [];
                foreach ($customFieldsData as $fieldKey => $fieldValue) {
                    $customFields[] = $this->customFieldFactory->create([
                        'key' => $fieldKey,
                        'value' => $fieldValue,
                    ]);
                }

                return $customFields;
            }
        }

        return [];
    }

    /**
     * @inheritdoc
     */
    public function setRating($rating)
    {
        return $this->setData(RequestInterface::RATING, (int)$rating);
    }

    /**
     * @inheritdoc
     */
    public function getRating()
    {
        return (int)$this->_getData(RequestInterface::RATING);
    }

    /**
     * @inheritdoc
     */
    public function setRatingComment($ratingComment)
    {
        return $this->setData(RequestInterface::RATING_COMMENT, $ratingComment);
    }

    /**
     * @inheritdoc
     */
    public function getRatingComment()
    {
        return $this->_getData(RequestInterface::RATING_COMMENT);
    }

    /**
     * @inheritDoc
     */
    public function setNote($note)
    {
        return $this->setData(RequestInterface::NOTE, $note);
    }

    /**
     * @inheritDoc
     */
    public function getNote()
    {
        return $this->_getData(RequestInterface::NOTE);
    }

    /**
     * @inheritdoc
     */
    public function setShippingLabel($label)
    {
        return $this->setData(RequestInterface::SHIPPING_LABEL, $label);
    }

    /**
     * @inheritdoc
     */
    public function getShippingLabel()
    {
        return $this->_getData(RequestInterface::SHIPPING_LABEL);
    }

    /**
     * @inheritDoc
     */
    public function setRequestItems($requestItems)
    {
        return $this->setData(RequestInterface::REQUEST_ITEMS, $requestItems);
    }

    /**
     * @inheritDoc
     */
    public function getRequestItems()
    {
        if ($items = $this->_getData(RequestInterface::REQUEST_ITEMS)) {
            return $items;
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function setTrackingNumbers($trackingNumbers)
    {
        return $this->setData(RequestInterface::TRACKING_NUMBERS, $trackingNumbers);
    }

    /**
     * @inheritDoc
     */
    public function getTrackingNumbers()
    {
        if ($items = $this->_getData(RequestInterface::TRACKING_NUMBERS)) {
            return $items;
        }

        return [];
    }
}
