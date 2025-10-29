<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Contact\Model;

use Gone\Contact\Api\Data\ContactInterface;
use Gone\Contact\Api\Data\ContactInterfaceFactory;
use Gone\Contact\Model\ResourceModel\Contact\Collection;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class Contact extends AbstractModel
{

    protected $contactDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'gone_contact_contact';

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ContactInterfaceFactory $contactDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param ResourceModel\Contact $resource
     * @param Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ContactInterfaceFactory $contactDataFactory,
        DataObjectHelper $dataObjectHelper,
        ResourceModel\Contact $resource,
        Collection $resourceCollection,
        array $data = []
    ) {
        $this->contactDataFactory = $contactDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve contact model with contact data
     * @return ContactInterface
     */
    public function getDataModel()
    {
        $contactData = $this->getData();

        $contactDataObject = $this->contactDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $contactDataObject,
            $contactData,
            ContactInterface::class
        );

        return $contactDataObject;
    }
}
