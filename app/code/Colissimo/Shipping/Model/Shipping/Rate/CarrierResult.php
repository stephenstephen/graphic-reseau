<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2020 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Model\Shipping\Rate;

use Magento\Shipping\Model\Rate\CarrierResult as BaseCarrierResult;
use Magento\Quote\Model\Quote\Address\RateResult\Method;

/**
 * Class CarrierResult
 */
class CarrierResult extends BaseCarrierResult
{
    /**
     * Return all quotes in the result
     * {override} Sort order by position instead of sort order by price
     *
     * @return Method[]
     */
    public function getAllRates()
    {
        /** @var Method[] $rates */
        $rates = parent::getAllRates();

        if (!is_array($rates) || !count($rates)) {
            return $rates;
        }

        $tmp = [];
        $result = [];

        /* @var $rate Method */
        foreach ($rates as $key => $rate) {
            $tmp[$key] = $rate->getMethodSortOrder() ?: 0;
        }

        natsort($tmp);

        foreach ($tmp as $key => $price) {
            $result[] = $rates[$key];
        }

        $this->reset();
        $this->_rates = $result;

        return $this->_rates;
    }
}
