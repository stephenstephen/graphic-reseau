<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Checkout\Block;

use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Helper\Product\Configuration;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Configuration\Item\ItemResolverInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Checkout\Block\Cart\Item\Renderer;
use Magento\Checkout\Model\Session;
use Magento\Framework\Message\ManagerInterface as ManagerInterfaceAlias;
use Magento\Framework\Module\Manager;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Url\Helper\Data;
use Magento\Framework\View\Element\Message\InterpretationStrategyInterface;
use Magento\Framework\View\Element\Template\Context;

class ProductStockRenderer extends Renderer
{
    protected StockItemRepository $_stockItemRepository;
    protected Product $product;
    protected StockRegistryInterface $stockRegistry;

    public function __construct(
        Context                         $context,
        Configuration                   $productConfig,
        Session                         $checkoutSession,
        ImageBuilder                    $imageBuilder,
        Data                            $urlHelper,
        ManagerInterfaceAlias           $messageManager,
        PriceCurrencyInterface          $priceCurrency,
        Manager                         $moduleManager,
        InterpretationStrategyInterface $messageInterpretationStrategy,
        StockItemRepository             $stockItemRepository,
        StockRegistryInterface          $stockRegistry,
        array                           $data = [],
        ItemResolverInterface           $itemResolver = null
    )
    {
        parent::__construct(
            $context,
            $productConfig,
            $checkoutSession,
            $imageBuilder,
            $urlHelper,
            $messageManager,
            $priceCurrency,
            $moduleManager,
            $messageInterpretationStrategy,
            $data,
            $itemResolver
        );
        $this->_stockItemRepository = $stockItemRepository;
        $this->stockRegistry = $stockRegistry;
    }

    /**
     * @return int
     */
    public function getStockItemQty(): int
    {
        return $this->stockRegistry->getStockStatus($this->product->getId(), $this->product->getStore()->getWebsiteId())->getQty();
    }

    /**
     * @param Product $product
     * @return void
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }
}
