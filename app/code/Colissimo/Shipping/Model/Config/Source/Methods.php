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

use Colissimo\Shipping\Model\Carrier\Colissimo;
use Magento\Framework\Option\ArrayInterface;

/**
 * Class Method
 */
class Methods implements ArrayInterface
{

    /**
     * @var Colissimo $carrier
     */
    protected $carrier;

    /**
     * @param Colissimo $carrier
     */
    public function __construct(Colissimo $carrier)
    {
        $this->carrier = $carrier;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @param bool $isActiveOnlyFlag
     * @return array
     */
    public function toOptionArray($isActiveOnlyFlag = false)
    {
        $options = $this->carrier->getAllowedMethods();

        $values = [];

        foreach ($options as $value => $label) {
            $values[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        return $values;
    }
}
