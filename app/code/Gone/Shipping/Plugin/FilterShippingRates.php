<?php

namespace Gone\Shipping\Plugin;

use Closure;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Item;
use Magento\Shipping\Model\Shipping;

/**
 * Class GetAllRates
 */
class FilterShippingRates
{

    public function aroundCollectCarrierRates(
        Shipping $subject,
        Closure $proceed,
        $carrierCode,
        RateRequest $request
    )
    {
        $dangerousItemAuthorizedCarrier = [
            'owsh1'
        ];
        $outsizedItemAuthorizedCarrier = [
            'owsh1'
        ];

        $itemsArr = $request->getAllItems();
        $hasDangerous = false;
        $isOutSized = false;

        /** @var Item $item */
        foreach ($itemsArr as $item) {
            if ($item->getProduct()->getDangerousProduct() == '1') {
                $hasDangerous = true;
            }
            if ($item->getProduct()->getOutsized() == '1') {
                $isOutSized = true;
            }
        }

        if ($hasDangerous && !in_array($carrierCode, $dangerousItemAuthorizedCarrier)) {
            // To disable the shipping method return false
            return false;
        }
        if ($isOutSized && !in_array($carrierCode, $outsizedItemAuthorizedCarrier)) {
            return false;
        }

        return $proceed($carrierCode, $request);
    }
}
