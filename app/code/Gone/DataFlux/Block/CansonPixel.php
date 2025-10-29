<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\DataFlux\Block;

use Gone\DataFlux\Cron\CansonFlux as CansonFluxCron;
use Magento\Catalog\Model\Product;
use Magento\Checkout\Block\Onepage\Success as OrderSuccess;
use Magento\Checkout\Model\Session;
use Magento\Eav\Model\Config;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\Order\Config as OrderConfig;

class CansonPixel extends OrderSuccess
{
    protected const MARCHANT_ID_CONF = 'dataflux/commerce_connector/merchant_id';

    protected Config $_eavConfig;

    public function __construct(
        Context     $context,
        Session     $checkoutSession,
        OrderConfig $orderConfig,
        HttpContext $httpContext,
        Config      $eavConfig,
        array       $data = []
    )
    {
        parent::__construct(
            $context,
            $checkoutSession,
            $orderConfig,
            $httpContext,
            $data
        );
        $this->_eavConfig = $eavConfig;
    }

    /**+
     * @return false|string
     */
    public function genPixelData()
    {
        if (!empty($merchantId = $this->getPixelMerchantId())) {
            $order = $this->_checkoutSession->getLastRealOrder();
            $orderItems = $order->getAllVisibleItems();

            $jsInjectionStart = "CCConversionTag.init('$merchantId', 'merchant');\n";
            $jsInjectionData = null;

            $brandId = $this->_eavConfig->getAttribute(Product::ENTITY, CansonFluxCron::BRAND_ATTRIBUTE)
                ->setStoreId(0)
                ->getSource()
                ->getOptionId(CansonFluxCron::BRAND);

            /**
             * @var \Magento\Sales\Api\Data\OrderItemInterface[] $orderItems
             */
            foreach ($orderItems as $item) {

                if ($item->getProduct()->getBrands() === $brandId) {
                    $jsInjectionData .= 'CCConversionTag.addPurchase('
                        . $item->getProductId() . ','
                        . (int)$item->getQtyOrdered() . ','
                        . $item->getPrice()
                        . ');' . "\n";
                }
            }
            return !empty($jsInjectionData) ? $jsInjectionStart . $jsInjectionData : false;
        }

        return false;
    }

    /**
     * @param string
     */
    public function getPixelMerchantId()
    {
        return $this->_scopeConfig->getValue(self::MARCHANT_ID_CONF) ?? false;
    }
}
