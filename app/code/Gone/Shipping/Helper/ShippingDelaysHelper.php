<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Shipping\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class ShippingDelaysHelper extends AbstractHelper
{

    const SHIPPING_DELAYS = [
        //carrier => days
        "colissimo" => 3,
        "chronopost" => 2,
        "chronorelais" => 1
    ];

    protected Date $_dateHelper;

    public function __construct(
        Context $context,
        Date $dateHelper
    ) {
        parent::__construct($context);
        $this->_dateHelper = $dateHelper;
    }

    /**
     * @param \Magento\Quote\Api\Data\ShippingMethodInterface $shippingMethod
     * @param $quoteMaxDelay
     * @param string $outputDateFormat
     * @return string
     */
    public function getShippingDelay($shippingMethod, $quoteMaxDelay, string $outputDateFormat)
    {
        $carrierCode = $shippingMethod->getCarrierCode();
        $methodFullCode = $carrierCode . '_' . $shippingMethod->getMethodCode();

        $delay = 0;

        if (strpos($methodFullCode, 'owsh1') !== false) {
            $delay = $shippingMethod->getExtensionAttributes()->getDeliveryDelay();
        } elseif (array_key_exists($carrierCode, self::SHIPPING_DELAYS)) {
            $delay = self::SHIPPING_DELAYS[$carrierCode];
        } else {
            return __('information not available');
        }

        if (!empty($quoteMaxDelay)) {
            $delay += $quoteMaxDelay;
        }

        return $this->_dateHelper->getShippingDate($delay, $outputDateFormat);
    }
}
