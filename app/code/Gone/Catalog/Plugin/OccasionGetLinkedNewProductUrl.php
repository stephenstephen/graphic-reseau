<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Catalog\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class OccasionGetLinkedNewProductUrl
{

    protected ProductRepositoryInterface $_productRepositoryInterface;
    protected SearchCriteriaBuilder $_searchCriteriaBuilder;

    public function __construct(
        ProductRepositoryInterface $productRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->_productRepositoryInterface = $productRepositoryInterface;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function afterGetProductUrl(
        \Magento\Checkout\Block\Cart\Item\Renderer $subject,
        $result
    )
    {

        //410-Overload-BEG
        $product = $subject->getProduct();
        if ($linkedNewProductSku = $product->getRelatedNewProductSku()) {
            $searchCriteria = $this->_searchCriteriaBuilder
                ->addFilter('sku', $linkedNewProductSku, 'eq');
            $linkedNewProduct = $this->_productRepositoryInterface->getList($searchCriteria->create())->getItems();
            $linkedNewProduct = reset($linkedNewProduct);

            return $linkedNewProduct->getUrlModel()->getUrl($linkedNewProduct);
        }
        return $result;
        //410-Overload-END
    }

    public function afterHasProductUrl(
        \Magento\Checkout\Block\Cart\Item\Renderer $subject,
        $result
    )
    {
        //410-Overload-BEG
        if (false === $result) {
            $product = $subject->getProduct();
            if ($product->getRelatedNewProductSku()) {
                return true;
            }
        }
        return $result;
        //410-Overload-END
    }
}
