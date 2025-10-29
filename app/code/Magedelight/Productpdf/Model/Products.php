<?php

namespace Magedelight\Productpdf\Model;

class Products
{
    protected $_imageHelper;
    protected $_wishlistHelper;
    protected $_compareHelper;
    protected $_priceHelper;
    protected $_taxHelper;
    protected $_catalogHelper;
    protected $_printHelper;
    protected $_cache;
    protected $storeManager;
    protected $productRepository;
    protected $scopeConfig;
    protected $reviewFactory;
    protected $optionFactory;
    protected $currencyFactory;
    protected $collectionProvider;
    protected $collectionFilter;
    protected $productCollectionFactory;
    protected $productConfig;
    protected $_logger;
    protected $directory_list;
    protected $configurable;
    public function __construct(
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Wishlist\Helper\Data $wishlistHelper,
        \Magento\Catalog\Helper\Product\Compare $compareHelper,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceHelper,
        \Magento\Catalog\Helper\Data $catalogHelper,
        \Magento\Tax\Helper\Data $taxHelper,
        \Magedelight\Productpdf\Helper\Data $printHelper,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Review\Model\Review\SummaryFactory $summaryFactory,
        \Magento\Catalog\Model\Product\OptionFactory $optionFactory,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Config $productConfig,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Pricing\Helper\Data $_priceHelper,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurable
    ) {
        $this->_imageHelper = $imageHelper;
        $this->_wishlistHelper = $wishlistHelper;
        $this->_compareHelper = $compareHelper;
        $this->_priceHelper = $priceHelper;
        $this->_printHelper = $printHelper;
        $this->_catalogHelper = $catalogHelper;
        $this->_taxHelper = $taxHelper;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->scopeConfig = $scopeConfig;
        $this->reviewFactory = $summaryFactory;
        $this->optionFactory = $optionFactory;
        $this->currencyFactory = $currencyFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productConfig = $productConfig;
        $this->_mdPricehelper = $_priceHelper;
        $this->directory_list = $directory_list;
        $this->configurable = $configurable;
    }

    public function prepareProductdata($productIds = [], $store = null, $categoryId = null)
    {

        $finalStoreIds = (!$store) ? $this->_getStoreIds(): [$store];

        foreach ($finalStoreIds as $storeId) {
            if ($storeId <= 0) {
                    continue;
            }
                $productData = [];
                $excludetags = (string)$this->scopeConfig->getValue('md_productpdf/general/exclude_tag_des');
                $collection = [];
            foreach ($productIds as $productId) {
                $productData = [];
                $product = $this->productRepository->getById($productId);

                $typeId = $product->getTypeId();
                $productData['id'] = $productId;
                $productData['store_id'] = $storeId;
                $productData['name'] = $product->getName();
                $productData['sku'] = $product->getSku();
                $productData['url'] = $product->getProductUrl();
                $size = (265 * 265) / (265 * 72 / 96) + 10;
                $productData['base_image'] = $this->directory_list->getPath('media') . "/catalog/product/".$product->getImage();
                ;
                $productData['stock_status'] = (int)$product->getIsSalable();
                //$productData['wishlist_url'] = (string)$this->storeManager->getStore()->getUrl('wishlist/index/index/add', ['product'=>$productId]);
                //$productData['compare_url'] = (string)$this->storeManager->getStore()->getUrl('catalog/product_compare/add', ['product'=>$productId]);
                $productData['short_description'] = (string)strip_tags($product->getShortDescription(), $excludetags);
                $productData['description'] = (string)strip_tags($product->getDescription(), $excludetags);
                $this->_getProductReview($product, $productData);
                $this->_getPriceBlock($product, $productData);
                $this->_getMediaImages($product, $productData);
                $this->_getAdditionalData($product, $productData);
                $this->_getUpsellProducts($product, $productData);
                $this->_getRelatedProducts($product, $productData);
                $this->_getProductCustomOptions($product, $productData);
                $this->_getTierPrices($product, $productData);
                if ($typeId == \Magento\GroupedProduct\Model\Product\Type\Grouped::TYPE_CODE) {
                        $this->_prepareGroupedOptions($product, $productData);
                } elseif ($typeId == \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE) {
                    $this->_prepareBundledOptions($product, $productData);
                } elseif ($typeId == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
                    $this->_prepareConfigurableOptions($product, $productData);
                }
                    $collection[$productId] = $productData;
            }
        }
        return $collection;
    }

