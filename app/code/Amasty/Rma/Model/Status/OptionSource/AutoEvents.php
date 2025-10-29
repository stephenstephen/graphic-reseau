<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Model\Status\OptionSource;

use Magento\Framework\Option\ArrayInterface;

class AutoEvents implements ArrayInterface
{
    const CUSTOMER_ADDED_COMMENT = 1;
    const CUSTOMER_ADDED_TRACKING_NUMBER = 2;
    const CUSTOMER_CANCELED_RMA = 3;
    const CUSTOMER_RATED_RMA = 4;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = [];
        foreach ($this->toArray() as $value => $label) {
            $optionArray[] = ['value' => $value, 'label' => $label];
        }
        return $optionArray;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::CUSTOMER_ADDED_COMMENT => __('Customer Added New Comment'),
            self::CUSTOMER_ADDED_TRACKING_NUMBER => __('Customer Added Tracking Number'),
            self::CUSTOMER_CANCELED_RMA => __('Customer Canceled RMA'),
            self::CUSTOMER_RATED_RMA => __('Customer Rated RMA')
        ];
    }
}
