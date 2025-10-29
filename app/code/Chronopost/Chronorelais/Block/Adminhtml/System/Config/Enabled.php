<?php
/**
 * Chronopost
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category  Chronopost
 * @package   Chronopost_Chronorelais
 * @copyright Copyright (c) 2021 Chronopost
 */
declare(strict_types=1);

namespace Chronopost\Chronorelais\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Chronopost\Chronorelais\Helper\Webservice;

/**
 * Class Enabled
 *
 * @package Chronopost\Chronorelais\Block\Adminhtml\System\Config
 */
class Enabled extends Field
{

    /**
     * @var Webservice
     */
    protected $helperWS;

    /**
     * Enabled constructor.
     *
     * @param Context    $context
     * @param Webservice $helperWS
     * @param array      $data
     */
    public function __construct(
        Context $context,
        Webservice $helperWS,
        array $data = []
    ) {
        $this->helperWS = $helperWS;
        parent::__construct($context, $data);
    }

    /**
     * Return element html
     *
     * @param  AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $id = $element->getId();
        $carrier = explode('_', $id);
        $carrier = $carrier[1];

        if(!$this->helperWS->shippingMethodEnabled($carrier)) {
            $element->setDisabled('disabled');
            $element->setValue(0);
        }

        return parent::_getElementHtml($element).$this->_toHtml();
    }
}
