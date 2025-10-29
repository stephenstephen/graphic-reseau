<?php

namespace Gone\Catalog\Block\Product\View;

use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Model\Product;

class SquareMeterPrice extends View
{

    private Product $_product;

    /**
     * @param $product
     * @return Product
     */
    public function setProduct($product)
    {
        $this->_product = $product;
    }

    /**
     * @return false|string
     */
    public function getPricePerSqm()
    {
        $product = $this->_product ?? $this->getProduct();
        $priceSqm = $product->getPaperPricePerSqm();

        return !empty($priceSqm) ? $this->priceCurrency->convertAndFormat($priceSqm) : false;
    }
}
