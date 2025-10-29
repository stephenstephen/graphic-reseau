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

class OrderDetails extends AbstractHelper
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    public $productFactory;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    public $categoryFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    public $dateTime;

    public $orderDetails;

    public $lastOrderDetails;

    public $firstOrderDetails;

    public $productObject;

    public $properties;

    public $connectionManager;

    public $exportPropertiesAndSegments;

    public $lastCompletedOrderDetails;

    public $categoryName;

    public $countryName;

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    public $country;

    /**
     * OrderDetails constructor.
     * @param Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory
     * @param Properties $properties
     * @param ConnectionManager $connectionManager
     * @param ExportPropertiesAndSegments $exportPropertiesAndSegments
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Directory\Model\CountryFactory $countryFactory
     */
    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory,
        Properties $properties,
        ConnectionManager $connectionManager,
        ExportPropertiesAndSegments $exportPropertiesAndSegments,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\CountryFactory $countryFactory
    ) {
        parent::__construct($context);
        $this->productFactory = $productFactory;
        $this->properties = $properties;
        $this->connectionManager = $connectionManager;
        $this->exportPropertiesAndSegments = $exportPropertiesAndSegments;
        $this->categoryFactory = $categoryFactory;
        $this->storeManager = $storeManager;
        $this->country = $countryFactory;
        $this->dateTime = $dateTime;
    }

    /**
     * @param $customer
     * @param $orders
     * @param $completedOrder
     * @param $arr
     * @return mixed
     */
    public function orderDetailsToExport($customer, $orders, $completedOrder, $arr)
    {
        $this->orderDetails = [];
        $this->lastOrderDetails = [];
        $this->firstOrderDetails = [];
        $this->lastCompletedOrderDetails = [];
        $shoppingCartProperties = $this->properties->allProperties('shopping_cart_fields');

        if ($this->connectionManager->isCustomerGroupEnabled('shopping_cart_fields')) {
            $arr = $this->getShoppingCartFields($customer, $orders, $shoppingCartProperties, $arr);
        }

        if ($orders != null) {
            $this->setOrderDetails($orders);
            $this->setLastOrderDetails(end($orders));
            $this->setFirstOrderDetails(reset($orders));
            $orderProperties = $this->properties->allProperties('order');
            $arr = $this->getOrderGroupDetails($orderProperties, $arr);
            $lastProductProperties = $this->properties->allProperties('last_products_bought');
            $arr = $this->getLastProductsBought($lastProductProperties, $arr);
            $categoriesBoughtProperties = $this->properties->allProperties('categories_bought');
            $arr = $this->getCategoriesBought($categoriesBoughtProperties, $arr);
            $rfmProperties = $this->properties->allProperties('rfm_fields');
            $arr = $this->getRfmFields($rfmProperties, $arr);
            $skusProperties = $this->properties->allProperties('skus_bought');
            $arr = $this->getSkusBought($skusProperties, $arr);
            $feedback = $this->connectionManager->isCustomerGroupEnabled('feedback');
            if ($feedback && $completedOrder!=null) {
                $this->setLastCompletedOrder($completedOrder);
                $feedbackProperties = $this->properties->allProperties('order_feedback');
                $arr = $this->getLastCompletedOrder($feedbackProperties, $arr);
            }
        }

        return $arr;
    }
    /**
     * @param $orders
     */
    public function setOrderDetails($orders)
    {
        $this->orderDetails['totalProductsBought'] = 0;
        $this->orderDetails['totalOrderValues'] = 0;
        $this->orderDetails['productsBought'] = [];
        $this->orderDetails['totalOrders'] = count($orders);

        foreach ($orders as $order) {
            foreach ($order->getAllVisibleItems() as $orderItem) {
                array_push(
                    $this->orderDetails['productsBought'],
                    $orderItem->getData('name') . "-" . $orderItem->getData('product_id')
                );
                $this->orderDetails['totalProductsBought'] += $orderItem->getData('qty_ordered');

                $product[$orderItem->getData('product_id')] = $this->getProductData($orderItem
                    ->getData('product_id'));

                $tempCat = $product[$orderItem->getData('product_id')]->getCategoryIds();
                if (!empty($tempCat) && is_array($tempCat)) {
                    $categoryId = $tempCat[0];
                    $category = $this->getCategoryName($categoryId);
                    $this->orderDetails['categoriesBought'][$category] = $category;
                }

                $sku = $orderItem->getData('sku');
                $this->orderDetails['skusBought'][$sku] = $sku;
            }
            $this->orderDetails['totalOrderValues'] += $order->getData('grand_total');
        }
    }

    /**
     * @param $lastOrder
     */
    public function setLastOrderDetails($lastOrder)
    {
        $this->lastOrderDetails['lastTotalProductsBought'] = 0;
        $this->lastOrderDetails['lastProductsBought'] = [];
        $this->orderDetails['lastThreeProducts']['last'] = [];
        $this->lastOrderDetails['trackingNumber'] = 0;
        $this->lastOrderDetails['carrierCode'] = " ";
        $break = false;
        $shipmentCollection = $lastOrder->getShipmentsCollection();
        foreach ($shipmentCollection as $shipment) {
            $this->lastOrderDetails['lastOrderShipDate'] = $shipment->getData('created_at');
            $tracks = $shipment->getAllTracks();
            foreach ($tracks as $track) {
                $this->lastOrderDetails['trackingNumber'] = $track->getTrackNumber();
                $this->lastOrderDetails['carrierCode'] = $track->getCarrierCode();
                $break = true;
                break;
            }
            if ($break) {
                break;
            }
        }

        $this->lastOrderDetails['lastOrderStatus'] = $lastOrder->getData('status');
        $this->lastOrderDetails['lastOrderNumber'] = $lastOrder->getIncrementId();
        $this->lastOrderDetails['lastOrderValue'] = (float)$lastOrder->getData('grand_total');
        $this->lastOrderDetails['lastOrderDate'] = date('Y-m-d H:i:s', strtotime($lastOrder->getCreatedAt()));

        foreach ($lastOrder->getAllVisibleItems() as $orderItem) {
            $this->setLastProduct($orderItem);
            $this->lastOrderDetails['lastTotalProductsBought'] += $orderItem->getData('qty_ordered');
            array_push(
                $this->lastOrderDetails['lastProductsBought'],
                $orderItem->getData('name') . "-" . $orderItem->getData('product_id')
            );

            $productId = $orderItem->getData('product_id');
            $product[$productId] = $this->getProductData($productId);

            $tempCat = $product[$productId]->getCategoryIds();
            if (!empty($tempCat) && is_array($tempCat)) {
                $categoryId = $tempCat[0];
                $category = $this->getCategoryName($categoryId);
                $this->lastOrderDetails['lastCategoriesBought'][$category] = $category;
            }
            $sku = $orderItem->getData('sku');
            $this->lastOrderDetails['lastSkusBought'][$sku] = $sku;
        }

        if (!empty($this->orderDetails['lastThreeProducts']['last'])) {
            $this->orderDetails['lastProduct'] = $this->getProductData($this->
            orderDetails['lastThreeProducts']['last']['product_id']);
        }
    }

    /**
     * @param $firstOrder
     */
    public function setFirstOrderDetails($firstOrder)
    {
        $this->firstOrderDetails['firstOrderValue'] = (float)$firstOrder->getData('grand_total');
        $this->firstOrderDetails['firstOrderDate'] = date('Y-m-d H:i:s', strtotime($firstOrder->getCreatedAt()));
    }

    public function setLastCompletedOrder($lastCompletedOrder)
    {
        $this->lastCompletedOrderDetails['ced_last_comp_order_date'] =
            date('Y-m-d H:i:s', strtotime($lastCompletedOrder->getUpdatedAt()));
        $this->lastCompletedOrderDetails['ced_last_comp_order_num'] = $lastCompletedOrder->getIncrementId();
        $feedbackFormHtml = '<div class="" style="position: relative; left: 0px; top: 0px;" data-slot="separator">
                              <hr></div>
                   <div data-slot="text"><table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tbody>
								<tr>
									<td style="border-bottom: 1px solid #f4f4f4; text-align: center;" width="33%">
										<strong>Item</strong>
									</td>
									<td style="border-bottom: 1px solid #f4f4f4; text-align: center" width="33%">
										<strong>Image</strong>
									</td>
									<td style="border-bottom: 1px solid #f4f4f4; text-align: center" width="33%">
										<strong> Feedback Link </strong>
									</td>
								</tr>';

        foreach ($lastCompletedOrder->getAllVisibleItems() as $orderItem) {
            $orderProduct = $this->getProductData($orderItem->getData('product_id'));
            $image = $this->getImageUrl($orderProduct->getImage());
            $feedbackFormHtml .= '<tr>
							<td width="33%" style="text-align: center"><strong>'
                . $orderItem->getData('name') . '</strong>
							</td>
							<td width="33%" style="text-align: center">
								<img src="' .$image. '" width="100px" height="100px"/>
							</td>
							<td width="33%" style="text-align: center">
								<a href = "'.$orderProduct->getProductUrl().'" target="_blank"> Feedback for '
                .$orderItem->getData('name'). '</a>
							</td>
						</tr>';
        }

        $this->lastCompletedOrderDetails['ced_feedback_html'] = $feedbackFormHtml;
    }

    /**
     * @param $orderItem
     */
    public function setLastProduct($orderItem)
    {
        if (empty($this->orderDetails['lastThreeProducts']['last']) || isset($this->orderDetails['lastThreeProducts']
                ['last']['item_id']) && $this->orderDetails['lastThreeProducts']['last']['item_id'] <
            $orderItem->getData('item_id')) {
            $this->orderDetails['lastThreeProducts']['last'] = $orderItem->getData();
        }
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
     * @param $properties
     * @param $arr
     * @return mixed
     */
    public function getOrderGroupDetails($properties, $arr)
    {
        foreach ($properties as $property) {
            if ($this->exportPropertiesAndSegments->canSetProperty(
                $property['alias'],
                \Ced\MauticIntegration\Model\Cedmautic::TYPE_PROPERTY
            )
            ) {
                switch ($property['alias']) {
                    case 'ced_last_order_stat':
                        if (isset($this->lastOrderDetails['lastOrderStatus'])) {
                            $arr['ced_last_order_stat'] = $this->lastOrderDetails['lastOrderStatus'];
                        }
                        break;

                    case 'ced_last_order_track_num':
                        if (isset($this->lastOrderDetails['trackingNumber'])) {
                            $arr['ced_last_order_track_num'] = $this->lastOrderDetails['trackingNumber'];
                        }
                        break;

                    case 'ced_last_order_track_url':
                        if (isset($this->lastOrderDetails['carrierCode'])) {
                            $arr['ced_last_order_track_url'] = $this->lastOrderDetails['carrierCode'];
                        }
                        break;

                    case 'ced_last_order_ship_date':
                        if (isset($this->lastOrderDetails['lastOrderShipDate'])) {
                            $arr['ced_last_order_ship_date'] = $this->lastOrderDetails['lastOrderShipDate'];
                        }
                        break;

                    case 'ced_last_order_num':
                        if (isset($this->lastOrderDetails['lastOrderNumber'])) {
                            $arr['ced_last_order_num'] = $this->lastOrderDetails['lastOrderNumber'];
                        }
                        break;
                }
            }
        }

        return $arr;
    }

    /**
     * @param $properties
     * @param $arr
     * @return mixed
     */
    public function getLastProductsBought($properties, $arr)
    {
        foreach ($properties as $property) {
            if ($this->exportPropertiesAndSegments->canSetProperty(
                $property['alias'],
                \Ced\MauticIntegration\Model\Cedmautic::TYPE_PROPERTY
            )
            ) {
                switch ($property['alias']) {
                    case 'ced_last_products':
                        if (!empty($this->lastOrderDetails['lastProductsBought'])) {
                            $arr['ced_last_products'] = implode(';', $this->lastOrderDetails['lastProductsBought']);
                        }
                        break;

                    case 'ced_last_num_of_prod':
                        if (isset($this->lastOrderDetails['lastTotalProductsBought']) &&
                            $this->lastOrderDetails['lastTotalProductsBought'] > 0) {
                            $arr['ced_last_num_of_prod'] = $this->lastOrderDetails['lastTotalProductsBought'];
                        }
                        break;

                    case 'ced_products':
                        if (!empty($this->orderDetails['productsBought'])) {
                            $arr['ced_products'] = implode(';', $this->orderDetails['productsBought']);
                        }
                        break;

                    case 'ced_total_products_num':
                        if (isset($this->orderDetails['totalProductsBought']) &&
                            $this->orderDetails['totalProductsBought'] > 0) {
                            $arr['ced_total_products_num'] = $this->orderDetails['totalProductsBought'];
                        }
                        break;

                    case 'ced_prod_1_img_url':
                        if (!empty($this->orderDetails['lastThreeProducts']['last'])) {
                            $imageUrl = $this->getImageUrl($this->orderDetails['lastProduct']->getImage());
                            $arr['ced_prod_1_img_url'] = $imageUrl;
                        }
                        break;

                    case 'ced_prod_1_name':
                        if (!empty($this->orderDetails['lastThreeProducts']['last'])) {
                            $arr['ced_prod_1_name'] = $this->orderDetails['lastThreeProducts']['last']['name'];
                        }
                        break;

                    case 'ced_prod_1_price':
                        if (!empty($this->orderDetails['lastThreeProducts']['last'])) {
                            $arr['ced_prod_1_price'] =
                                $this->orderDetails['lastThreeProducts']['last']['base_row_total_incl_tax'] -
                                $this->orderDetails['lastThreeProducts']['last']['base_discount_amount'];
                        }
                        break;

                    case 'ced_prod_1_url':
                        if (!empty($this->orderDetails['lastThreeProducts']['last'])) {
                            if ($this->orderDetails['lastProduct']->getData('visibility') == 1) {
                                $arr['ced_prod_1_url'] = "Not Visible Individually";
                            } else {
                                $arr['ced_prod_1_url'] = $this->orderDetails['lastProduct']->getProductUrl();
                            }
                        }
                        break;
                }
            }
        }

        return $arr;
    }

    /**
     * @param $properties
     * @param $arr
     * @return mixed
     */
    public function getRfmFields($properties, $arr)
    {
        foreach ($properties as $property) {
            if ($this->exportPropertiesAndSegments->canSetProperty(
                $property['alias'],
                \Ced\MauticIntegration\Model\Cedmautic::TYPE_PROPERTY
            )
            ) {
                switch ($property['alias']) {
                    case 'ced_total_val_of_orders':
                        if ($this->orderDetails['totalOrderValues'] > 0) {
                            $arr['ced_total_val_of_orders'] = $this->orderDetails['totalOrderValues'];
                        }
                        break;

                    case 'ced_avg_order_value':
                        if ($this->orderDetails['totalOrderValues'] > 0) {
                            $arr['ced_avg_order_value'] =
                                $this->orderDetails['totalOrderValues'] / $this->orderDetails['totalOrders'];
                        }
                        break;

                    case 'ced_total_orders':
                        $arr['ced_total_orders'] = $this->orderDetails['totalOrders'];
                        break;

                    case 'ced_first_order_val':
                        $arr['ced_first_order_val'] = $this->firstOrderDetails['firstOrderValue'];
                        break;

                    case 'ced_first_order_date':
                        $arr['ced_first_order_date'] = $this->firstOrderDetails['firstOrderDate'];
                        break;

                    case 'ced_last_order_val':
                        $arr['ced_last_order_val'] = $this->lastOrderDetails['lastOrderValue'];
                        break;

                    case 'ced_last_order_date':
                        $arr['ced_last_order_date'] = $this->lastOrderDetails['lastOrderDate'];
                        break;

                    case 'ced_avg_days_bt_orders':
                        $arr['ced_avg_days_bt_orders'] = $this->getAvgDays();
                        break;

                    case 'ced_order_monetary':
                        if ($this->orderDetails['totalOrderValues'] > 0) {
                            $monetaryRating = $this->getRating('monetary', $this->orderDetails['totalOrderValues']);
                            $arr['ced_order_monetary'] = (int)$monetaryRating;
                        }
                        break;

                    case 'ced_order_frequency':
                        $frequencyRating = $this->getRating('frequency', $this->orderDetails['totalOrders']);
                        $arr['ced_order_frequency'] = (int)$frequencyRating;
                        break;

                    case 'ced_order_recency':
                        $recencyDateDiff = $this->getRecencyDateDiff();
                        $recencyRating = $this->getRating('recency', $recencyDateDiff);
                        $arr['ced_order_recency'] = (int)$recencyRating;
                        break;
                }
            }
        }

        return $arr;
    }

    /**
     * @param $properties
     * @param $arr
     * @return mixed
     */
    public function getCategoriesBought($properties, $arr)
    {
        foreach ($properties as $property) {
            if ($this->exportPropertiesAndSegments->canSetProperty(
                $property['alias'],
                \Ced\MauticIntegration\Model\Cedmautic::TYPE_PROPERTY
            )
            ) {
                switch ($property['alias']) {
                    case 'ced_categories':
                        if (isset($this->orderDetails['categoriesBought'])) {
                            $arr['ced_categories'] =
                                implode(';', array_values($this->orderDetails['categoriesBought']));
                        }
                        break;

                    case 'ced_last_categories':
                        if (isset($this->lastOrderDetails['lastCategoriesBought'])) {
                            $arr['ced_last_categories'] =
                                implode(';', array_values($this->lastOrderDetails['lastCategoriesBought']));
                        }
                        break;
                }
            }
        }

        return $arr;
    }

    /**
     * @param $properties
     * @param $arr
     * @return mixed
     */
    public function getSkusBought($properties, $arr)
    {
        foreach ($properties as $property) {
            if ($this->exportPropertiesAndSegments->canSetProperty(
                $property['alias'],
                \Ced\MauticIntegration\Model\Cedmautic::TYPE_PROPERTY
            )
            ) {
                switch ($property['alias']) {
                    case 'ced_skus':
                        if (isset($this->orderDetails['skusBought'])) {
                            $arr['ced_skus'] = implode(';', array_values($this->orderDetails['skusBought']));
                        }
                        break;

                    case 'ced_last_skus':
                        if (isset($this->lastOrderDetails['lastSkusBought'])) {
                            $arr['ced_last_skus'] =
                                implode(';', array_values($this->lastOrderDetails['lastSkusBought']));
                        }
                        break;
                }
            }
        }

        return $arr;
    }

    public function getLastCompletedOrder($properties, $arr)
    {
        foreach ($properties as $property) {
            if ($this->exportPropertiesAndSegments->canSetProperty(
                $property['alias'],
                \Ced\MauticIntegration\Model\Cedmautic::TYPE_PROPERTY
            )
            ) {
                switch ($property['alias']) {
                    case 'ced_feedback_html':
                        if (isset($this->lastCompletedOrderDetails['ced_feedback_html'])) {
                            $arr['ced_feedback_html'] = $this->lastCompletedOrderDetails['ced_feedback_html'];
                        }
                        break;

                    case 'ced_last_comp_order_date':
                        if (isset($this->lastCompletedOrderDetails['ced_last_comp_order_date'])) {
                            $arr['ced_last_comp_order_date'] =
                                $this->lastCompletedOrderDetails['ced_last_comp_order_date'];
                        }
                        break;

                    case 'ced_last_comp_order_num':
                        if (isset($this->lastCompletedOrderDetails['ced_last_comp_order_num'])) {
                            $arr['ced_last_comp_order_num'] =
                                $this->lastCompletedOrderDetails['ced_last_comp_order_num'];
                        }
                        break;
                }
            }
        }
        return $arr;
    }

    /**
     * @param $category
     * @return mixed
     */
    public function getCategoryName($category)
    {
        if (isset($this->categoryName[$category])) {
            return $this->categoryName[$category];
        } else {
            $this->categoryName[$category] = $this->categoryFactory->create()->load($category)->getName();
            return $this->categoryName[$category];
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

    /**
     * @return float|int
     */
    public function getAvgDays()
    {
        $firstOrderDate = date('Y-m-d', strtotime($this->firstOrderDetails['firstOrderDate']));
        $lastOrderDate = date('Y-m-d', strtotime($this->lastOrderDetails['lastOrderDate']));
        $dateDiff = date_diff(date_create($lastOrderDate), date_create($firstOrderDate))->format('%a');
        $avgDays = $dateDiff / $this->orderDetails['totalOrders'];
        return $avgDays;
    }

    /**
     * @return string
     */
    public function getRecencyDateDiff()
    {
        $currentTime = $this->dateTime->Date('Y-m-d H:i:s');
        $time = date('Y-m-d', strtotime($currentTime));
        $recencyDateDiff = date_diff(date_create($time), date_create($this->lastOrderDetails['lastOrderDate']))
            ->format('%a');
        return $recencyDateDiff;
    }

    /**
     * @param $keyword
     * @param $value
     * @return int
     */
    public function getRating($keyword, $value)
    {
        $data = $this->scopeConfig->getValue('mautic_integration/mautic_rfm_settings/rfm_fields');
        $rfmRating = json_decode($data, true);

        switch ($keyword) {
            case 'recency':
                if ($value <= $rfmRating['rfm_at_5'][$keyword]) {
                    return 5;
                } elseif ($value >= $rfmRating['from_rfm_4'][$keyword] && $value <= $rfmRating['to_rfm_4'][$keyword]) {
                    return 4;
                } elseif ($value >= $rfmRating['from_rfm_3'][$keyword] && $value <= $rfmRating['to_rfm_3'][$keyword]) {
                    return 3;
                } elseif ($value >= $rfmRating['from_rfm_2'][$keyword] && $value <= $rfmRating['to_rfm_2'][$keyword]) {
                    return 2;
                } else {
                    return 1;
                }
                break;

            case 'frequency':
                if ($value >= $rfmRating['rfm_at_5'][$keyword]) {
                    return 5;
                } elseif ($value >= $rfmRating['from_rfm_4'][$keyword] && $value <= $rfmRating['to_rfm_4'][$keyword]) {
                    return 4;
                } elseif ($value >= $rfmRating['from_rfm_3'][$keyword] && $value <= $rfmRating['to_rfm_3'][$keyword]) {
                    return 3;
                } elseif ($value >= $rfmRating['from_rfm_2'][$keyword] && $value <= $rfmRating['to_rfm_2'][$keyword]) {
                    return 2;
                } else {
                    return 1;
                }
                break;

            case 'monetary':
                if ($value >= $rfmRating['rfm_at_5'][$keyword]) {
                    return 5;
                } elseif ($value >= $rfmRating['from_rfm_4'][$keyword] && $value <= $rfmRating['to_rfm_4'][$keyword]) {
                    return 4;
                } elseif ($value >= $rfmRating['from_rfm_3'][$keyword] && $value <= $rfmRating['to_rfm_3'][$keyword]) {
                    return 3;
                } elseif ($value >= $rfmRating['from_rfm_2'][$keyword] && $value <= $rfmRating['to_rfm_2'][$keyword]) {
                    return 2;
                } else {
                    return 1;
                }
                break;

            default:
                return 0;
        }
    }

    /**
     * @param $customer
     * @param $orders
     * @param $properties
     * @param $arr
     * @return mixed
     */
    public function getShoppingCartFields($customer, $orders, $properties, $arr)
    {
        $billingAddress = [];
        $shippingAddress = [];
        if ($orders != null) {
            $lastOrder = end($orders);
            $shippingAddress = $lastOrder->getShippingAddress();
            $billingAddress = $lastOrder->getBillingAddress();
        } else {
            if ($customer->getDefaultShippingAddress()) {
                $shippingAddress = $customer->getDefaultShippingAddress();
            } elseif ($customer->getAddresses()) {
                foreach ($customer->getAddresses() as $address) {
                    $shippingAddress = $address;
                }
            }

            if ($customer->getDefaultBillingAddress()) {
                $billingAddress = $customer->getDefaultBillingAddress();
            } elseif ($customer->getAddresses()) {
                foreach ($customer->getAddresses() as $address) {
                    $billingAddress = $address;
                }
            }
        }

        foreach ($properties as $property) {
            if ($this->exportPropertiesAndSegments->canSetProperty(
                $property['alias'],
                \Ced\MauticIntegration\Model\Cedmautic::TYPE_PROPERTY
            )
            ) {
                switch ($property['alias']) {
                    case 'ced_ship_add_line_1':
                        if (!empty($shippingAddress) && isset($shippingAddress->getStreet()[0])) {
                            $arr['ced_ship_add_line_1'] = $shippingAddress->getStreet()[0];
                        }
                        break;

                    case 'ced_ship_add_line_2':
                        if (!empty($shippingAddress) && $shippingAddress->getStreet()) {
                            $addressLine2 = $this->getAddressLine2(
                                $shippingAddress->getStreet()[0],
                                $shippingAddress->getStreetFull()
                            );
                            if ($addressLine2 != "") {
                                $arr['ced_ship_add_line_2'] = $addressLine2;
                            }
                        }
                        break;

                    case 'ced_ship_city':
                        if (!empty($shippingAddress) && $shippingAddress->getCity()) {
                            $arr['ced_ship_city'] = $shippingAddress->getCity();
                        }
                        break;

                    case 'ced_ship_state':
                        if (!empty($shippingAddress)) {
                            if ($shippingAddress->getRegion()) {
                                $arr['shipping_state'] = $shippingAddress->getRegion();
                            }
                        }
                        break;

                    case 'ced_ship_post_code':
                        if (!empty($shippingAddress) && $shippingAddress->getPostcode()) {
                            $arr['ced_ship_post_code'] = $shippingAddress->getPostcode();
                        }
                        break;

                    case 'ced_ship_country':
                        if (!empty($shippingAddress)) {
                            $countryCode = $shippingAddress->getCountry() ?: $shippingAddress->getCountryId();
                            if ($countryCode) {
                                $shippingCountry = $this->getCountry($countryCode);
                                $arr['ced_ship_country'] = $shippingCountry;
                            }
                        }
                        break;

                    case 'ced_bill_add_line_1':
                        if (!empty($billingAddress) && isset($billingAddress->getStreet()[0])) {
                            $arr['ced_bill_add_line_1'] = $billingAddress->getStreet()[0];
                        }
                        break;

                    case 'ced_bill_add_line_2':
                        if (!empty($billingAddress) && $billingAddress->getStreet()) {
                            $addressLine2 = $this->getAddressLine2(
                                $billingAddress->getStreet()[0],
                                $billingAddress->getStreetFull()
                            );
                            if ($addressLine2 != "") {
                                $arr['ced_bill_add_line_2'] = $addressLine2;
                            }
                        }
                        break;

                    case 'ced_bill_city':
                        if (!empty($billingAddress) && $billingAddress->getCity()) {
                            $arr['ced_bill_city'] = $billingAddress->getCity();
                        }
                        break;

                    case 'ced_bill_state':
                        if (!empty($billingAddress)) {
                            if ($billingAddress->getRegion()) {
                                $arr['ced_bill_state'] = $billingAddress->getRegion();
                            }
                        }
                        break;

                    case 'ced_bill_post_code':
                        if (!empty($billingAddress) && $billingAddress->getPostcode()) {
                            $arr['ced_bill_post_code'] = $billingAddress->getPostcode();
                        }
                        break;

                    case 'ced_bill_country':
                        if (!empty($billingAddress)) {
                            $countryCode = $billingAddress->getCountry() ?: $billingAddress->getCountryId();
                            if ($countryCode) {
                                $billingCountry = $this->getCountry($countryCode);
                                $arr['ced_bill_country'] = $billingCountry;
                            }
                        }
                        break;
                }
            }

        }
        if (!empty($billingAddress) && $billingAddress->getCompany()) {
            $arr['company'] = $billingAddress->getCompany();
        } elseif (!empty($shippingAddress) && $shippingAddress->getCompany()) {
            $arr['company'] = $shippingAddress->getCompany();
        }
        return $arr;
    }

    /**
     * @param $addressLine1
     * @param $address
     * @return mixed
     */
    public function getAddressLine2($addressLine1, $address)
    {
        $addressLines = str_replace(
            $addressLine1,
            "",
            $address
        );
        $addressLine2 = str_replace("\n", " ", $addressLines);
        return $addressLine2;
    }

    /**
     * @param $countryCode
     * @return mixed
     */
    public function getCountry($countryCode)
    {
        if (!isset($this->countryName[$countryCode])) {
            $this->countryName[$countryCode] = $this->country->create()->loadByCode($countryCode)->getName();
        }

        return $this->countryName[$countryCode];
    }
}
