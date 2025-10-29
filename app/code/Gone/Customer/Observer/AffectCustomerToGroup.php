<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Customer\Observer;

use Exception;
use Gone\Customer\Helper\Customer;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Data\Address;
use Magento\Customer\Model\GroupFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AffectCustomerToGroup implements ObserverInterface
{

    protected CustomerRepositoryInterface $_customerRepository;
    protected GroupFactory $_customerGroupFactory;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        GroupFactory                $customerGroupFactory
    )
    {
        $this->_customerRepository = $customerRepository;
        $this->_customerGroupFactory = $customerGroupFactory;
    }

    /**
     * event : customer_register_success
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Customer\Model\Data\Customer $customer */
        $customer = $observer->getEvent()->getCustomer();

        /** @var Address $customerAddress */
        $customerAddress = $customer->getAddresses()[0];

        if (!empty($customerAddress->getCompany())) {
            if (in_array($customerAddress->getCountryId(), Customer::FR_CODES)) {
                $bToBGroup = $this->_customerGroupFactory->create()
                    ->load(Customer::BTOB_GROUP_NAME, 'customer_group_code');
            } else {
                $bToBGroup = $this->_customerGroupFactory->create()
                    ->load(Customer::BTOB_GROUP_NAME_EU, 'customer_group_code');
            }

            if ($bToBGroup->getId()) {
                $customer->setGroupId($bToBGroup->getId());
                try {
                    $this->_customerRepository->save($customer);
                } catch (Exception $e) {
                }
            }
        }
    }
}
