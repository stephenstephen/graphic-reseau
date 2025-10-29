<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Shipping\Plugin;

use Gone\Shipping\Helper\Date;
use Gone\Shipping\Helper\ShippingDelaysHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Psr\Log\LoggerInterface;

class AddShippingDelayAndSort
{

    protected CartRepositoryInterface $_quoteRepository;
    protected Date $_dateHelper;
    protected ShippingDelaysHelper $_shippingDelayHelper;
    protected LoggerInterface $logger;

    public function __construct(
        CartRepositoryInterface $quoteRepository,
        ShippingDelaysHelper $shippingDelayHelper,
        Date $dateHelper,
        LoggerInterface $logger
    )
    {
        $this->_quoteRepository = $quoteRepository;
        $this->_dateHelper = $dateHelper;
        $this->_shippingDelayHelper = $shippingDelayHelper;
        $this->logger = $logger;
    }

    public function aroundEstimateByAddressId(
        \Magento\Quote\Model\ShippingMethodManagement $subject,
        callable $proceed,
        $cartId,
        $addressId
    )
    {
        try {
            $quote = $this->_quoteRepository->getActive($cartId);
            $return = $proceed($cartId, $addressId);
            $return = $this->_addShippingDelay($quote, $return);

            //sort methods by extension attr sort_order > owebia > set("sort_order") for each method if needed
            usort($return, function ($a, $b) {
                if ($a->getCarrierCode() == 'owsh1' && $b->getCarrierCode() == 'owsh1') {
                    $aSort = $a->getExtensionAttributes()->getSortOrder() ?? 0;
                    $bSort = $b->getExtensionAttributes()->getSortOrder() ?? 0;
                    return $aSort <=> $bSort;
                }
            });

        } catch (NoSuchEntityException $e) {
            $this->logger->error($e);
        }
        return $return;
    }

    protected function _addShippingDelay($quote, $return)
    {
        $quoteMaxDelay = $this->_dateHelper->getMaxProductDelay($quote);

        if (!empty($return)) {
            /** @var \Magento\Quote\Api\Data\ShippingMethodInterface $shippingMethod */
            foreach ($return as $shippingMethod) {
                $delayString = $this->_shippingDelayHelper->getShippingDelay($shippingMethod, $quoteMaxDelay, 'd/m/Y');
                $shippingMethod->getExtensionAttributes()->setShippingDelay($delayString);
            }
        }

        return $return;
    }

    public function aroundEstimateByExtendedAddress(
        \Magento\Quote\Model\ShippingMethodManagement $subject,
        callable $proceed,
        $cartId,
        AddressInterface $address
    )
    {
        try {
            $quote = $this->_quoteRepository->getActive($cartId);
            $return = $proceed($cartId, $address);
            $return = $this->_addShippingDelay($quote, $return);
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e);
        }
        return $return;
    }
}
