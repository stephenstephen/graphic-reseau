<?php

namespace Gone\Shipping\Model;

use Gone\Checkout\Helper\CheckoutHelper;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\View\LayoutInterface;

class ConfigProvider implements ConfigProviderInterface
{

    protected CheckoutHelper $_checkoutHelper;
    protected LayoutInterface $_layout;

    public function __construct(
        LayoutInterface $layout,
        CheckoutHelper $checkoutHelper
    ) {
        $this->_checkoutHelper = $checkoutHelper;
        $this->_layout = $layout;
    }

    public function getConfig()
    {
        return [
            'has_dangerous' => $this->_checkoutHelper->hasDangerousItem(),
            'cms_block_dangerous' => $this->_layout->createBlock('Magento\Cms\Block\Block', 'has_dangerous_product')
                ->toHtml()
        ];
    }
}
