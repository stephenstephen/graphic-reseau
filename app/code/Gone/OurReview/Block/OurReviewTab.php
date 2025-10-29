<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\OurReview\Block;

use Gone\OurReview\Helper\AttributeHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Url\EncoderInterface as UrlEncoder;
use Magento\Catalog\Block\Product\Context;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory as EavCollectionFactory;

class OurReviewTab extends View
{
    protected Product $_product;
    protected AttributeHelper $_attributeHelper;
    protected SerializerInterface $_serializer;

    // store data
    protected $_ratingCollection;
    protected $_applicationCollection;
    protected string $_textReview = '';
    protected array $_graphData = [];

    public function __construct(
        Context $context,
        UrlEncoder $urlEncoder,
        EncoderInterface $jsonEncoder,
        StringUtils $string,
        ProductHelper $productHelper,
        ConfigInterface $productTypeConfig,
        FormatInterface $localeFormat,
        Session $customerSession,
        ProductRepositoryInterface $productRepository,
        PriceCurrencyInterface $priceCurrency,
        AttributeHelper $attributeHelper,
        SerializerInterface $serializer,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
        $this->_attributeHelper = $attributeHelper;
        $this->_serializer = $serializer;
    }

    /**
     * @param $product
     */
    public function setProduct($product)
    {
        $this->_product = $product;
    }

    public function formatClass($rate)
    {
        $rate = number_format($rate, 1);

        $number = substr($rate, 0, 1);
        if (explode('.', $rate)[1] == '5') {
            $number .= '-half';
        }

        return 'icon-star-' . $number;
    }

    public function getTextReviewHtml()
    {
        if (empty($this->_textReview)) {
            if ($this->_request->getFullActionName() == 'catalog_product_view') {
                $this->_textReview = (string) $this->getProduct()->getNotreAvis();
            }
        }

        return $this->_textReview;
    }

    public function getProduct()
    {
        if (isset($this->_product)) {
            return $this->_product; // from category page
        }
        return parent::getProduct(); // from product view
    }

    public function getRatingAttributes()
    {
        if (empty($this->_ratingCollection)) {
            $this->_ratingCollection = $this->_attributeHelper->getAttributeFromGroup(
                AttributeHelper::OUR_REVIEW_GROUP_NAME,
                $this->getProduct()
            );
        }

        return $this->_ratingCollection;
    }

    public function isGraphEnable()
    {
        return $this->_request->getFullActionName() == 'catalog_product_view'
            && $this->getApplicationAttributes()->getSize() > 0;
    }

    public function getAdvancedGraphData($product)
    {
        if (empty($this->_graphData)) {
            $attributesCollection = $this->getApplicationAttributes();
            foreach ($attributesCollection as $attribute) {
                $this->_graphData['label'][] = $attribute->getFrontendLabel();
                $this->_graphData['value'][] = $product->getData($attribute->getAttributeCode());
            }
        }

        return $this->_serializer->serialize($this->_graphData);
    }

    protected function getApplicationAttributes()
    {
        if (empty($this->_applicationCollection)) {
            $this->_applicationCollection = $this->_attributeHelper->getAttributeFromGroup(
                AttributeHelper::ADVANCED_STAT_GROUP_NAME,
                $this->getProduct()
            );
        }

        return $this->_applicationCollection;
    }

    protected function _toHtml()
    {
        if ($this->getRatingAttributes()->getSize() > 0
            || !empty($this->getTextReviewHtml())
        ) {
            return parent::_toHtml();
        }

        return '';
    }
}
