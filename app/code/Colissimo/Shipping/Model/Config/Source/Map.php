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
 * Class Map
 */
class Map implements ArrayInterface
{
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
            'gmaps' => __('Google Maps'),
            'osm'   => __('OpenStreetMap (free)'),
        ];
    }
}
