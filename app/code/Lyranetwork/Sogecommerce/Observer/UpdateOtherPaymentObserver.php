<?php
/**
 * Copyright © Lyra Network.
 * This file is part of Sogecommerce plugin for Magento 2. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
namespace Lyranetwork\Sogecommerce\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class UpdateOtherPaymentObserver implements ObserverInterface
{
    /**
     * Update payment method ID to set payment means if other payment means method.
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $payment = $observer->getDataObject();

        if ($payment->getMethod() !== 'sogecommerce_other') {
            // Not sogecommerce other payment, do nothing.
            return $this;
        }

        // Retreive selected option.
        $option = @unserialize($payment->getAdditionalInformation(\Lyranetwork\Sogecommerce\Helper\Payment::OTHER_OPTION));
        if (is_array($option) && ! empty($option)) {
            $payment->setMethod('sogecommerce_other_' . $option['means']);
        }

        return $this;
    }
}
