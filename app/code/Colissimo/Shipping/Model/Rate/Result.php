<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Model\Rate;

use Magento\Shipping\Model\Rate\Result as RateResult;

/**
 * Class Pickup
 */
class Result extends RateResult
{

    /**
     * Avoid to sort rates by price
     *
     * @return $this
     */
    public function sortRatesByPrice()
    {
        return $this;
    }
}
