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

class ExportCustomers extends AbstractHelper
{
    const EXPORT_DEFAULT = 'default';
    const EXPORT_CUSTOMER_ACCOUNT = 'customer_account';
    const EXPORT_CUSTOMER_ORDER = 'customer_order';
    const EXPORT_CUSTOMER_CART = 'customer_cart';
    const EXPORT_CUSTOMER_BY_CRON = 'export_by_cron';

    public $customer;
    public $order;
    public $cart;
    public $customerCollectionFactory;
    public $orderCollectionFactory;
    public $quoteCollectionFactory;
    public $connectionManager;
    public $orderDetails;
    public $lastOrderDetails;
    public $firstOrderDetails;
    public $lastCompletedOrderDetails;
    public $feedback;
    public $customerAccount;
    public $abandonedCart;
    public $dateTime;

    /**
     * ExportCustomers constructor.
     * @param Context $context
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory $quoteCollectionFactory
     * @param ConnectionManager $connectionManager
     * @param OrderDetails $orderDetails
     * @param CustomerAccount $customerAccount
     * @param AbandonedCart $abandonedCart
     */
    public function __construct(
        Context $context,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory $quoteCollectionFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        ConnectionManager $connectionManager,
        OrderDetails $orderDetails,
        CustomerAccount $customerAccount,
        AbandonedCart $abandonedCart
    ) {
        parent::__construct($context);
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->quoteCollectionFactory = $quoteCollectionFactory;
        $this->connectionManager = $connectionManager;
        $this->feedback = $this->connectionManager->isCustomerGroupEnabled('feedback');
        $this->orderDetails = $orderDetails;
        $this->customerAccount = $customerAccount;
        $this->abandonedCart = $abandonedCart;
        $this->dateTime = $dateTime;
    }

