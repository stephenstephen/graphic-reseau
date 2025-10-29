<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_MauticIntegration
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license     http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\MauticIntegration\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

class AbandonedCart extends AbstractHelper
{
    public $properties;
    public $productObject;
    public $connectionManager;
    public $exportPropertiesAndSegments;
    public $storeManager;
    public $currencyFactory;
    public $productFactory;

    public function __construct(
        Context $context,
        ExportPropertiesAndSegments $exportPropertiesAndSegments,
        Properties $properties,
        ConnectionManager $connectionManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory
    ) {
        parent::__construct($context);
        $this->exportPropertiesAndSegments = $exportPropertiesAndSegments;
        $this->properties = $properties;
        $this->connectionManager = $connectionManager;
        $this->storeManager = $storeManager;
        $this->currencyFactory = $currencyFactory;
        $this->productFactory = $productFactory;
    }

    /**
     * @param $carts
     * @param $arr
     * @return mixed
     */
    public function getAbandonedCartDetails($carts, $arr)
    {
        if ($carts == null || !$this->connectionManager->isCustomerGroupEnabled('abandoned_cart')) {
            $arr['ced_abncart_stat'] = 'no';
            $arr['ced_abncart_prod_html'] = " ";
            $arr['ced_abncart_total'] = 0;
            return $arr;
        }

        $properties = $this->properties->allProperties('abandoned_cart');
        $key = 0;
        $cartProductsCount = 0;
        $cartTotal = 0;

        foreach ($carts as $cart) {
            foreach ($cart->getAllVisibleItems() as $cartItem) {
                $cart_products[$key]["name"] = $cartItem->getProduct()->getName();
                if ($cartItem->getProduct()->getData('visibility') == 1) {
                    $cart_products[$key]["url"] = "Not Visible Individually";
                } else {
                    $cart_products[$key]["url"] = $cartItem->getProduct()->getProductUrl();
                }
                $cart_products[$key]["price"] = $cartItem->getPrice();
                $cart_products[$key]["qty"] = $cartItem->getQty();
                $cart_products[$key]['product_id'] = $cartItem->getProductId();
                $cart_products[$key]["total"] = $cartItem->getQty() * $cartItem->getProduct()->getPrice();
                $cartProductsCount += $cartItem->getQty();
                $cartTotal += $cart_products[$key]["total"];
                $key++;
            }
            $currencyCode = $cart->getBaseCurrencyCode();
        }

        foreach ($properties as $property) {
            if ($this->exportPropertiesAndSegments->canSetProperty(
                $property['alias'],
                \Ced\MauticIntegration\Model\Cedmautic::TYPE_PROPERTY
            )
            ) {
                switch ($property['alias']) {
                    case 'ced_abncart_stat':
                        $arr['ced_abncart_stat'] = 'yes';
                        break;

                    case 'ced_abncart_prod_html':
                        if (isset($cart_products)) {
                            $arr['ced_abncart_prod_html'] = $this->getProductsHtml($cart_products, $currencyCode);
                        }
                        break;

                    case 'ced_abncart_total':
                        $arr['ced_abncart_total'] = $cartTotal;
                        break;
                }
            }
        }
        return $arr;
    }

    /**
     * @param $cart_products
     * @param $currencyCode
     * @return string
     */
    public function getProductsHtml($cart_products, $currencyCode)
    {
        if ($currencyCode) {
            $currencySymbol = $this->currencyFactory->create()->load($currencyCode)->getCurrencySymbol();
        }
        $products_html = "";
        if (!empty($cart_products)) {
            $products_html =
                '<div class="" style="position: relative; left: 0px; top: 0px;" data-slot="separator"><hr></div>
                   <div data-slot="text"><table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tbody>
								<tr>
									<td style="border-bottom: 1px solid #f4f4f4;" width="20%">
										<strong>Item</strong>
									</td>
									<td style="border-bottom: 1px solid #f4f4f4;" width="20%">
										<strong>Image</strong>
									</td>
									<td style="border-bottom: 1px solid #f4f4f4;" width="20%">
										<strong>Qty</strong>
									</td>
									<td style="border-bottom: 1px solid #f4f4f4;" width="20%">
										<strong>Cost</strong>
									</td>
									<td style="border-bottom: 1px solid #f4f4f4;" width="20%">
										<strong>Total</strong>
									</td>
								</tr>';

            foreach ($cart_products as $single_product) {
                $cartProduct = $this->getProductData($single_product['product_id']);
                $image = $this->getImageUrl($cartProduct->getImage());
                $products_html .= '<tr>
							<td width="20%">
								<a href="' .
                    $single_product["url"] . '"><strong>' . $single_product["name"] . '</strong></a>
							</td>
							<td width="20%">
								<img src="' .$image. '" 
								height="100px" width="100px"/>
							</td>
							<td width="20%">
								<div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto 
								!important; font-size:14px; line-height:20px; text-align:left">' .
                    $single_product["qty"] . '</div>
							</td>
							<td width="20%">
								' .
                    $currencySymbol . $single_product["price"] . '
							</td>

							<td width="20%">
								' . $currencySymbol . $single_product["total"] . '
							</td>
						</tr>';
            }

            $products_html .= '</tbody></table></div><div class="" style="position: relative; 
                              left: 0px; top: 0px;" data-slot="separator"><hr></div>';
        }

        return $products_html;
    }

    /**
     * @param $productId
     * @return mixed
     */
    public function getProductData($productId)
    {
        if (isset($this->productObject[$productId])) {
            return $this->productObject[$productId];
        } else {
            $this->productObject[$productId] = $this->productFactory->create()->addAttributeToSelect('*')
                ->addAttributeToFilter('entity_id', $productId)
                ->getFirstItem();
            return $this->productObject[$productId];
        }
    }

    /**
     * @param $image
     * @return string
     */
    public function getImageUrl($image)
    {
        $url = $this->storeManager->getStore()->getBaseUrl();
        $imageUrl = $url . "pub/media/catalog/product" . $image;
        return $imageUrl;
    }
}
