<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Plugin\RequestQuote\Model\Quote\Item;

use Amasty\RequestQuote\Model\HidePrice\Provider as HidePriceProvider;
use Amasty\RequestQuote\Model\Quote\Item\Processor;
use Magento\Catalog\Model\Product;
use Magento\Quote\Model\Quote\Item;

class ProcessorPlugin
{
    /**
     * @var HidePriceProvider
     */
    private $hidePriceProvider;

    public function __construct(HidePriceProvider $hidePriceProvider)
    {
        $this->hidePriceProvider = $hidePriceProvider;
    }

    /**
     * @param Processor $subject
     * @param Item $item
     * @param Product $product
     * @return Item
     */
    public function afterInit(Processor $subject, Item $item, Product $product): Item
    {
        if ($this->hidePriceProvider->isHidePrice($product)) {
            $item->setCustomPrice(0)->setOriginalCustomPrice(0);
        }

        return $item;
    }
}
