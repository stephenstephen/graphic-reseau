<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Gone\Customer\Helper;

use Magento\Catalog\Helper\Product;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\GroupFactory;

class Customer extends Product
{

    public const BTOB_GROUP_NAME = 'B2B';
    public const BTOB_GROUP_NAME_EU = 'B2B_EU';
    public const STUDENT_GROUP_NAME = 'Etudiants';

    public const FR_CODES = ['FR'];

    protected GroupFactory $_customerGroupFactory;

    public function __construct(
        GroupFactory $customerGroupFactory
    ) {
        $this->_customerGroupFactory = $customerGroupFactory;
    }

    /**
     * @param CustomerInterface $customer
     * @return bool
     */
    public function isConnectedCustomerBtoB(CustomerInterface $customer): bool
    {
           return $this->getGroupIdByName(self::BTOB_GROUP_NAME) == $customer->getGroupId() ? true : false;
    }

    public function getGroupIdByName(string $name) : ?int
    {
        return $this->_customerGroupFactory->create()
            ->load($name, 'customer_group_code')->getId();
    }
}
