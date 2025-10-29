<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Gone\AvisVerifies\Block;

use Gone\AvisVerifies\Helper\Settings;
use Magento\Framework\View\Element\Template;

/**
 * Html pager block
 *
 */
class Widget extends \Magento\Framework\View\Element\Template
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
    public function getWidgetCode()
    {
        return $this->_settings->getWigetCode() ?? false;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function getWidgetImg()
    {
        return $this->_settings->getWidgetImg() ?? false;
    }
}
