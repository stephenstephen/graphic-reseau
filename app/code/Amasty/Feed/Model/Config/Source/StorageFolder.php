<?php

namespace Amasty\Feed\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class FilePath
 */
class StorageFolder implements ArrayInterface
{
    const MEDIA_FOLDER = 'media';
    const VAR_FOLDER = 'var';

    public function toOptionArray()
    {
        return [
            ['value' => self::MEDIA_FOLDER, 'label' => __('Use \'pub/media\' folder')],
            ['value' => self::VAR_FOLDER, 'label' => __('Use \'var\' folder')]
        ];
    }
}
