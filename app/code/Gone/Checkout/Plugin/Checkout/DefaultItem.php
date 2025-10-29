<?php

namespace Gone\Checkout\Plugin\Checkout;

use Gone\Catalog\Helper\Product;
use Magento\Quote\Model\Quote\Item;

class DefaultItem
{
    private Product $_productHelper;

    public function __construct(
        Product $productHelper
    )
    {
        $this->_productHelper = $productHelper;
    }

    public function aroundGetItemData($subject, \Closure $proceed, Item $item)
    {
        $data = $proceed($item);
        $product = $item->getProduct();

        $atts = [
            'product_availability' => $this->_productHelper->getAvailablityStatus($product)
        ];

        return array_merge($data, $atts);
    }
}
