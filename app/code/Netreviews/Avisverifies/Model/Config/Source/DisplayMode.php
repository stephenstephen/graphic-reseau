<?php
namespace Netreviews\Avisverifies\Model\Config\Source;


class DisplayMode implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @const string
     */
    const PRODUCTION = 'production';

    /**
     * @const string
     */
    const SANDBOX = 'sandbox';
    
    /**
     * Options int
     *
     * @return array
     */
    public function toOptionArray()
    {
        return  [
            ['value' => self::SANDBOX, 'label' => __('Sandbox')],
            ['value' => self::PRODUCTION, 'label' => __('Production')],
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $array = [];
        foreach ($this->toOptionArray() as $item) {
            $array[$item['value']] = $item['label'];
        }
        return $array;
    }
}