    protected function _getPriceBlock($product, &$data)
    {
            $_taxHelper = $this->_taxHelper;
            //$_coreHelper = $this->_coreHelper;
            $currentCurrency = $this->storeManager->getStore()->getCurrentCurrency();
            $data['price'] = [];
        if ($product->getTypeId() == \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE || $product->getTypeId() == \Magento\GroupedProduct\Model\Product\Type\Grouped::TYPE_CODE) {
            $product = $this->productCollectionFactory->create()->setStore($product->getStore())
            ->addIdFilter([$product->getId()])
            ->addAttributeToSelect($this->productConfig->getProductAttributes())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->load()
            ->getFirstItem();
        }
            $_simplePricesTax = ($_taxHelper->displayPriceIncludingTax() || $_taxHelper->displayBothPrices());

            $_minimalPriceValue = $product->getMinimalPrice();

            //$_price = $this->_catalogHelper->getTaxPrice($product, $product->getPrice());
            $_price = $this->_catalogHelper->getTaxPrice($product, $product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue());

            //$_regularPrice = $this->_catalogHelper->getTaxPrice($product, $product->getPrice(), $_simplePricesTax);
            $_regularPrice = $this->_catalogHelper->getTaxPrice($product, $product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue(), $_simplePricesTax);

            //410-UPDATE-FIX-TVA/NO TVA INVVERSION
            //$_finalPrice = $this->_catalogHelper->getTaxPrice($product, $product->getFinalPrice());
            $_finalPrice = $product->getFinalPrice();

        //$_finalPriceInclTax = $this->_catalogHelper->getTaxPrice($product, $product->getFinalPrice(), true);
            $_finalPriceInclTax = $this->_catalogHelper->getTaxPrice($product, $product->getPriceInfo()->getPrice('final_price')->getAmount()->getValue());
            //410-UPDATE-FIX-TVA/NO TVA INVVERSION

        if ($product->getTypeId() == \Magento\Catalog\Model\Product\Type::DEFAULT_TYPE || $product->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE || $product->getTypeId() == \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL || $product->getTypeId() == \Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE) {
            if ($_taxHelper->displayBothPrices()) {
                if ($_finalPrice >= $_price) {
                    $data['price']['excl_tax'] = $currentCurrency->format($_price, false);
                    $data['price']['incl_tax'] = $currentCurrency->format($_finalPriceInclTax, false);
                } else {
                    $data['price']['regular'] = $currentCurrency->format($_regularPrice, false);
                    $data['price']['special']['excl_tax'] = $currentCurrency->format($_finalPrice, false);
                    $data['price']['special']['incl_tax'] = $currentCurrency->format($_finalPriceInclTax, false);
                }
            } elseif ($_taxHelper->displayPriceIncludingTax()) {
                if ($_finalPrice >= $_price) {
                    $data['price'] = $currentCurrency->format($_finalPriceInclTax, false);
                } else {
                    $data['price']['regular'] = $currentCurrency->format($_regularPrice, false);
                    $data['price']['special'] = $currentCurrency->format($_finalPriceInclTax, false);
                }
            } else {
                if ($_finalPrice >= $_price) {
                    $data['price'] = $currentCurrency->format($_price, false);
                } else {
                    $data['price']['regular'] = $currentCurrency->format($_regularPrice, false);
                    $data['price']['special'] = $currentCurrency->format($_finalPrice, false);
                }
            }
        } elseif ($product->getTypeId() == \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE) {
            $_priceModel  = $product->getPriceModel();

            list($_minimalPriceTax, $_maximalPriceTax) = $_priceModel->getTotalPrices($product);
            list($_minimalPriceInclTax, $_maximalPriceInclTax) = $_priceModel->getTotalPrices($product, null, true);
            if ($_taxHelper->displayBothPrices()) {
                $data['price']['from']['excl_tax'] = $currentCurrency->format($_minimalPriceTax, false);
                $data['price']['from']['incl_tax'] = $currentCurrency->format($_minimalPriceInclTax, false);
                $data['price']['to']['excl_tax'] = $currentCurrency->format($_maximalPriceTax, false);
                $data['price']['to']['incl_tax'] = $currentCurrency->format($_maximalPriceInclTax, false);
            } elseif ($_taxHelper->displayPriceIncludingTax()) {
                $data['price']['from'] = $currentCurrency->format($_minimalPriceInclTax, false);
                $data['price']['to'] = $currentCurrency->format($_maximalPriceInclTax, false);
            } else {
                $data['price']['from'] = $currentCurrency->format($_minimalPriceTax, false);
                $data['price']['to'] = $currentCurrency->format($_maximalPriceTax, false);
            }
        } elseif ($product->getTypeId() == 'grouped') {
            $_exclTax = $this->_catalogHelper->getTaxPrice($product, $_minimalPriceValue);
            $_inclTax = $this->_catalogHelper->getTaxPrice($product, $_minimalPriceValue, true);
            if ($_taxHelper->displayBothPrices()) {
                $data['price']['starting']['excl_tax'] = $currentCurrency->format($_exclTax, false);
                $data['price']['starting']['incl_tax'] = $currentCurrency->format($_inclTax, false);
            } elseif ($_taxHelper->displayPriceIncludingTax()) {
                $data['price']['starting'] = $currentCurrency->format($_inclTax, false);
            } else {
                $data['price']['starting'] = $currentCurrency->format($_exclTax, false);
            }
        }
    }

