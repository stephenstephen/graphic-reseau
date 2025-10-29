<?php

namespace Amasty\Feed\Block\Adminhtml\Field\Edit\Button;

/**
 * Class Reset
 *
 * @package Amasty\Feed
 */
class Reset extends Generic
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [
            'label' => __('Reset'),
            'class' => 'reset',
            'on_click' => 'location.reload();',
            'sort_order' => 30,
        ];
    }
}
