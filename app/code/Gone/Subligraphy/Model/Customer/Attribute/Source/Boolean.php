<?php

namespace Gone\Subligraphy\Model\Customer\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Boolean extends AbstractSource
{
    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['label' => __('No'), 'value' => 0],
                ['label' => __('Yes'), 'value' => 1],
            ];
        }
        return $this->_options;
    }
}
