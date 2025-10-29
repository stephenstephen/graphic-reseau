<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Gone\GoogleTag\Block;

use Gone\GoogleTag\Helper\Settings;
use Magento\Framework\View\Element\Template;

/**
 * Html pager block
 *
 */
class GoogleTag extends \Magento\Framework\View\Element\Template
{

    protected Settings $_settings;

    public function __construct(
        Template\Context $context,
        Settings $settings,
        array $data = []
    )
    {
        parent::__construct(
            $context,
            $data
        );
        $this->_settings = $settings;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function getGoogleTagId()
    {
        return $this->_settings->getGoogleTagId() ?? false;
    }
}
