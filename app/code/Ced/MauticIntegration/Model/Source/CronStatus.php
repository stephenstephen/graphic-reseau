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

namespace Ced\MauticIntegration\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Cron\Model\Schedule;

/**
 * Class CronStatus
 * @package Ced\HubIntegration\Model\Source
 */
class CronStatus implements OptionSourceInterface
{

    /**
     * @var array
     */
    public $_options = [];

    /**
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['value' => Schedule::STATUS_PENDING, 'label' => __('Pending') ],
            ['value' => Schedule::STATUS_RUNNING, 'label' => __('Running') ],
            ['value' => Schedule::STATUS_ERROR, 'label' => __('Error') ],
            ['value' => Schedule::STATUS_MISSED, 'label' => __('Missed') ],
            ['value' => Schedule::STATUS_SUCCESS, 'label' => __('Success') ]
        ];
        return $this->_options;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    /**
     * @param $optionId
     * @return mixed|null
     */
    public function getOptionText($optionId)
    {
        $options = $this->getOptionArray();
        return isset($options[$optionId]) ? $options[$optionId] : null;
    }

    /**
     * @return array
     */
    public function getOptionArray()
    {
        $options = [];
        foreach ($this->getAllOptions() as $option) {
            $options[$option['value']] = (string)$option['label'];
        }
        return $options;
    }
}