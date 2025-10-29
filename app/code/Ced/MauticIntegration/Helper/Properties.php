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

use Magento\Framework\App\Helper\AbstractHelper;

class Properties extends AbstractHelper
{
    /**
     * @return array
     */
    public function allGroups()
    {
        $values = [];
        $values[] = ['name' => 'customer_group', 'displayName' => 'Customer Group'];
        $values[] = ['name' => 'shopping_cart_fields', 'displayName' => 'Shoping Cart Information'];
        $values[] = ['name' => 'order', 'displayName' => 'Order'];
        $values[] = ['name' => 'last_products_bought', 'displayName' => 'Last Products Bought'];
        $values[] = ['name' => 'categories_bought', 'displayName' => 'Categories Bought'];
        $values[] = ['name' => 'rfm_fields', 'displayName' => 'RFM Information'];
        $values[] = ['name' => 'skus_bought', 'displayName' => 'SKUs Bought'];
        $values[] = ['name' => 'abandoned_cart', 'displayName' => 'Abandoned Cart'];
        $values[] = ['name' => 'order_feedback', 'displayName' => 'Order Feedback'];

        return $values;
    }

    /**
     * @param $group_name
     * @return array
     */
    public function allProperties($group_name)
    {
        $group_properties = [];
        if (!empty($group_name)) {
            if ($group_name == "customer_group") {
                $group_properties['ced_customer_group'] = [
                    "alias" => "ced_customer_group",
                    "label" => 'Customer Group/ User role',
                    "type" => "text",
                    "defaultValue" => "null"
                ];

                $group_properties['ced_newsletter_subs'] = [
                    "alias" => "ced_newsletter_subs",
                    "label" => 'Accepts Marketing',
                    "type" => "select",
                    "defaultValue" => "no",
                    "properties" => ['list' => $this->getUserMarketingAction()]
                ];

                $group_properties['ced_customer_cart_id'] = [
                    "alias" => "ced_customer_cart_id",
                    "label" => 'Store Customer ID',
                    "type" => "number"
                ];

                $group_properties['ced_acc_creation_date'] = [
                    "alias" => "ced_acc_creation_date",
                    "label" => 'Account Creation Date',
                    "type" => "date"
                ];
            } elseif ($group_name == "shopping_cart_fields") {
                $group_properties['ced_ship_add_line_1'] = [
                    "alias" => "ced_ship_add_line_1",
                    "label" => 'Shipping Address Line 1',
                    "type" => "text"
                ];

                $group_properties['ced_ship_add_line_2'] = [
                    "alias" => "ced_ship_add_line_2",
                    "label" => 'Shipping Address Line 2',
                    "type" => "text"
                ];

                $group_properties['ced_ship_city'] = [
                    "alias" => "ced_ship_city",
                    "label" => 'Shipping City',
                    "type" => "text"
                ];

                $group_properties['ced_ship_state'] = [
                    "alias" => "ced_ship_state",
                    "label" => 'Shipping State',
                    "type" => "text"
                ];

                $group_properties['ced_ship_post_code'] = [
                    "alias" => "ced_ship_post_code",
                    "label" => 'Shipping Postal Code',
                    "type" => "text"
                ];

                $group_properties['ced_ship_country'] = [
                    "alias" => "ced_ship_country",
                    "label" => 'Shipping Country',
                    "type" => "text"
                ];

                $group_properties['ced_bill_add_line_1'] = [
                    "alias" => "ced_bill_add_line_1",
                    "label" => 'Billing Address Line 1',
                    "type" => "text"
                ];

                $group_properties['ced_bill_add_line_2'] = [
                    "alias" => "ced_bill_add_line_2",
                    "label" => 'Billing Address Line 2',
                    "type" => "text"
                ];

                $group_properties['ced_bill_city'] = [
                    "alias" => "ced_bill_city",
                    "label" => 'Billing City',
                    "type" => "text"
                ];

                $group_properties['ced_bill_state'] = [
                    "alias" => "ced_bill_state",
                    "label" => 'Billing State',
                    "type" => "text"
                ];

                $group_properties['ced_bill_post_code'] = [
                    "alias" => "ced_bill_post_code",
                    "label" => 'Billing Postal Code',
                    "type" => "text"
                ];

                $group_properties['ced_bill_country'] = [
                    "alias" => "ced_bill_country",
                    "label" => 'Billing Country',
                    "type" => "text"
                ];
            } elseif ($group_name == "last_products_bought") {
                $group_properties['ced_last_products'] = [
                    "alias" => "ced_last_products",
                    "label" => 'Last Products Bought',
                    "type" => "textarea"
                ];

                $group_properties['ced_last_num_of_prod'] = [
                    "alias" => "ced_last_num_of_prod",
                    "label" => 'Last Total Number Of Products Bought',
                    "type" => "number"
                ];

                $group_properties['ced_products'] = [
                    "alias" => "ced_products",
                    "label" => 'Products Bought',
                    "type" => "textarea"
                ];

                $group_properties['ced_total_products_num'] = [
                    "alias" => "ced_total_products_num",
                    "label" => 'Total Number Of Products Bought',
                    "type" => "number"
                ];

                $group_properties['ced_prod_1_img_url'] = [
                    "alias" => "ced_prod_1_img_url",
                    "label" => 'Last Products Bought Product 1 Image URL',
                    "type" => "text"
                ];

                $group_properties['ced_prod_1_name'] = [
                    "alias" => "ced_prod_1_name",
                    "label" => 'Last Products Bought Product 1 Name',
                    "type" => "text"
                ];

                $group_properties['ced_prod_1_price'] = [
                    "alias" => "ced_prod_1_price",
                    "label" => 'Last Products Bought Product 1 Price',
                    "type" => "text"
                ];

                $group_properties['ced_prod_1_url'] = [
                    "alias" => "ced_prod_1_url",
                    "label" => 'Last Products Bought Product 1 Url',
                    "type" => "text"
                ];
            } elseif ($group_name == "order") {
                $group_properties['ced_last_order_stat'] = [
                    "alias" => "ced_last_order_stat",
                    "label" => 'Last Order Status',
                    "type" => "text"
                ];

                $group_properties['ced_last_order_track_num'] = [
                    "alias" => "ced_last_order_track_num",
                    "label" => 'Last Order Tracking Number',
                    "type" => "text"
                ];

                $group_properties['ced_last_order_track_url'] = [
                    "alias" => "ced_last_order_track_url",
                    "label" => 'Last Order Tracking URL',
                    "type" => "text"
                ];

                $group_properties['ced_last_order_ship_date'] = [
                    "alias" => "ced_last_order_ship_date",
                    "label" => 'Last Order Shipment Date',
                    "type" => "date"
                ];

                $group_properties['ced_last_order_num'] = [
                    "alias" => "ced_last_order_num",
                    "label" => 'Last Order Number',
                    "type" => "text"
                ];
            } elseif ($group_name == "rfm_fields") {
                $group_properties['ced_total_val_of_orders'] = [
                    "alias" => "ced_total_val_of_orders",
                    "label" => 'Total Value of Orders',
                    "type" => "number"
                ];

                $group_properties['ced_avg_order_value'] = [
                    "alias" => "ced_avg_order_value",
                    "label" => 'Average Order Value',
                    "type" => "number"
                ];

                $group_properties['ced_total_orders'] = [
                    "alias" => "ced_total_orders",
                    "label" => 'Total Number of Orders',
                    "type" => "number"
                ];

                $group_properties['ced_first_order_val'] = [
                    "alias" => "ced_first_order_val",
                    "label" => 'First Order Value',
                    "type" => "number"
                ];

                $group_properties['ced_first_order_date'] = [
                    "alias" => "ced_first_order_date",
                    "label" => 'First Order Date',
                    "type" => "date"
                ];

                $group_properties['ced_last_order_val'] = [
                    "alias" => "ced_last_order_val",
                    "label" => 'Last Order Value',
                    "type" => "number"
                ];

                $group_properties['ced_last_order_date'] = [
                    "alias" => "ced_last_order_date",
                    "label" => 'Last Order Date',
                    "type" => "date"
                ];

                $group_properties['ced_avg_days_bt_orders'] = [
                    "alias" => "ced_avg_days_bt_orders",
                    "label" => 'Average Days Between Orders',
                    "type" => "number"
                ];

                $group_properties['ced_order_monetary'] = [
                    "alias" => "ced_order_monetary",
                    "label" => 'Order Monetary Rating',
                    "type" => "select",
                    "defaultValue" => "null",
                    "properties" => ['list' => $this->getRfmRating()]

                ];

                $group_properties['ced_order_frequency'] = [
                    "alias" => "ced_order_frequency",
                    "label" => 'Order Frequency Rating',
                    "type" => "select",
                    "defaultValue" => "null",
                    "properties" => ['list' => $this->getRfmRating()]
                ];

                $group_properties['ced_order_recency'] = [
                    "alias" => "ced_order_recency",
                    "label" => 'Order Recency Rating',
                    "type" => "select",
                    "defaultValue" => "null",
                    "properties" => ['list' => $this->getRfmRating()]
                ];
            } elseif ($group_name == "categories_bought") {
                $group_properties['ced_categories'] = [
                    "alias" => "ced_categories",
                    "label" => 'Categories Bought',
                    "type" => "text"
                ];

                $group_properties['ced_last_categories'] = [
                    "alias" => "ced_last_categories",
                    "label" => 'Last Categories Bought',
                    "type" => "text"
                ];
            } elseif ($group_name == "skus_bought") {
                $group_properties['ced_last_skus'] = [
                    "alias" => "ced_last_skus",
                    "label" => 'Last SKUs Bought',
                    "type" => "text"
                ];

                $group_properties['ced_skus'] = [
                    "alias" => "ced_skus",
                    "label" => 'SKUs Bought',
                    "type" => "textarea"
                ];
            } elseif ($group_name == 'abandoned_cart') {
                $group_properties['ced_abncart_stat'] = [
                    "alias" => "ced_abncart_stat",
                    "label" => 'Current Abandoned Cart',
                    "type" => "select",
                    "defaultValue" => "no",
                    "properties" => ['list' => $this->getAbandonedCartStatus()]
                ];

                $group_properties['ced_abncart_prod_html'] = [
                    "alias" => "ced_abncart_prod_html",
                    "label" => 'Abandoned Cart Products Html',
                    "type" => "textarea"
                ];

                $group_properties['ced_abncart_total'] = [
                    "alias" => "ced_abncart_total",
                    "label" => 'Abandoned Cart Total Value',
                    "type" => "number"
                ];
            } elseif ($group_name == 'order_feedback') {
                $group_properties['ced_feedback_html'] = [
                    "alias" => "ced_feedback_html",
                    "label" => 'Order Feedback Html',
                    "type" => "textarea",
                    "defaultValue" => " "
                ];

                $group_properties['ced_last_comp_order_date'] = [
                    "alias" => "ced_last_comp_order_date",
                    "label" => 'Last Completed Order Date',
                    "type" => "date"
                ];

                $group_properties['ced_last_comp_order_num'] = [
                    "alias" => "ced_last_comp_order_num",
                    "label" => 'Last Completed Order Number',
                    "type" => "text"
                ];
            }
        }

        return $group_properties;
    }

