<?php
/**
 * Copyright © Lyra Network.
 * This file is part of Systempay plugin for Magento 2. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
namespace Lyranetwork\Systempay\Block\Payment\Form;

abstract class Systempay extends \Magento\Payment\Block\Form
{
    /**
     * @var \Lyranetwork\Systempay\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Lyranetwork\Systempay\Helper\Data $dataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Lyranetwork\Systempay\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->dataHelper = $dataHelper;

        parent::__construct($context, $data);
    }

    private function checkAndGetLogoUrl($fileName)
    {
        if (! $fileName) {
            return false;
        }

        return $this->dataHelper->getLogoImageSrc($fileName);
    }

    public function getConfigData($name)
    {
        return $this->getMethod()->getConfigData($name);
    }

    /**
     * Render block HTML.
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (! $this->dataHelper->isBackend()) {
            $logoURL = $this->checkAndGetLogoUrl($this->getConfigData('module_logo'));

            if ($logoURL) {
                /** @var $logo \Magento\Framework\View\Element\Template */
                $logo = $this->_layout->createBlock(\Magento\Framework\View\Element\Template::class);
                $logo->setTemplate('Lyranetwork_Systempay::payment/logo.phtml');
                $logo->setLogoUrl($logoURL);

                // Add logo to the method title.
                $this->setMethodLabelAfterHtml($logo->toHtml());
            }
        }

        return parent::_toHtml();
    }

    public function getCcTypeImageSrc($card)
    {
        return $this->dataHelper->getCcTypeImageSrc($card);
    }

    public function getCurrentCustomer()
    {
        return $this->getMethod()->getCurrentCustomer();
    }
}
