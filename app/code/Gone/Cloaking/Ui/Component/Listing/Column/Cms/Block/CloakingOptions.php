<?php
/*
 *
 * @copyright Copyright © 2020 410-Gone. All rights reserved.
 * @author    contact@410-gone.fr
 *
 */
namespace Gone\Cloaking\Ui\Component\Listing\Column\Cms\Block;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Store Options for Cms Pages and Blocks
 */
class CloakingOptions implements OptionSourceInterface
{
    /**
     * All Store Views value
     */
    public const CLOAK_NONE = 0;
    public const CLOAK_LINK_BY_CLASS = 3;
    public const CLOAK_LINK_BY_CLASS_NOT_HOME = 4;

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            ['label' => 'Link by class', 'value' => self::CLOAK_LINK_BY_CLASS],
            ['label' => 'Link by class not home', 'value' => self::CLOAK_LINK_BY_CLASS_NOT_HOME],
            ['label' => 'None', 'value' => self::CLOAK_NONE],
        ];

        return $options;
    }
}