    /**
     * @return array
     */
    public function getMauticSegments()
    {
        $segments = [];
        $segments['ced-abandoned-cart'] = [
            'name' => 'Abandoned Cart',
            'alias' => 'ced-abandoned-cart',
            'description' => 'Segment to capture cart abandoners',
            'isPublished' => 1,
            'filters' => [
                [
                    'glue' => 'and',
                    'field' => 'ced_abncart_stat',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 'yes',
                    'operator' => '='
                ]
            ]
        ];

        $segments['ced-new-customers'] = [
            'name' => 'New Customers',
            'alias' => 'ced-new-customers',
            'description' => 'Segment to capture all customers who have just started purchasing from my store.',
            'isPublished' => 1,
            'filters' => [
                [
                    'glue' => 'and',
                    'field' => 'ced_order_recency',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 1,
                    'operator' => '='
                ],
                [
                    'glue' => 'and',
                    'field' => 'ced_order_frequency',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 1,
                    'operator' => '='
                ]
            ]
        ];

        $segments['ced-newsletter-subs'] = [
            'name' => 'Newsletter Subscriber',
            'alias' => 'ced-newsletter-subs',
            'description' => 'Segment to capture users who subscribe to my weekly newsletter. Send one email weekly.',
            'isPublished' => 1,
            'filters' => [
                [
                    'glue' => 'and',
                    'field' => 'ced_newsletter_subs',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 'yes',
                    'operator' => '='
                ]
            ]
        ];

        $segments['ced-best-customers'] = [
            'name' => 'Best Customers',
            'alias' => 'ced-best-customers',
            'description' => 'Customers of my store with high RFM rating of 5-5-5. These are the customers who bought
            most recently, most often and spend the most.',
            'isPublished' => 1,
            'filters' => [
                [
                    'glue' => 'and',
                    'field' => 'ced_order_recency',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 5,
                    'operator' => '='
                ],
                [
                    'glue' => 'and',
                    'field' => 'ced_order_frequency',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 5,
                    'operator' => '='
                ],
                [
                    'glue' => 'and',
                    'field' => 'ced_order_monetary',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 5,
                    'operator' => '='
                ]
            ]
        ];

        $segments['ced-big-spenders'] = [
            'name' => 'Big Spenders',
            'alias' => 'ced-big-spenders',
            'description' => 'Customers who spend most on my store and have high Monetary rating than other customers.',
            'isPublished' => 1,
            'filters' => [
                [
                    'glue' => 'and',
                    'field' => 'ced_order_monetary',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 5,
                    'operator' => '='
                ]
            ]
        ];

        $segments['ced-mid-spenders'] = [
            'name' => 'Mid Spenders',
            'alias' => 'ced-mid-spenders',
            'description' => 'Customers who spend average on my store and have Monetary rating equal to 3.',
            'isPublished' => 1,
            'filters' => [
                [
                    'glue' => 'and',
                    'field' => 'ced_order_monetary',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 3,
                    'operator' => '='
                ]
            ]
        ];

        $segments['ced-low-spenders'] = [
            'name' => 'Low Spenders',
            'alias' => 'ced-low-spenders',
            'description' => 'Customers who have spend least on my store till now and have low Monetary rating than
            other customers.',
            'isPublished' => 1,
            'filters' => [
                [
                    'glue' => 'and',
                    'field' => 'ced_order_monetary',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 1,
                    'operator' => '='
                ]
            ]
        ];

        $segments['ced-loyal-customers'] = [
            'name' => 'Loyal Customers',
            'alias' => 'ced-loyal-customers',
            'description' => 'Customers who bought most recently and most frequently.',
            'isPublished' => 1,
            'filters' => [
                [
                    'glue' => 'and',
                    'field' => 'ced_order_frequency',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 5,
                    'operator' => '='
                ],
                [
                    'glue' => 'and',
                    'field' => 'ced_order_recency',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 5,
                    'operator' => '='
                ]
            ]
        ];

        $segments['ced-lost-customers'] = [
            'name' => 'Lost Customers',
            'alias' => 'ced-lost-customers',
            'description' => 'Customers who last purchased long ago but purchased frequently and spend the most.',
            'isPublished' => 1,
            'filters' => [
                [
                    'glue' => 'and',
                    'field' => 'ced_order_recency',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 1,
                    'operator' => '='
                ],
                [
                    'glue' => 'and',
                    'field' => 'ced_order_frequency',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 5,
                    'operator' => '='
                ],
                [
                    'glue' => 'and',
                    'field' => 'ced_order_monetary',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 5,
                    'operator' => '='
                ]
            ]
        ];

        $segments['ced-lost-cheap-customers'] = [
            'name' => 'Lost Cheap Customers',
            'alias' => 'ced-lost-cheap-customers',
            'description' => 'Customers who purchased last long ago, purchased little and spend the least.',
            'isPublished' => 1,
            'filters' => [
                [
                    'glue' => 'and',
                    'field' => 'ced_order_recency',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 1,
                    'operator' => '='
                ],
                [
                    'glue' => 'and',
                    'field' => 'ced_order_frequency',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 1,
                    'operator' => '='
                ],
                [
                    'glue' => 'and',
                    'field' => 'ced_order_monetary',
                    'object' => 'lead',
                    'type' => 'select',
                    'filter' => 1,
                    'operator' => '='
                ]
            ]
        ];

        $segments['ced-one-time-purchase'] = [
            'name' => 'One Time Purchase Customers',
            'alias' => 'ced-one-time-purchase',
            'description' => 'Segment to capture all customers who have purchased only one time from my store.',
            'isPublished' => 1,
            'filters' => [
                [
                    'glue' => 'and',
                    'field' => 'ced_total_orders',
                    'object' => 'lead',
                    'type' => 'number',
                    'filter' => 1,
                    'operator' => '='
                ]
            ]
        ];

        $segments['ced-two-time-purchase'] = [
            'name' => 'Two Time Purchase Customers',
            'alias' => 'ced-two-time-purchase',
            'description' => 'Segment to capture all customers who have purchased only twice from my store.',
            'isPublished' => 1,
            'filters' => [
                [
                    'glue' => 'and',
                    'field' => 'ced_total_orders',
                    'object' => 'lead',
                    'type' => 'number',
                    'filter' => 2,
                    'operator' => '='
                ]
            ]
        ];

        $segments['ced-three-time-purchase'] = [
            'name' => 'Three Time Purchase Customers',
            'alias' => 'ced-three-time-purchase',
            'description' => 'Segment to capture all customers who have purchased only three times from my store.',
            'isPublished' => 1,
            'filters' => [
                [
                    'glue' => 'and',
                    'field' => 'ced_total_orders',
                    'object' => 'lead',
                    'type' => 'number',
                    'filter' => 3,
                    'operator' => '='
                ]
            ]
        ];

        $segments['ced-having-feedback-html'] = [
            'name' => 'Customers having Feedback Html',
            'alias' => 'ced-having-feedback-html',
            'description' => 'Segment to capture all customers who have feedback html in their details.',
            'isPublished' => 1,
            'filters' => [
                [
                    'glue' => 'and',
                    'field' => 'ced_feedback_html',
                    'object' => 'lead',
                    'operator' => '!empty'
                ],
                [
                    'glue' => 'and',
                    'field' => 'ced_last_comp_order_date',
                    'object' => 'lead',
                    'type' => 'date',
                    'filter' => '-2 days',
                    'operator' => 'lte'
                ]
            ]
        ];

        return $segments;
    }

    /**
     * @return array
     */
    public function getRfmRating()
    {
        $rating = [];
        $rating[] = ['label' => '5', 'value' => 5];
        $rating[] = ['label' => '4', 'value' => 4];
        $rating[] = ['label' => '3', 'value' => 3];
        $rating[] = ['label' => '2', 'value' => 2];
        $rating[] = ['label' => '1', 'value' => 1];
        $rating[] = ['label' => 'Nil', 'value' => 'null'];

        return $rating;
    }

    /**
     * @return array
     */
    public function getUserMarketingAction()
    {
        $user_actions = [];
        $user_actions[] = ['label' => 'Yes', 'value' => 'yes'];
        $user_actions[] = ['label' => 'No', 'value' => 'no'];

        return $user_actions;
    }

    /**
     * @return array
     */
    public static function getAbandonedCartStatus()
    {
        $cart_status = [];
        $cart_status[] = ['label' => 'Yes', 'value' => 'yes'];
        $cart_status[] = ['label' => 'No', 'value' => 'no'];

        return $cart_status;
    }
}
