<?php
/**
 * Copyright © Lyra Network.
 * This file is part of Systempay plugin for Magento 2. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
namespace Lyranetwork\Systempay\Model;

class MultiConfigProvider extends \Lyranetwork\Systempay\Model\SystempayConfigProvider
{
    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Lyranetwork\Systempay\Helper\Data $dataHelper
     * @param string $methodCode
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Lyranetwork\Systempay\Helper\Data $dataHelper
    ) {
        parent::__construct(
            $storeManager,
            $urlBuilder,
            $dataHelper,
            \Lyranetwork\Systempay\Helper\Data::METHOD_MULTI
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $config = parent::getConfig();
        $config['payment'][$this->method->getCode()]['availableOptions'] = $this->getAvailableOptions();

        return $config;
    }

    private function getAvailableOptions()
    {
        $quote = $this->dataHelper->getCheckoutQuote();
        $amount = ($quote && $quote->getId()) ? $quote->getBaseGrandTotal() : null;

        $options = [];
        foreach ($this->method->getAvailableOptions($amount) as $key => $option) {
            $options[] = [
                'key' => $key,
                'label' => $option['label']
            ];
        }

        return $options;
    }
}
