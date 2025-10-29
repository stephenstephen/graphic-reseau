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

/**
 * Class RequestType
 * @package Ced\MauticIntegration\Model\Source
 */
class RequestType implements OptionSourceInterface
{
    const REQUEST_TYPE_GET = "GET";
    const REQUEST_TYPE_POST = "POST";
    const REQUEST_TYPE_EXCEPTION = "EXCEPTION";

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
            ['value' => "", 'label' => __('Please Select') ],
            ['value' => self::REQUEST_TYPE_GET, 'label' => __(self::REQUEST_TYPE_GET) ],
            ['value' => self::REQUEST_TYPE_POST, 'label' => __(self::REQUEST_TYPE_POST) ],
            ['value' => self::REQUEST_TYPE_EXCEPTION, 'label' => __(self::REQUEST_TYPE_EXCEPTION) ]
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