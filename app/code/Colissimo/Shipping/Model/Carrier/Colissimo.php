<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2020 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Model\Carrier;

use Colissimo\Shipping\Helper\Data as ShippingHelper;
use Colissimo\Shipping\Model\Rate\ResultFactory as RateResultFactory;
use Colissimo\Shipping\Model\Config\Source\Calculation;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory as RateMethodFactory;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Tracking\ResultFactory as TrackingResultFactory;
use Magento\Shipping\Model\Tracking\Result\StatusFactory as ResultStatusFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State as AppState;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Psr\Log\LoggerInterface;
use Zend_Measure_Weight;

/**
 * Class Colissimo
 */
class Colissimo extends AbstractCarrier implements CarrierInterface
{
    const SHIPPING_CARRIER_CODE = 'colissimo';

    const SHIPPING_CARRIER_PICKUP_METHOD = 'colissimo_pickup';

    const REQUEST_MAX_PRODUCT_WEIGHT_ATTRIBUTE = 'package_max_product_weight';

    const QUOTE_COLISSIMO_PASS_ID_CONTRACT = 'colissimo_pass_id_contract';

    /**
     * @var string $_code
     * @phpcs:disable
     */
    protected $_code = 'colissimo';

    /**
     * @var bool $isFixed
     */
    protected $isFixed = true;

    /**
     * @var bool $isColissimoPass
     * @deprecated Colissimo Pass is an abandoned offer
     */
    protected $isColissimoPass = false;

    /**
     * @var RateResultFactory $rateResultFactory
     */
    protected $rateResultFactory;

    /**
     * @var RateMethodFactory $rateMethodFactory
     */
    protected $rateMethodFactory;

    /**
     * @var ShippingHelper $shippingHelper
     */
    protected $shippingHelper;

    /**
     * @var TrackingResultFactory $trackFactory
     */
    protected $trackFactory;

    /**
     * @var ResultStatusFactory $trackStatusFactory
     */
    protected $trackStatusFactory;

    /**
     * @var AppState $appState
     */
    protected $appState;

