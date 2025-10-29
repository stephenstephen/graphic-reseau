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

namespace Ced\MauticIntegration\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

class CustomerAccount extends AbstractHelper
{
    public $groupRepository;
    public $subscriberFactory;
    public $groupName;
    public $exportProperties;
    public $properties;
    public $connectionManager;

    /**
     * CustomerAccount constructor.
     * @param Context $context
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param ExportPropertiesAndSegments $exportPropertiesAndSegments
     * @param Properties $properties
     * @param ConnectionManager $connectionManager
     */
    public function __construct(
        Context $context,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        ExportPropertiesAndSegments $exportPropertiesAndSegments,
        Properties $properties,
        ConnectionManager $connectionManager
    ) {
        parent::__construct($context);
        $this->groupRepository = $groupRepository;
        $this->subscriberFactory = $subscriberFactory;
        $this->exportProperties = $exportPropertiesAndSegments;
        $this->properties = $properties;
        $this->connectionManager = $connectionManager;
    }

    /**
     * @param $customer
     * @param $arr
     * @param bool $newSubs
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function customerAccountDetailsToExport($customer, $arr, $newSubs = false)
    {
        $arr['firstname'] = $customer->getFirstname();
        $arr['lastname'] = $customer->getData('lastname');

        $defaultAddress = false;
        if ($customer->getDefaultBillingAddress()) {
            $defaultAddress = $customer->getDefaultBillingAddress();
        } elseif ($customer->getDefaultShippingAddress()) {
            $defaultAddress = $customer->getDefaultShippingAddress();
        } elseif ($customer->getAddresses()) {
            foreach ($customer->getAddresses() as $address) {
                $defaultAddress = $address;
                break;
            }
        }

        if ($defaultAddress && $defaultAddress->getTelephone()) {
            $arr['phone'] = $defaultAddress->getTelephone();
            $arr['company'] = $defaultAddress->getCompany();
        }

        $customerGroupProperties = $this->properties->allProperties('customer_group');
        if ($this->connectionManager->isCustomerGroupEnabled('customer_group')) {
            $arr = $this->getCustomerGroupDetails($customer, $customerGroupProperties, $arr, $newSubs);
        }
        $arr = $this->setMappingFields($customer, $arr);
        return $arr;
    }

    /**
     * @param $customer
     * @param $properties
     * @param $arr
     * @param bool $newSubs
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomerGroupDetails($customer, $properties, $arr, $newSubs = false)
    {
        foreach ($properties as $property) {
            if ($this->exportProperties->canSetProperty(
                $property['alias'],
                \Ced\MauticIntegration\Model\Cedmautic::TYPE_PROPERTY
            )) {
                switch ($property['alias']) {
                    case 'ced_customer_group':
                        if (isset($customer['group_id'])) {
                            $arr['ced_customer_group'] = $this->getCustomerGroup($customer['group_id']);
                        }
                        break;

                    case 'ced_newsletter_subs':
                        if (isset($customer['email'])) {
                            if ($newSubs && ($newSubs == 'yes' || $newSubs == 'no')) {
                                $arr['ced_newsletter_subs'] = $newSubs;
                            } else {
                                $arr['ced_newsletter_subs'] = $this->getNewsLetterSubscription($customer->getEmail());
                            }
                        }
                        break;

                    case 'ced_customer_cart_id':
                        $arr['ced_customer_cart_id'] = $customer->getId();
                        break;

                    case 'ced_acc_creation_date':
                        $arr['ced_acc_creation_date'] = date('Y-m-d H:i:s', strtotime($customer->getCreatedAt()));
                        break;
                }
            }
        }

        return $arr;
    }

    /**
     * @param $customer
     * @param $arr
     * @return mixed
     */
    public function setMappingFields($customer, $arr)
    {
        if (json_decode($this->getMappingFields())) {
            $mappingArray = json_decode($this->getMappingFields());
            foreach ($mappingArray as $key => $value) {
                if ($customer->getData($value)) {
                    $arr[$key] = $customer->getData($value);
                }
            }
        }

        return $arr;
    }

    /**
     * @return mixed
     */
    public function getMappingFields()
    {
        return $this->scopeConfig->getValue('mautic_integration/mautic_fields_mapping/mapping_table');
    }

    /**
     * @param $groupId
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomerGroup($groupId)
    {
        if (!isset($this->groupName[$groupId])) {
            $this->groupName[$groupId] = $this->groupRepository->getById($groupId)->getCode();
        }

        return $this->groupName[$groupId];
    }

    /**
     * @param $email
     * @return string
     */
    public function getNewsLetterSubscription($email)
    {
        $checkSubscriber = $this->subscriberFactory->create()->loadByEmail($email);
        if ($checkSubscriber->isSubscribed()) {
            return "yes";
        } else {
            return "no";
        }
    }
}