    protected function _getStoreIds()
    {
        $storeIds = $this->storeManager->getStores();
        return array_keys($storeIds);
    }

    protected function _getProductReview($product, &$data)
    {
        $summaryObject = $this->reviewFactory->create();
        $summaryData = $summaryObject->load($product->getId());
        $data['review'] = $summaryData->getData();
        //$data['review']['review_url'] = Mage::getUrl('review/product/list', array('id'=>$product->getId(),'category'=> $product->getCategoryId()));
        $data['review']['review_url'] = $this->storeManager->getStore()->getUrl('review/product/list', ['id'=>$product->getId(),'category'=> $product->getCategoryId()]);
    }

    protected function _getMediaImages($product, &$data)
    {
        $counter = 1;
        $targetCount = (int)$this->scopeConfig->getValue('md_productpdf/general/count_additional_images');
        if ($product->getMediaGalleryImages()) {
            if (is_array($product->getMediaGalleryImages()->getItems()) && count($product->getMediaGalleryImages()->getItems()) > 0) {
                foreach ($product->getMediaGalleryImages()->getItems() as $mediaImage) {
                    if (($counter > $targetCount) && $targetCount > 0) {
                        break;
                    }
                    $data['media_gallery'][] = $this->directory_list->getPath('media') . "/catalog/product/".$mediaImage->getFile();
                    $counter++;
                }
            }
        }
    }

    protected function _getAdditionalData($product, &$data)
    {
        $attributes = $product->getAttributes();
            $excludeAttr = [];
        foreach ($attributes as $attribute) {
            if ($attribute->getIsVisibleOnFront() && !in_array($attribute->getAttributeCode(), $excludeAttr)) {
                $value = $attribute->getFrontend()->getValue($product);
                if (!$product->hasData($attribute->getAttributeCode())) {
                    $value = __('N/A');
                } elseif ((string)$value == '') {
                    $value = __('No');
                } elseif ($attribute->getFrontendInput() == 'price' && is_string($value)) {
                    $value = $this->_mdPricehelper->currency($value, true, false);
                }
                if (is_string($value) && strlen($value)) {
                        $data['additional_data'][$attribute->getAttributeCode()] = [
                            'label' => $attribute->getStoreLabel(),
                            'value' => $value,
                            'code'  => $attribute->getAttributeCode()
                        ];
                }
            }
        }
    }

    protected function _getUpsellProducts($product, &$data)
    {
        $currentStore = $this->storeManager->getStore();
        $collection = $product->getUpSellProductCollection()->addAttributeToSelect('*')
            ->setPositionOrder()
            ->addStoreFilter();
            $collection->load();
        foreach ($collection as $upsellProduct) {
            if ($upsellProduct->getAttributeText('visibility')->getText() != 'Not Visible Individually') {
                $data['upsell_products'][$upsellProduct->getId()] = [
                    'name'=> $upsellProduct->getName(),
                    'url' => $upsellProduct->getProductUrl(),
                    //'price'=> $this->_coreHelper->formatPrice($upsellProduct->getFinalPrice(),false),
                    'image_path' => (string) $this->_imageHelper->init($upsellProduct, 'product_page_image_small')->setImageFile($upsellProduct->getImage())->getUrl(),
                    //'wishlist_url' => (string)$this->_wishlistHelper->getAddParams($upsellProduct),
                    //'compare_url' => (string)$this->_compareHelper->getPostDataParams($upsellProduct)
                ];
                $this->_getPriceBlock($upsellProduct, $data['upsell_products'][$upsellProduct->getId()]);
                $this->_getProductReview($upsellProduct, $data['upsell_products'][$upsellProduct->getId()], $product->getCategoryId());
            }
        }
    }
    protected function _getRelatedProducts($product, &$data)
    {
        $currentStore = $this->storeManager->getStore();
        $collection = $product->getRelatedProductCollection()->addAttributeToSelect('*')
            ->setPositionOrder()
            ->addStoreFilter();
            $collection->load();
        foreach ($collection as $relatedProduct) {
            if ($relatedProduct->getAttributeText('visibility')->getText() != 'Not Visible Individually') {
                $data['related_products'][$relatedProduct->getId()] = [
                'name'=> $relatedProduct->getName(),
                'url' => $relatedProduct->getProductUrl(),
                //'price'=> $this->_coreHelper->formatPrice($relatedProduct->getFinalPrice(),false),
                'image_path' => (string) $this->_imageHelper->init($relatedProduct, 'product_page_image_small')->setImageFile($relatedProduct->getImage())->getUrl(),
                //'wishlist_url' => (string)$this->_wishlistHelper->getAddParams($relatedProduct),
                //'compare_url' => (string)$this->_compareHelper->getPostDataParams($relatedProduct)
                ];
                $this->_getPriceBlock($relatedProduct, $data['related_products'][$relatedProduct->getId()]);
                $this->_getProductReview($relatedProduct, $data['related_products'][$relatedProduct->getId()], $product->getCategoryId());
            }


        }
    }