    /**
     * @var ManagerInterface $eventManager
     */
    protected $eventManager;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param RateResultFactory $rateResultFactory
     * @param RateMethodFactory $rateMethodFactory
     * @param ShippingHelper $shippingHelper
     * @param TrackingResultFactory $trackFactory
     * @param ResultStatusFactory $trackStatusFactory
     * @param ManagerInterface $eventManager
     * @param AppState $appState
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        RateResultFactory $rateResultFactory,
        RateMethodFactory $rateMethodFactory,
        ShippingHelper $shippingHelper,
        TrackingResultFactory $trackFactory,
        ResultStatusFactory $trackStatusFactory,
        ManagerInterface $eventManager,
        AppState $appState,
        array $data = []
    ) {
        $this->rateResultFactory  = $rateResultFactory;
        $this->rateMethodFactory  = $rateMethodFactory;
        $this->shippingHelper     = $shippingHelper;
        $this->trackFactory       = $trackFactory;
        $this->trackStatusFactory = $trackStatusFactory;
        $this->appState           = $appState;
        $this->eventManager       = $eventManager;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * @param RateRequest $request
     * @return Result|bool
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        if ($request->getData('is_return')) {
            return $this->addReturnMethod($request);
        }

        $this->addRequestAttributes($request);

        $methods = array_keys($this->getAllowedMethods());

        $quote = $this->getQuote($request);

        if ($quote) {
            $payment = $quote->getPayment();

            $review  = $payment->getAdditionalInformation('button');
            $payerId = $payment->getAdditionalInformation('paypal_express_checkout_payer_id');

            if ($payerId && $review) {
                $methods = array_diff($methods, ['pickup']);
            }

            if ($quote->getIsMultiShipping()) {
                $methods = array_diff($methods, ['pickup']);
            }

            if ($quote->getData(self::QUOTE_COLISSIMO_PASS_ID_CONTRACT)) {
                $this->isColissimoPass = true;
            }
        }

        return $this->addMethods($request, $methods);
    }

    /**
     * @param RateRequest $request
     * @param array $methodCodes
     * @return Result
     */
    protected function addMethods(RateRequest $request, $methodCodes)
    {
        /** @var Result $result */
        $result = $this->rateResultFactory->create();

        foreach ($methodCodes as $methodCode) {
            /* Check if method is active */
            if (!$this->getConfigData($methodCode . '/active')) {
                continue;
            }

            $countryId = $this->shippingHelper->getCountry($request->getDestCountryId(), $request->getDestPostcode());

            /* Check if country is active */
            $specific = $this->getConfigData($methodCode . '/specificcountry');
            if (!$specific) {
                continue;
            }
            $countries = explode(',', $specific);
            if (!in_array($countryId, $countries)) {
                continue;
            }

            /* Check Weight */
            $weight = $request->getPackageWeight();
            $calculation = $this->shippingHelper->getWeightCalculation($request->getStoreId());
            if ($calculation === Calculation::COLISSIMO_CALCULATION_PRODUCT) {
                $weight = $request->getData(self::REQUEST_MAX_PRODUCT_WEIGHT_ATTRIBUTE);
            }

            $weightUnit = $this->shippingHelper->getStoreWeightUnit($request->getStoreId());
            $shippingWeight = $this->shippingHelper->convertWeight(
                $weight,
                $weightUnit,
                Zend_Measure_Weight::KILOGRAM
            );
            $maxWeight = $this->shippingHelper->getConfig($methodCode . '/country/' . $countryId . '/max_weight');

            if ($shippingWeight && $shippingWeight >= $maxWeight) {
                continue;
            }

            /* Retrieve Price */
            $price = 0;
            if ($request->getFreeShipping() !== true && $this->isColissimoPass === false) {
                $shippingWeight = $this->shippingHelper->convertWeight(
                    $request->getPackageWeight(),
                    $weightUnit,
                    Zend_Measure_Weight::KILOGRAM
                );

                $price = $this->shippingHelper->getShippingPrice(
                    $methodCode,
                    $countryId,
                    $shippingWeight,
                    $request->getStoreId()
                );

                if ($this->getConfigFlag('price_security') && $price === null) {
                    continue;
                }
            }

            /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
            $method = $this->rateMethodFactory->create();

            $method->setCarrier($this->_code);
            $method->setCarrierTitle($this->getConfigData('title'));

            $method->setMethod($methodCode);
            $method->setMethodTitle($this->getConfigData($methodCode . '/name'));
            $method->setMethodSortOrder((int)$this->getConfigData($methodCode . '/sort_order'));

            $method->setPrice($price);
            $method->setCost($price);

            $this->eventManager->dispatch(
                'colissimo_append_method',
                ['method' => $method, 'request' => $request]
            );

            if ($method->getHideMethod()) {
                continue;
            }

            $result->append($method);
        }

        return $result;
    }

    /**
     * Add return method if return requested
     *
     * @param RateRequest $request
     * @return Result
     */
    protected function addReturnMethod(RateRequest $request)
    {
        /** @var Result $result */
        $result = $this->rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle('Colissimo');

        $method->setMethod('return');
        $method->setMethodTitle(__('Return'));

        $this->eventManager->dispatch(
            'colissimo_append_method',
            ['method' => $method, 'request' => $request]
        );

        $result->append($method);

        return $result;
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        $methods = [
            'homecl'        => $this->getConfigData('homecl/sort_order'),
            'homesi'        => $this->getConfigData('homesi/sort_order'),
            'international' => $this->getConfigData('international/sort_order'),
            'domtomcl'      => $this->getConfigData('domtomcl/sort_order'),
            'domtomsi'      => $this->getConfigData('domtomsi/sort_order'),
            'domtomeco'     => $this->getConfigData('domtomeco/sort_order'),
            'pickup'        => $this->getConfigData('pickup/sort_order'),
        ];

        natsort($methods);

        foreach ($methods as $key => $value) {
            $methods[$key] = $this->getConfigData($key . '/name');
        }

        return $methods;
    }

    /**
     * Check if carrier has shipping tracking option available
     *
     * @return boolean
     */
    public function isTrackingAvailable()
    {
        return true;
    }

    /**
     * Get tracking information
     *
     * @param string $tracking
     * @return string|false
     * @api
     */
    public function getTrackingInfo($tracking)
    {
        $result = $this->getTracking($tracking);

        if ($result instanceof \Magento\Shipping\Model\Tracking\Result) {
            $trackings = $result->getAllTrackings();
            if ($trackings) {
                return $trackings[0];
            }
        } elseif (is_string($result) && !empty($result)) {
            return $result;
        }

        return false;
    }

    /**
     * Get tracking
     *
     * @param string|string[] $trackings
     * @return \Magento\Shipping\Model\Tracking\Result
     */
    public function getTracking($trackings)
    {
        if (!is_array($trackings)) {
            $trackings = [$trackings];
        }

        $result = $this->trackFactory->create();

        foreach ($trackings as $tracking) {
            /** @var \Magento\Shipping\Model\Tracking\Result\Status $status */
            $status = $this->trackStatusFactory->create();
            $status->setCarrier($this->_code);
            $status->setCarrierTitle($this->getConfigData('title'));
            $status->setTracking($tracking);
            $status->setPopup(1);
            $status->setUrl("https://www.laposte.fr/outils/suivre-vos-envois?code={$tracking}");

            $result->append($status);
        }

        return $result;
    }

    /**
     * Add additional attributes to request
     *
     * @param RateRequest $request
     * @return void
     */
    protected function addRequestAttributes(RateRequest $request)
    {
        $maxProductWeight = 0;

        /** @var \Magento\Quote\Model\Quote\Item $item */
        foreach ($request->getAllItems() as $item) {
            if ($item->getWeight() > $maxProductWeight) {
                $maxProductWeight = $item->getWeight();
            }
        }

        $request->setData(self::REQUEST_MAX_PRODUCT_WEIGHT_ATTRIBUTE, $maxProductWeight);
    }

    /**
     * Retrieve current quote
     *
     * @param RateRequest $request
     *
     * @return bool|\Magento\Quote\Model\Quote
     */
    protected function getQuote(RateRequest $request)
    {
        $items = $request->getAllItems();

        if (!isset($items[0])) {
            return false;
        }

        /** @var \Magento\Quote\Model\Quote\Item $item */
        $item = $items[0];

        return $item->getQuote();
    }

    /**
     * @param DataObject $request
     * @return $this
     */
    public function checkAvailableShipCountries(DataObject $request)
    {
        return $this;
    }

    /**
     * Check whether girth is allowed for the carrier
     *
     * @param null|string $countyDest
     * @param null|string $carrierMethodCode
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @api
     */
    public function isGirthAllowed($countyDest = null, $carrierMethodCode = null)
    {
        return false;
    }
}
