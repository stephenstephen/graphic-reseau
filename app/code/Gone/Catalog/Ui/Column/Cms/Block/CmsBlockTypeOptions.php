<?php
/*
 *
 * @copyright Copyright © 2020 410-Gone. All rights reserved.
 * @author    contact@410-gone.fr
 *
 */

namespace Gone\Catalog\Ui\Column\Cms\Block;

use Magento\Framework\Data\OptionSourceInterface;
use ReflectionClass;

/**
 * Store Options for Cms Pages and Blocks
 */
class CmsBlockTypeOptions implements OptionSourceInterface
{
    /**
     * All CMS Block type
     */
    public const STANDARD = 0;
    public const CUSTOM_PRODUCT_TAB = 1;
    public const HOME_SLIDER = 2;
    public const CHECKOUT_PROMO_AD = 3;
    public const FRONTEND_TERMINAL = 4;
    public const PRODUCT_CATEGORY_DESCRIPTION = 5;

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->getConstants() as $constant => $value) {
            $options [] = [
                'label' => ucfirst(strtolower(str_replace('_', ' ', $constant))),
                'value' => $value
            ];

        }
        return $options;
    }

    /**
     * @return mixed
     */
    private function getConstants()
    {
        $reflectionClass = new ReflectionClass(static::class);
        return $reflectionClass->getConstants();
    }
}