    protected function _getProductCustomOptions($product, &$data)
    {
        $optionCollection = $this->optionFactory->create()->getProductOptionCollection($product);
        $currentCurrency = $this->storeManager->getStore()->getCurrentCurrency();
            $finalPrice = $product->getFinalPrice();
        if ($optionCollection->count() > 0) {
            foreach ($optionCollection as $option) {
                $data['custom_options'][$option->getId()] = $option->getData();
                $defaultPrice = $data['custom_options'][$option->getId()]['default_price'];
                $storePrice = $data['custom_options'][$option->getId()]['store_price'];
                $price = $data['custom_options'][$option->getId()]['price'];
                if ($data['custom_options'][$option->getId()]['price_type'] == 'percent' && strlen($price) > 0) {
                    $percentage = $finalPrice * ($price / 100);
                    $data['custom_options'][$option->getId()]['price'] = $currentCurrency->format($price, false);
                }
                if ($data['custom_options'][$option->getId()]['store_price_type'] == 'percent' && strlen($storePrice) > 0) {
                    $percentage = $finalPrice * ($storePrice / 100);
                    $data['custom_options'][$option->getId()]['store_price'] = $currentCurrency->format($percentage, false);
                }
                if ($data['custom_options'][$option->getId()]['default_price_type'] == 'percent' && strlen($defaultPrice) > 0) {
                    $percentage = $finalPrice * ($defaultPrice / 100);
                    $data['custom_options'][$option->getId()]['default_price'] = $currentCurrency->format($percentage, false);
                }
                if ($data['custom_options'][$option->getId()]['price_type'] == 'fixed' && strlen($price) > 0) {
                    $data['custom_options'][$option->getId()]['price'] = $currentCurrency->format($price, false);
                }
                if ($data['custom_options'][$option->getId()]['store_price_type'] == 'fixed' && strlen($storePrice) > 0) {
                    $data['custom_options'][$option->getId()]['store_price'] = $currentCurrency->format($storePrice, false);
                }
                if ($data['custom_options'][$option->getId()]['default_price_type'] == 'fixed' && strlen($defaultPrice) > 0) {
                    $data['custom_options'][$option->getId()]['default_price'] = $currentCurrency->format($storePrice, false);
                }
                if ($option->getValuesCollection()->count() > 0) {
                    $data['custom_options'][$option->getId()]['option_values'] = [];
                    foreach ($option->getValuesCollection() as $value) {
                        $data['custom_options'][$option->getId()]['option_values'][$value->getId()] = $value->getData();
                        $defaultPrice = $data['custom_options'][$option->getId()]['option_values'][$value->getId()]['default_price'];
                        $storePrice = $data['custom_options'][$option->getId()]['option_values'][$value->getId()]['store_price'];
                        $price = $data['custom_options'][$option->getId()]['option_values'][$value->getId()]['price'];
                        if ($data['custom_options'][$option->getId()]['option_values'][$value->getId()]['price_type'] == 'percent' && strlen($defaultPrice) > 0) {
                            $percentage = $finalPrice * ($price / 100);
                            $data['custom_options'][$option->getId()]['option_values'][$value->getId()]['price'] = $currentCurrency->format($percentage, false);
                        }
                        if ($data['custom_options'][$option->getId()]['option_values'][$value->getId()]['store_price_type'] == 'percent' && strlen($defaultPrice) > 0) {
                            $percentage = $finalPrice * ($storePrice / 100);
                            $data['custom_options'][$option->getId()]['option_values'][$value->getId()]['store_price'] = $currentCurrency->format($percentage, false);
                        }
                        if ($data['custom_options'][$option->getId()]['option_values'][$value->getId()]['default_price_type'] == 'percent' && strlen($defaultPrice) > 0) {
                            $percentage = $finalPrice * ($defaultPrice / 100);
                            $data['custom_options'][$option->getId()]['option_values'][$value->getId()]['default_price'] = $currentCurrency->format($percentage, false);
                        }
                        if ($data['custom_options'][$option->getId()]['option_values'][$value->getId()]['price_type'] == 'fixed' && strlen($price) > 0) {
                            $data['custom_options'][$option->getId()]['option_values'][$value->getId()]['price'] = $currentCurrency->format($price, false);
                        }
                        if ($data['custom_options'][$option->getId()]['option_values'][$value->getId()]['store_price_type'] == 'fixed' && strlen($storePrice) > 0) {
                            $data['custom_options'][$option->getId()]['option_values'][$value->getId()]['store_price'] = $currentCurrency->format($storePrice, false);
                        }
                        if ($data['custom_options'][$option->getId()]['option_values'][$value->getId()]['default_price_type'] == 'fixed' && strlen($defaultPrice) > 0) {
                            $data['custom_options'][$option->getId()]['option_values'][$value->getId()]['default_price'] = $currentCurrency->format($defaultPrice, false);
                        }
                    }
                }
            }
        }
    }
    protected function _getTierPrices($product, &$data)
    {
        $tierPrices = $product->getTierPrice();
        $currentCurrency = $this->storeManager->getStore()->getCurrentCurrency();
            $finalPrice = $product->getFinalPrice();
        if (count($tierPrices)) {
            $data['tier_prices'] = [];
            foreach ($tierPrices as $tier) {
                if ($product->getTypeId() != 'bundle') {
                    $savePercentage = ceil(100 - ((100 / $finalPrice) * $tier['price']));
                    $data['tier_prices'][] = __('Buy '.(int)$tier['price_qty'].' for '.$currentCurrency->format($tier['price'], false).' each and save '.$savePercentage.'%');
                } else {
                    $data['tier_prices'][] = __('Buy %1$s with %2$s discount each', (int)$tier['price_qty'], ($tier['price']*1).'%');
                }
            }
        }
    }

