<?php
/*
 *
 * @copyright Copyright © 2020 410-Gone. All rights reserved.
 * @author    contact@410-gone.fr
 *
 */

namespace Gone\Cloaking\Ui\Component\Listing\Column\Cms\Page;

use Magento\Framework\Data\OptionSourceInterface;
use Gone\Cloaking\Ui\Component\Listing\Column\Cms\Block\CloakingOptions as BlockCloakingOption;

class CloakingOptions implements OptionSourceInterface
{

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            ["label" => "Link by class", "value" => BlockCloakingOption::CLOAK_LINK_BY_CLASS],
            ["label" => "None", "value" => BlockCloakingOption::CLOAK_NONE],
        ];

        return $options;
    }
}