    /**
     * @param $customerIds
     * @return array
     * Export all the details of customer
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createBulkContacts($customerIds)
    {
        $endpoint = '/api/contacts/batch/new';
        $method = 'POST';
        $requestParam = [];
        $errors = [];
        $bulkCustomers = $this->customerCollectionFactory->create()->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', ['in' => $customerIds]);

        $bulkOrders = $this->orderCollectionFactory->create()->addAttributeToSelect('*')
            ->addFieldToFilter('customer_id', ['in' => $customerIds]);
        $from = date('Y-m-d H:i:s ', 0);
        $to = $this->dateTime->date('Y-m-d H:i:s ', strtotime('-' . $this->getAbandonedCartTime() . ' minutes'));
        $bulkCarts = $this->quoteCollectionFactory->create()
            ->addFieldToFilter('is_active', ['eq' => 1])
            ->addFieldToFilter('items_count', ['gt' => 0])
            ->addFieldToFilter('customer_id', ['in' => $customerIds])
            ->addFieldToFilter('updated_at', ['from' => $from, 'to' => $to, 'date' => true]);
        $ordersList = [];
        $lastCompletedOrder = [];
        foreach ($bulkOrders as $order) {
            $ordersList[$order->getCustomerId()][$order->getId()] = $order;
            if ($this->feedback) {
                if ($order->getData('status') == 'complete') {
                    if (isset($lastCompletedOrder[$order->getCustomerId()]) &&
                        $lastCompletedOrder[$order->getCustomerId()]->getData('updated_at') < $order
                            ->getData('updated_at')) {
                        $lastCompletedOrder[$order->getCustomerId()] = $order;
                    } elseif (!isset($lastCompletedOrder[$order->getCustomerId()])) {
                        $lastCompletedOrder[$order->getCustomerId()] = $order;
                    }
                }
            }
        }

        $cartList = [];
        foreach ($bulkCarts as $cart) {
            $cartList[$cart->getCustomerId()][$cart->getId()] = $cart;
        }

        foreach ($bulkCustomers as $customer) {
            $arr = [];
            if (isset($ordersList[$customer->getId()])) {
                $orders = $ordersList[$customer->getId()];
            } else {
                $orders = null;
            }

            if (isset($lastCompletedOrder[$customer->getId()])) {
                $completedOrder = $lastCompletedOrder[$customer->getId()];
            } else {
                $completedOrder = null;
            }

            if (isset($cartList[$customer->getId()])) {
                $carts = $cartList[$customer->getId()];
            } else {
                $carts = null;
            }

            $arr = $this->getContactDetails($customer, $orders, $completedOrder, $carts, $arr, $this::EXPORT_DEFAULT);
            $requestParam[] = $arr;
        }
        if(count($requestParam)) {
            $contactResponse = $this->connectionManager->createRequest($method, $endpoint, $requestParam);
            if (isset($contactResponse['status']) && $contactResponse['status'] === false) {
                $errors[$customer->getEmail()] = [
                    'email' => $customer->getEmail(),
                    'id' => $customer->getId(),
                    'errors' => isset($contactResponse['message']) ? $contactResponse['message'] : 'Unkown Error'
                ];
            } elseif (isset($contactResponse['status_code']) &&
                !in_array($contactResponse['status_code'], [200, 201])) {
                $msg = [];
                $response = json_decode($contactResponse['response'], true);
                if (isset($response['errors'])) {
                    foreach ($response['errors'] as $error) {
                        $msg[] = $error['message'];
                    }
                }
                $errors[$customer->getEmail()] = [
                    'email' => $customer->getEmail(),
                    'id' => $customer->getId(),
                    'errors' => $msg
                ];
            }
        }
        return $errors;
    }

    /**
     * @param $customerIds
     * Export Only Customer's account details
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function exportCustomerAccount($customerIds)
    {
        $endpoint = '/api/contacts/new';
        $method = 'POST';
        $allCustomers = $this->customerCollectionFactory->create()->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', ['in' => $customerIds]);
        $from = date('Y-m-d H:i:s ', 0);
        $to = $this->dateTime->date('Y-m-d H:i:s ', strtotime('-' . $this->getAbandonedCartTime() . ' minutes'));
        $carts = $this->quoteCollectionFactory->create()
            ->addFieldToFilter('is_active', ['eq' => 1])
            ->addFieldToFilter('items_count', ['gt' => 0])
            ->addFieldToFilter('customer_id', ['in' => $customerIds])
            ->addFieldToFilter('updated_at', ['from' => $from, 'to' => $to, 'date' => true]);
        $cartList = [];
        foreach ($carts as $cart) {
            $cartList[$cart->getCustomerId()][$cart->getId()] = $cart;
        }
        foreach ($allCustomers as $customer) {
            $arr = [];
            if (isset($cartList[$customer->getId()])) {
                $carts = $cartList[$customer->getId()];
            } else {
                $carts = null;
            }
            $arr = $this->getContactDetails(
                $customer,
                null,
                null,
                $carts,
                $arr,
                $this::EXPORT_CUSTOMER_ACCOUNT
            );
            $contactResponse = $this->connectionManager->createRequest($method, $endpoint, $arr);
        }
    }

    /**
     * @param $customerIds
     * Export Only Customer's orders
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function exportCustomerOrder($customerIds)
    {
        $endpoint = '/api/contacts/new';
        $method = 'POST';
        $allCustomersData = $this->customerCollectionFactory->create()->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', ['in' => $customerIds]);
        $allOrdersData = $this->orderCollectionFactory->create()->addAttributeToSelect('*')
            ->addFieldToFilter('customer_id', ['in' => $customerIds]);
        $from = date('Y-m-d H:i:s ', 0);
        $to = $this->dateTime->date('Y-m-d H:i:s ', strtotime('-' . $this->getAbandonedCartTime() . ' minutes'));
        $allCartsData = $this->quoteCollectionFactory->create()
            ->addFieldToFilter('is_active', ['eq' => 1])
            ->addFieldToFilter('items_count', ['gt' => 0])
            ->addFieldToFilter('customer_id', ['in' => $customerIds])
            ->addFieldToFilter('updated_at', ['from' => $from, 'to' => $to, 'date' => true]);

        $ordersList = [];
        $lastCompletedOrder = [];
        foreach ($allOrdersData as $order) {
            $ordersList[$order->getCustomerId()][$order->getId()] = $order;
            if ($this->feedback) {
                if ($order->getData('status') == 'complete') {
                    if (isset($lastCompletedOrder[$order->getCustomerId()]) &&
                        $lastCompletedOrder[$order->getCustomerId()]->getData('updated_at') < $order
                            ->getData('updated_at')) {
                        $lastCompletedOrder[$order->getCustomerId()] = $order;
                    } elseif (!isset($lastCompletedOrder[$order->getCustomerId()])) {
                        $lastCompletedOrder[$order->getCustomerId()] = $order;
                    }
                }
            }
        }

        $cartList = [];
        foreach ($allCartsData as $cart) {
            $cartList[$cart->getCustomerId()][$cart->getId()] = $cart;
        }

        foreach ($allCustomersData as $customer) {
            $arr = [];
            if (isset($ordersList[$customer->getId()])) {
                $orders = $ordersList[$customer->getId()];
            } else {
                $orders = null;
            }

            if (isset($lastCompletedOrder[$customer->getId()])) {
                $completedOrder = $lastCompletedOrder[$customer->getId()];
            } else {
                $completedOrder = null;
            }

            if (isset($cartList[$customer->getId()])) {
                $carts = $cartList[$customer->getId()];
            } else {
                $carts = null;
            }

            $arr = $this->getContactDetails(
                $customer,
                $orders,
                $completedOrder,
                $carts,
                $arr,
                $this::EXPORT_CUSTOMER_ORDER
            );

            $contactResponse = $this->connectionManager->createRequest($method, $endpoint, $arr);
        }
    }

    /**
     * Export customer having abandoned cart by cron
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function exportCustomerCart()
    {
        $endpoint = '/api/contacts/new';
        $method = 'POST';
        $abandonedCartTime = $this->getAbandonedCartTime();
        $time = 0;
        $cronTime = $this->scopeConfig->getValue('mautic_integration/mautic_abandoned_cart/abandoned_cart_cron_time');
        if ($cronTime == \Ced\MauticIntegration\Block\Adminhtml\System\Config\AbandonedCartCronTime::ONCE_IN_A_DAY) {
            $time = 1440;
        } elseif ($cronTime == \Ced\MauticIntegration\Block\Adminhtml\System\Config\AbandonedCartCronTime::
            TWICE_A_DAY) {
            $time = 720;
        } elseif ($cronTime == \Ced\MauticIntegration\Block\Adminhtml\System\Config\AbandonedCartCronTime::
            FOUR_TIMES_A_DAY) {
            $time = 360;
        } elseif ($cronTime == \Ced\MauticIntegration\Block\Adminhtml\System\Config\AbandonedCartCronTime::EVERY_HOUR) {
            $time = 60;
        } elseif ($cronTime == \Ced\MauticIntegration\Block\Adminhtml\System\Config\AbandonedCartCronTime::
            EVERY_FIVE_MINUTES) {
            $time = 5;
        }

        $addedTime = (int)$time + (int)$abandonedCartTime;
        $from = $this->dateTime->date('Y-m-d H:i:s ', strtotime('-'.$addedTime.'minutes'));
        $to = $this->dateTime->date('Y-m-d H:i:s ', strtotime('-'.$abandonedCartTime.'minutes'));

        $carts = $this->quoteCollectionFactory->create()
            ->addFieldToFilter('is_active', ['eq' => 1])
            ->addFieldToFilter('items_count', ['gt' => 0])
            ->addFieldToFilter('customer_id', ['notnull' => true])
            ->addFieldToFilter('updated_at', [
                'from' => $from,
                'to' => $to,
                'date' => true
            ]);
        $allIds = [];
        $cartList = [];
        foreach ($carts as $cart) {
            $cartList[$cart->getCustomerId()][$cart->getId()] = $cart;
            array_push($allIds, $cart->getCustomerId());
        }

        $allCustomers = $this->customerCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', ['in' => $allIds]);

        foreach ($allCustomers as $customer) {
            $arr = [];
            if (isset($cartList[$customer->getId()])) {
                $carts = $cartList[$customer->getId()];
            } else {
                $carts = null;
            }

            $arr = $this->getContactDetails(
                $customer,
                null,
                null,
                $carts,
                $arr,
                $this::EXPORT_CUSTOMER_CART
            );
            $contactResponse = $this->connectionManager->createRequest($method, $endpoint, $arr);
        }
    }

    /**
     * Export customer data by cron
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function exportCustomersByCron()
    {
        $endpoint = '/api/contacts/batch/new';
        $method = 'POST';
        $requestParam = [];
        $cronTime = $this->scopeConfig->getValue('mautic_integration/mautic_customer_export/cron_time');
        if ($cronTime == \Ced\MauticIntegration\Block\Adminhtml\System\Config\CronTime::ONCE_IN_A_DAY) {
            $time = 1440;
        } elseif ($cronTime == \Ced\MauticIntegration\Block\Adminhtml\System\Config\CronTime::TWICE_A_DAY) {
            $time = 720;
        } elseif ($cronTime == \Ced\MauticIntegration\Block\Adminhtml\System\Config\CronTime::FOUR_TIMES_A_DAY) {
            $time = 360;
        } elseif ($cronTime == \Ced\MauticIntegration\Block\Adminhtml\System\Config\CronTime::EVERY_HOUR) {
            $time = 60;
        } elseif ($cronTime == \Ced\MauticIntegration\Block\Adminhtml\System\Config\CronTime::EVERY_FIVE_MINUTES) {
            $time = 5;
        }

        $orderIds = $this->orderCollectionFactory->create()
            ->addAttributeToSelect('customer_id')
            ->addFieldToFilter('updated_at', [
                'from' => $this->dateTime->date('Y-m-d H:i:s ', strtotime('-' . $time . ' minutes')),
                'to' => $this->dateTime->date('Y-m-d H:i:s ', time()),
                'date' => true
            ]);

        $customerIds = $this->customerCollectionFactory->create()
            ->addAttributeToSelect('entity_id')
            ->addAttributeToFilter(
                'updated_at',
                [
                'from' => $this->dateTime->date('Y-m-d H:i:s ', strtotime('-' . $time . ' minutes')),
                'to' => $this->dateTime->date('Y-m-d H:i:s ', time()),
                'date' => true
                ]
            )->getAllIds();
        $allIds = array_merge($orderIds->getColumnValues('customer_id'), $customerIds);

        $cronCustomersData = $this->customerCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', ['in' => $allIds]);

        $cronOrdersData = $this->orderCollectionFactory->create()->addAttributeToSelect('*')
            ->addFieldToFilter('customer_id', ['in' => $allIds]);

        $from = date('Y-m-d H:i:s ', 0);
        $to = $this->dateTime->date('Y-m-d H:i:s ', strtotime('-' . $this->getAbandonedCartTime() . ' minutes'));
        $cronCartsData = $this->quoteCollectionFactory->create()
            ->addFieldToFilter('is_active', ['eq' => 1])
            ->addFieldToFilter('items_count', ['gt' => 0])
            ->addFieldToFilter('customer_id', ['in' => $allIds])
            ->addFieldToFilter('updated_at', ['from' => $from, 'to' => $to, 'date' => true]);

        $ordersList = [];
        $lastCompletedOrder = [];
        foreach ($cronOrdersData as $order) {
            $ordersList[$order->getCustomerId()][$order->getId()] = $order;
            if ($this->feedback) {
                if ($order->getData('status') == 'complete') {
                    if (isset($lastCompletedOrder[$order->getCustomerId()]) &&
                        $lastCompletedOrder[$order->getCustomerId()]->getData('updated_at') <
                        $order->getData('updated_at')) {
                        $lastCompletedOrder[$order->getCustomerId()] = $order;
                    } elseif (!isset($lastCompletedOrder[$order->getCustomerId()])) {
                        $lastCompletedOrder[$order->getCustomerId()] = $order;
                    }
                }
            }
        }

        $cartList = [];
        foreach ($cronCartsData as $cart) {
            $cartList[$cart->getCustomerId()][$cart->getId()] = $cart;
        }

        foreach ($cronCustomersData as $customer) {
            $arr = [];
            if (isset($ordersList[$customer->getId()])) {
                $orders = $ordersList[$customer->getId()];
            } else {
                $orders = null;
            }

            if (isset($lastCompletedOrder[$customer->getId()])) {
                $completedOrder = $lastCompletedOrder[$customer->getId()];
            } else {
                $completedOrder = null;
            }

            if (isset($cartList[$customer->getId()])) {
                $carts = $cartList[$customer->getId()];
            } else {
                $carts = null;
            }

            $arr = $this->getContactDetails(
                $customer,
                $orders,
                $completedOrder,
                $carts,
                $arr,
                $this::EXPORT_CUSTOMER_BY_CRON
            );
            $requestParam[] = $arr;
        }
        if(count($requestParam)){
            $this->connectionManager->createRequest($method, $endpoint, $requestParam);
        }
    }
    /**
     * @param $customer
     * @param $orders
     * @param $completedOrder
     * @param $carts
     * @param $arr
     * @param $exportType
     * @param bool $newSubs
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getContactDetails($customer, $orders, $completedOrder, $carts, $arr, $exportType, $newSubs = false)
    {
        $arr['email'] = $customer->getEmail();
        if ($exportType == $this::EXPORT_CUSTOMER_ACCOUNT) {
                $arr = $this->customerAccount->customerAccountDetailsToExport($customer, $arr, $newSubs);
        } elseif ($exportType == $this::EXPORT_CUSTOMER_ORDER) {
            $arr = $this->orderDetails->orderDetailsToExport($customer, $orders, $completedOrder, $arr);
        } elseif ($exportType == $this::EXPORT_CUSTOMER_BY_CRON) {
                $arr = $this->customerAccount->customerAccountDetailsToExport($customer, $arr);
            $arr = $this->orderDetails->orderDetailsToExport($customer, $orders, $completedOrder, $arr);
        } elseif ($exportType == $this::EXPORT_DEFAULT) {
                $arr = $this->customerAccount->customerAccountDetailsToExport($customer, $arr);
            $arr = $this->orderDetails->orderDetailsToExport($customer, $orders, $completedOrder, $arr);
        }
        $arr = $this->abandonedCart->getAbandonedCartDetails($carts, $arr);
        return $arr;
    }

    /**
     * @param $customerId
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function exportCustomerEmptyCart($customerId)
    {
        $endpoint = '/api/contacts/new';
        $method = 'POST';
        $customer = $this->customerCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', ['in' => $customerId])->getFirstItem();
        $arr = [];
        $arr['email'] = $customer->getEmail();
        $arr['ced_abncart_stat'] = 'no';
        $arr['ced_abncart_prod_html'] = " ";
        $arr['ced_abncart_total'] = 0;
        $contactResponse = $this->connectionManager->createRequest($method, $endpoint, $arr);
    }

    public function getAbandonedCartTime()
    {
        return $this->scopeConfig->getValue('mautic_integration/mautic_abandoned_cart/abandoned_cart_time');
    }
}