    protected function _prepareGroupedOptions($product, &$data)
    {
        $groupedCollection = $product->getTypeInstance(true)->getAssociatedProducts($product);
        $currentStore = $this->storeManager->getStore();
        $data['grouped_products'] = [];
        foreach ($groupedCollection as $grouped) {
            $data['grouped_products'][$grouped->getId()] = [
                'name'=> $grouped->getName(),
                'url' => $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB).$grouped->getUrlPath(),
                'stock_status'=> (int)$grouped->getIsSalable(),
                //'price'=>$this->_coreHelper->formatPrice($grouped->getFinalPrice(),false),
                'image'=> (string) /*$this->_imageHelper->init($grouped,'image')->resize(105,80)*/ $this->_imageHelper->init($grouped, 'product_page_image_small')->setImageFile($grouped->getImage())->getUrl()
            ];
            //$this->_getPriceBlock($grouped,$data['grouped_products'][$grouped->getId()]);
        }
    }

    protected function _prepareBundledOptions($product, &$data)
    {
        $optionsCollection = $product->getTypeInstance(true)->getOptionsCollection($product);
            $optionIds = $product->getTypeInstance(true)->getOptionsIds($product);
            $selectionCollection = $product->getTypeInstance(true)
            ->getSelectionsCollection(
                $optionIds,
                $product
            );
            $options = $optionsCollection->appendSelections($selectionCollection, false, true);
            $data['bundle_products'] = [];
            $currentCurrency = $this->storeManager->getStore()->getCurrentCurrency();
        foreach ($options as $option) {
            $data['bundle_products'][$option->getId()]['default_title'] = $option->getDefaultTitle();
            $data['bundle_products'][$option->getId()]['type'] = $option->getType();

            if (!empty($option->getSelections())) {
                foreach ($option->getSelections() as $selection) {
                    $bundlePrice = $selection->getFinalPrice();
                    $data['bundle_products'][$option->getId()]['selections'][$selection->getId()]['title'] = $selection->getName();
                    $data['bundle_products'][$option->getId()]['selections'][$selection->getId()]['price'] = (!$bundlePrice || $bundlePrice <= 0) ? '':$currentCurrency->format($bundlePrice, false);
                }
            }
        }
    }
    protected function _prepareConfigurableOptions($product, &$data)
    {
            $configurableCollection = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
        if (count($configurableCollection) > 0) {
            $data['configurable_products'] = $configurableCollection;
        }
    }
}
