<?php

namespace Amasty\Feed\Block\Adminhtml\Feed\Edit\Tab\Xml;

/**
 * Class Content
 *
 * @package Amasty\Feed
 */
class Content extends \Amasty\Feed\Block\Adminhtml\Feed\Edit\Tab\Content
{
    protected $_template = 'feed/xml.phtml';

    protected function _prepareLayout()
    {
        $button = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            [
                'label' => __('Insert'),
                'id' => 'insert_button',
                'class' => 'add'
            ]
        );

        $this->setChild('insert_button', $button);

        $button = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            [
                'label' => __('Update'),
                'id' => 'update_button',
                'class' => 'add hidden'
            ]
        );

        $this->setChild('update_button', $button);

        return parent::_prepareLayout();
    }

    public function getInsertButtonHtml()
    {
        return $this->getChildHtml('insert_button');
    }

    public function getUpdateButtonHtml()
    {
        return $this->getChildHtml('update_button');
    }
    
    public function escapeHtmlInContent($value)
    {
        // phpcs:ignore
        $html = htmlspecialchars($value, ENT_NOQUOTES, 'UTF-8');
        
        return $this->escapeHtml($html);
    }
}
