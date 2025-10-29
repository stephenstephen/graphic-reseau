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

namespace Ced\MauticIntegration\Block\Adminhtml\System\Config\Form\Field;

class FieldsMapping extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * @var
     */
    public $customerFieldsRenderer;
    public $mauticFieldsRenderer;

    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    public $elementFactory;

    /**
     * @var \Magento\Framework\View\Design\Theme\LabelFactory
     */
    public $labelFactory;

    /**
     * FieldsMapping constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param \Magento\Framework\View\Design\Theme\LabelFactory $labelFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        \Magento\Framework\View\Design\Theme\LabelFactory $labelFactory,
        array $data = []
    ) {
        $this->elementFactory = $elementFactory;
        $this->labelFactory = $labelFactory;
        parent::__construct($context, $data);
    }

    /**
     * @throws \Exception
     */
    public function _prepareToRender()
    {
        $this->addColumn('mautic_fields', [
            'label' => __('Mautic Contact Fields'),
            'renderer' => $this->getMauticContactFieldsRenderer()
        ]);
        $this->addColumn('magento_fields', [
            'label' => __('Magento Customer Fields'),
            'renderer' => $this->getCustomerFieldsRenderer()
        ]);
        $this->_addAfter = false;
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomerFieldsRenderer()
    {
        if (!$this->customerFieldsRenderer) {
            $this->customerFieldsRenderer = $this->getLayout()->createBlock(
                CustomerFields::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->customerFieldsRenderer;
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMauticContactFieldsRenderer()
    {
        if (!$this->mauticFieldsRenderer) {
            $this->mauticFieldsRenderer = $this->getLayout()->createBlock(
                MauticContactFields::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->mauticFieldsRenderer;
    }

    /**
     * @param \Magento\Framework\DataObject $row
     */
    public function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $mauticField = $row->getData('mautic_fields');
        $options = [];
        $options['option_' . $this->getMauticContactFieldsRenderer()->calcOptionHash($mauticField)]
            = 'selected="selected"';
        $magentoField = $row->getData('magento_fields');

        $options['option_' . $this->getCustomerFieldsRenderer()->calcOptionHash($magentoField)]
            = 'selected="selected"';
        $row->setData('option_extra_attrs', $options);
    }
}
