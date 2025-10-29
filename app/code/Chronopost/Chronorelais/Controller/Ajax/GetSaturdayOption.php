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

namespace Chronopost\Chronorelais\Controller\Ajax;

use Chronopost\Chronorelais\Helper\Data;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\LayoutFactory;
use Magento\Shipping\Model\CarrierFactory;

/**
 * Class GetSaturdayOption
 *
 * @package Chronopost\Chronorelais\Controller\Ajax
 */
class GetSaturdayOption extends Action
{

    /**
     * @var CarrierFactory
     */
    private $carrierFactory;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var LayoutFactory
     */
    private $layoutFactory;

    /**
     * @var PriceCurrencyInterface
     */
    private $currency;

    /**
     * GetSaturdayOption constructor.
     *
     * @param Context                $context
     * @param CarrierFactory         $carrierFactory
     * @param ScopeConfigInterface   $scopeConfig
     * @param Data                   $helper
     * @param JsonFactory            $jsonFactory
     * @param LayoutFactory          $layoutFactory
     * @param PriceCurrencyInterface $currency
     */
    public function __construct(
        Context $context,
        CarrierFactory $carrierFactory,
        ScopeConfigInterface $scopeConfig,
        Data $helper,
        JsonFactory $jsonFactory,
        LayoutFactory $layoutFactory,
        PriceCurrencyInterface $currency
    ) {
        parent::__construct($context);
        $this->carrierFactory = $carrierFactory;
        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
        $this->jsonFactory = $jsonFactory;
        $this->layoutFactory = $layoutFactory;
        $this->currency = $currency;
    }

    /**
     * Display saturday options
     *
     * @return ResponseInterface|Json|ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $result = $this->jsonFactory->create();
        $shippingMethodCode = $this->getRequest()->getParam('method_code');
        $carrier = $this->carrierFactory->get($shippingMethodCode);
        $shippingDeliverOnSaturday = $carrier->getConfigData('deliver_on_saturday');
        $customerChoiceEnabled = $this->scopeConfig->getValue('chronorelais/saturday/display_to_customer');
        $isSendingDay = $this->helper->isSendingDay();
        $saturdayAmount = $this->scopeConfig->getValue('chronorelais/saturday/amount');
        $currencySymbol = $this->currency->getCurrencySymbol();

        $resultData = [];
        if ($customerChoiceEnabled && $isSendingDay && $shippingDeliverOnSaturday) {
            $layout = $this->layoutFactory->create();
            $content = $layout->createBlock('Magento\Framework\View\Element\Template')
                ->setMethodCode($shippingMethodCode)
                ->setSaturdayAmount($saturdayAmount)
                ->setCurrencySymbol($currencySymbol)
                ->setTemplate('Chronopost_Chronorelais::saturday_option.phtml')
                ->toHtml();

            $resultData['method_code'] = $shippingMethodCode;
            $resultData['content'] = $content;
        }

        $result->setData($resultData);

        return $result;
    }
}
