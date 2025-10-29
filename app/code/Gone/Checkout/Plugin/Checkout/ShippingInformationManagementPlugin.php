<?php

namespace Gone\Checkout\Plugin\Checkout;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Quote\Model\QuoteRepository;

class ShippingInformationManagementPlugin
{

    protected QuoteRepository $quoteRepository;

    public function __construct(
        QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param ShippingInformationManagement $subject
     * @param ShippingInformationInterface $addressInformation
     * @param $cartId
     */
    public function beforeSaveAddressInformation(
        ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $extAttributes = $addressInformation->getShippingAddress()->getExtensionAttributes();
        $customerShipmentComment = $extAttributes->getGrCustomerShipmentComment();
        $quote = $this->quoteRepository->getActive($cartId);
        $quote->setGrCustomerShipmentComment($customerShipmentComment);
    }
}
