<?php
/*
 *
 * @copyright Copyright © 2020 410-Gone. All rights reserved.
 * @author    contact@410-gone.fr
 *
 */

namespace Gone\Cloaking\Plugin;

use Gone\Cloaking\Helper\Replace;
use Gone\Cloaking\Ui\Component\Listing\Column\Cms\Block\CloakingOptions as BlockCloakingOption;
use Magento\Cms\Model\Page;

/**
 * Plugin for Magento\Catalog\Model\Product
 */
class CloakingReplaceCmsPageContentPlugin
{

    protected $_cloakingReplaceHelper;

    public function __construct(
        Replace $cloakingReplaceHelper
    )
    {
        $this->_cloakingReplaceHelper = $cloakingReplaceHelper;
    }

    /**
     * \Magento\Framework\App\Http\Context::getVaryString is used by Magento to retrieve unique identifier for selected context,
     * so this is a best place to declare custom context variables
     */
    public function afterGetContent(Page $subject, $result)
    {

        if ($this->_cloakingReplaceHelper->isCloakingEnable() == false) {
            return $result;
        }

        if ($subject->getCloakingMode() != BlockCloakingOption::CLOAK_NONE) {
            return $this->_cloakingReplaceHelper->getCloakingFromContent($result, $subject->getCloakingMode());
        } else {
            return $result;
        }
    }
}
