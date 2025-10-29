<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Calculation
 */
class Calculation implements ArrayInterface
{
    const COLISSIMO_CALCULATION_PRODUCT = 'per_product';

    const COLISSIMO_CALCULATION_CART = 'per_cart';

    /**
     * Get options as array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];

        foreach ($this->toArray() as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        return $options;
    }

    /**
     * Get options as array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::COLISSIMO_CALCULATION_PRODUCT => __('The heaviest product of the cart (allow multiple packages)'),
            self::COLISSIMO_CALCULATION_CART    => __('Total weight of the cart (one package per order)'),
        ];
    }
}
