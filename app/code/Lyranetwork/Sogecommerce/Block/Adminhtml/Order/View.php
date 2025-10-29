<?php
/**
 * Copyright © Lyra Network.
 * This file is part of Sogecommerce plugin for Magento 2. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
namespace Lyranetwork\Sogecommerce\Block\Adminhtml\Order;

use Magento\Sales\Block\Adminhtml\Order\View as OrderView;

class View
{
    public function beforeSetLayout(OrderView $orderView)
    {
        $order = $orderView->getOrder();

        if (! $order) {
            return;
        }

        switch ($order->getStatus()) {
            case 'sogecommerce_to_validate':
                $message = __('Are you sure you want to validate this order in Sogecommerce gateway?');
                $orderView->addButton(
                    'sogecommerce_validate_payment',
                    [
                        'label' => __('Validate payment'),
                        'onclick' => "confirmSetLocation('{$message}', '{$orderView->getUrl('sogecommerce/payment/validate')}')",
                        'class' =>'go'
                    ]
                );

            // Break omitted intentionally.
            case 'payment_review':
                $orderView->removeButton('accept_payment');
                break;

            default:
                break;
        }
    }
}
