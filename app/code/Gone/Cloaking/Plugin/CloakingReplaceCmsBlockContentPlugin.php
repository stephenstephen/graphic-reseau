<?php
/*
 *
 * @copyright Copyright © 2020 410-Gone. All rights reserved.
 * @author    contact@410-gone.fr
 *
 */

namespace Gone\Cloaking\Plugin;

use Gone\Cloaking\Ui\Component\Listing\Column\Cms\Block\CloakingOptions as BlockCloakingOption;

class CloakingReplaceCmsBlockContentPlugin
{

    protected $_cloakingReplaceHelper;
    protected $_request;

    public function __construct(
        \Gone\Cloaking\Helper\Replace $cloakingReplaceHelper,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->_request = $request;
        $this->_cloakingReplaceHelper = $cloakingReplaceHelper;
    }

    /**
     * \Magento\Framework\App\Http\Context::getVaryString is used by Magento to retrieve unique identifier for selected context,
     * so this is a best place to declare custom context variables
     */
    function afterGetContent(\Magento\Cms\Model\Block $subject, $result)
    {

        if ($this->_cloakingReplaceHelper->isCloakingEnable() == false) {
            return $result;
        }

        if ($subject->getCloakingMode() == BlockCloakingOption::CLOAK_LINK_BY_CLASS) {
            return $this->_cloakingReplaceHelper->getCloakingFromContent($result, $subject->getCloakingMode());
        } elseif ($subject->getCloakingMode() == BlockCloakingOption::CLOAK_LINK_BY_CLASS_NOT_HOME
            && !$this->isHomePage()) {
            return $this->_cloakingReplaceHelper->getCloakingFromContent($result, $subject->getCloakingMode());
        } else {
            return $result;
        }
    }

    protected function isHomePage()
    {
        $return = false;
        if ($this->_request->getFullActionName() == 'cms_index_index') {
            $return = true;
        }
        return $return;
    }
}
