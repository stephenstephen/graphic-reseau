<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Checkout\Block;

use Magento\Catalog\Api\Data\ProductCustomOptionValuesInterface;
use Magento\Catalog\Block\Product\View\Options\Type\Select\Checkable;
use Magento\Catalog\Helper\Data;
use Magento\Catalog\Model\Product\Option;
use Magento\Catalog\Pricing\Price\CalculateCustomOptionCatalogRule;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Pricing\Adjustment\CalculatorInterface;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Quote\Model\Quote\Item\AbstractItem;

class ProductOptions extends Checkable
{

    protected Option $_productOption;
    public array $selectedOptions;
    public AbstractItem $orderItem;

    public function __construct(
        Context $context,
        PriceHelper $pricingHelper,
        Data $catalogData,
        Option $productOption,
        array $data = [],
        CalculateCustomOptionCatalogRule $calculateCustomOptionCatalogRule = null,
        CalculatorInterface $calculator = null,
        PriceCurrencyInterface $priceCurrency = null
    ) {
        parent::__construct(
            $context,
            $pricingHelper,
            $catalogData,
            $data,
            $calculateCustomOptionCatalogRule,
            $calculator,
            $priceCurrency
        );
        $this->_productOption = $productOption;
    }

    public function setSelectedOptions(array $options) : void
    {
        $this->selectedOptions = $options;
    }
    public function setOrderItem($orderItem) : void
    {
        $this->orderItem = $orderItem;
    }

    /**
     * @return false|AbstractCollection
     */
    public function getProductOptions()
    {
        if (!empty($this->_product)) {
            return $this->_productOption->getProductOptionCollection($this->_product);
        }

        return false;
    }

    public function getSelectedOptions() : array
    {
        if (!empty($this->selectedOptions)) {
            $returnArray = [];
            foreach ($this->selectedOptions as $option) {
                if (array_key_exists('option_id', $option)) {
                    $returnArray[$option['option_id']] = $option['value'];
                }
            }
            return $returnArray;
        }

        return [];
    }

    /**
     * Returns formated price
     *
     * @param ProductCustomOptionValuesInterface $value
     * @return string
     */
    public function formatPrice(ProductCustomOptionValuesInterface $value): string
    {
        return parent::_formatPrice(
            [
                'is_percent' => $value->getPriceType() === 'percent',
                'pricing_value' => $value->getDefaultPrice($value->getPriceType() === 'percent') // 410-override getPrice not working
            ]
        );
    }
}
