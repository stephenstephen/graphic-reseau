<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Block\Adminhtml\System\Config;

use Colissimo\Shipping\Helper\Data as ShippingHelper;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Widget\Button;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Test
 */
class Test extends Field
{
    /**
     * @var ShippingHelper $shippingHelper
     */
    protected $shippingHelper;

    /**
     * @param Context $context
     * @param ShippingHelper $shippingHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        ShippingHelper $shippingHelper,
        array $data = []
    ) {
        $this->shippingHelper = $shippingHelper;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve element HTML markup
     *
     * @param AbstractElement $element
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @phpcs:disable
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        /** @var \Magento\Backend\Block\Widget\Button $buttonBlock  */
        $buttonBlock = $this->getForm()->getLayout()->createBlock(Button::class);

        $website = $buttonBlock->getRequest()->getParam('website');
        $store   = $buttonBlock->getRequest()->getParam('store');

        $params = [
            'website' => $website,
            'store'   => $store
        ];

        $data = [
            'label' => $this->getLabel(),
            'onclick' => "setLocation('" . $this->getTestUrl($params) . "')",
        ];

        $apiConfig = $this->shippingHelper->getApiConfig($store, $website);

        if (!$apiConfig['login'] || !$apiConfig['password']) {
            $data['disabled'] = true;
        }

        $html = $buttonBlock->setData($data)->toHtml();
        return $html;
    }

    /**
     * Retrieve button label
     *
     * @return \Magento\Framework\Phrase
     */
    private function getLabel()
    {
        return  __('Test');
    }

    /**
     * Retrieve Button URL
     *
     * @param array
     * @return string
     */
    public function getTestUrl($params = [])
    {
        return $this->getUrl('colissimo_shipping/test', $params);
    }
}
