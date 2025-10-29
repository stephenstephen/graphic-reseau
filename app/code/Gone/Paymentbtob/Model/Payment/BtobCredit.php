<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Paymentbtob\Model\Payment;

use Magento\Directory\Helper\Data as DirectoryHelper;

class BtobCredit extends \Magento\Payment\Model\Method\AbstractMethod
{

    protected $_code = "btob_credit";
    protected $_isOffline = true;
    protected \Gone\Customer\Helper\Customer $_customerHelper;
    protected \Magento\Sales\Api\OrderRepositoryInterface $_orderRepository;
    protected \Magento\Framework\Api\SearchCriteriaBuilder $_searchCriteriaBuilder;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Magento\Customer\Model\Session $customer,
        \Gone\Customer\Helper\Customer $customerHelper,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Checkout\Model\Session $checkoutSession,
        DirectoryHelper $directory = null,
        array $data = []
    )
    {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data,
            $directory
        );

        $this->_customerHelper = $customerHelper;
        $this->_orderRepository = $orderRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function isAvailable(
        \Magento\Quote\Api\Data\CartInterface $quote = null
    )
    {

        if ($quote == null) {
            return false;
        }

        $customer = $quote->getCustomer();

        //if customer B2B
        if (!$this->_customerHelper->isConnectedCustomerBtoB($customer)) {
            return false;
        }

        //if customer is authorized using credit
        $isCreditAuthAttr = $customer->getCustomAttribute(
            \Gone\Paymentbtob\Setup\Patch\Data\AddBToBCreditAttributes::BTOB_CREDIT_AUTH);
        $isCreditAuth = !empty($isCreditAuthAttr) ? $isCreditAuthAttr->getValue() : null;

        if (empty($isCreditAuth)) {
            return false;
        }

        //get customer credit max authorized
        $customerMaxCreditAttr = $customer->getCustomAttribute(
            \Gone\Paymentbtob\Setup\Patch\Data\AddBToBCreditAttributes::BTOB_CREDIT_MAX);
        $customerMaxCredit = !empty($customerMaxCreditAttr) ? $customerMaxCreditAttr->getValue() : null;

        //get store credit max authorized if customer has not
        if (empty($customerMaxCredit)) {
            $store = $quote->getStore();
            $customerMaxCredit =
                $store->getConfig(\Gone\Paymentbtob\Setup\Patch\Data\AddBToBCreditAttributes::BTOB_CREDIT_MAX)
                ?? null;
        }

        if (empty($customerMaxCredit)) {
            return false;
        }

        //summing total due on orders on going for this account
        $searchCriteria = $this->_searchCriteriaBuilder
            ->addFilter('customer_id', $customer->getId(), 'eq')
            ->addFilter('status', [
                'new',
                'payment_review',
                'pending_payment'
            ], 'in');
        $openOrderArr = $this->_orderRepository->getList($searchCriteria->create())->getItems();
        $totalDue = 0;
        foreach ($openOrderArr as $order) {
            $totalDue += $order->getTotalDue();
        }

        if (($totalDue + $quote->getGrandTotal()) > $customerMaxCredit) {
            return false;
        }

        return parent::isAvailable($quote);
    }
}
