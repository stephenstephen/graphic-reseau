<?php
namespace Netreviews\Avisverifies\Model\Config\Source;

class customSelectIdOrSku implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'sku', 'label' => __('SKU')],
            ['value' => 'id', 'label' => __('ID')]
        ];
    }
}
