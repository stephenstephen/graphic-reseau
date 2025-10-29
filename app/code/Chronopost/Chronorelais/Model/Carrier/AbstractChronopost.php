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

namespace Chronopost\Chronorelais\Model\Carrier;

use Chronopost\Chronorelais\Helper\Data;
use Chronopost\Chronorelais\Helper\Webservice as HelperWebservice;
use Exception;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Shipping\Model\Tracking\Result\StatusFactory;
use Magento\Shipping\Model\Tracking\ResultFactory as TrackingResultFactory;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractChronopost
 *
 * @package Chronopost\Chronorelais\Model\Carrier
 * @SuppressWarnings("CouplingBetweenObjects")
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
abstract class AbstractChronopost extends AbstractCarrier implements CarrierInterface
{
    const CHECK_RELAI_WS = false;
    const CHECK_CONTRACT = false;
    const PRODUCT_CODE = '';
    const PRODUCT_CODE_STR = '';
    const OPTION_BAL_ENABLE = false;
    const PRODUCT_CODE_BAL = '';
    const PRODUCT_CODE_BAL_STR = '';
    const DELIVER_ON_SATURDAY = false;

    protected $debugData = [];

    /**
     * @var HelperWebservice
     */
    protected $helperWebservice;

    /**
     * @var $helperData
     */
    protected $helperData;

    /**
     * @var ResultFactory
     */
    protected $rateResultFactory;

    /**
     * @var MethodFactory
     */
    protected $rateMethodFactory;

    /**
     * @var TrackingResultFactory
     */
    protected $trackFactory;

    /**
     * @var StatusFactory
     */
    protected $trackStatusFactory;
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;
    /**
     * @var SerializerInterface
     */
    private $jsonSerializer;

    /**
     * Chronopost constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param HelperWebservice $helperWebservice
     * @param TrackingResultFactory $trackFactory
     * @param StatusFactory $trackStatusFactory
     * @param Data $helperData
     * @param SerializerInterface $jsonSerializer
     * @param CheckoutSession $checkoutSession
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        ScopeConfigInterface  $scopeConfig,
        ErrorFactory          $rateErrorFactory,
        LoggerInterface       $logger,
        ResultFactory         $rateResultFactory,
        MethodFactory         $rateMethodFactory,
        HelperWebservice      $helperWebservice,
        TrackingResultFactory $trackFactory,
        StatusFactory         $trackStatusFactory,
        Data                  $helperData,
        SerializerInterface   $jsonSerializer,
        CheckoutSession       $checkoutSession,
        array                 $data = []
    )
    {
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->helperWebservice = $helperWebservice;
        $this->trackFactory = $trackFactory;
        $this->trackStatusFactory = $trackStatusFactory;
        $this->helperData = $helperData;
        $this->jsonSerializer = $jsonSerializer;
        $this->checkoutSession = $checkoutSession;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * Check if carrier has shipping tracking option available
     *
     * @return bool
     */
    public function isTrackingAvailable()
    {
        return true;
    }

    /**
     * Get tracking informations
     *
     * @param $tracking
     *
     * @return mixed
     */
    public function getTrackingInfo($tracking)
    {
        $tracking_url = $this->_scopeConfig->getValue('chronorelais/shipping/tracking_view_url');
        $tracking_url = str_replace('{tracking_number}', $tracking, $tracking_url);

        $status = $this->trackStatusFactory->create();
        $status->setCarrier($this->_code);
        $status->setCarrierTitle($this->getConfigData('title'));
        $status->setTracking($tracking);
        $status->setPopup(1);
        $status->setUrl($tracking_url);

        return $status;
    }

    /**
     * Get chronopost product code
     *
     * @return string
     */
    public function getChronoProductCodeStr()
    {
        return static::PRODUCT_CODE_STR;
    }

    /**
     * Get chronopost product code to shipment
     *
     * @return string
     */
    public function getChronoProductCodeToShipment()
    {
        if (static::OPTION_BAL_ENABLE && $this->_scopeConfig->getValue("chronorelais/optionbal/enabled")) {
            return static::PRODUCT_CODE_BAL;
        }

        return static::PRODUCT_CODE;
    }

    /**
     * Get chronopost product code (str) to shipment
     *
     * @return string
     */
    public function getChronoProductCodeToShipmentStr()
    {
        if (static::OPTION_BAL_ENABLE && $this->_scopeConfig->getValue("chronorelais/optionbal/enabled")) {
            return static::PRODUCT_CODE_BAL_STR;
        }

        return static::PRODUCT_CODE_STR;
    }

    /**
     * Check if option is enable
     *
     * @return bool
     */
    public function optionBalEnable()
    {
        return static::OPTION_BAL_ENABLE && $this->_scopeConfig->getValue("chronorelais/optionbal/enabled");
    }

    /**
     * @return bool
     */
    public function canDeliverOnSaturday()
    {
        return static::DELIVER_ON_SATURDAY === true;
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return [$this->_code => $this->getConfigData('name')];
    }

    /**
     * Check if chronopost method
     *
     * @return bool
     */
    public function getIsChronoMethod()
    {
        return true;
    }

    /**
     * Collect rates
     *
     * @param RateRequest $request
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @return bool|Result
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $this->debugData = [];
        $this->debugData['request'] = [];
        $this->debugData['error'] = [];
        $this->debugData['request']['code'] = $this->_code;

        /** @var Result $result */
        $result = $this->rateResultFactory->create();

        $cartWeight = $this->checkCartWeight($request);
        $this->debugData['request']['cart_weight'] = $cartWeight;
        if ($cartWeight === false) {
            $this->_debug($this->debugData);

            return false;
        }

        if ($request->getDestPostcode() === null) {
            $this->_debug($this->debugData);

            return false;
        }

        if ($request->getDestCountryId() === 'FR' && $this->_code == 'chronoexpress') {
            $this->_debug($this->debugData);

            return false;
        }

        if ($request->getDestCountryId() !== 'FR'
            && in_array($this->_code, ['chronorelais', 'chronopostc10', 'chronopost', 'chronopostc18'])) {
            $this->_debug($this->debugData);

            return false;
        }

        if (!$this->validateMethod($request)) {
            $this->_debug($this->debugData);

            return false;
        }

        $shippingPrice = $this->getShippingPrice($request, $cartWeight);
        $this->debugData['request']['shipping_price'] = $shippingPrice;
        if ($shippingPrice === false) {
            $this->_debug($this->debugData);

            return false;
        }

        // Application fees
        $applicationFee = $this->getConfigData('application_fee');
        if ($applicationFee) {
            $this->debugData['request']['application_fee'] = $applicationFee;
            $shippingPrice += $applicationFee;
        }

        // Processing fee
        $handlingFee = $this->getConfigData('handling_fee');
        if ($handlingFee) {
            $this->debugData['request']['handling_fee'] = $handlingFee;
            $shippingPrice += $handlingFee;
        }

        $shippingPrice = $this->additionalPrice($shippingPrice);
        $this->debugData['request']['shipping_price_total'] = $shippingPrice;

        // Freeshipping
        $freeShippingEnable = $this->getConfigData('free_shipping_enable');
        $freeShippingSubtotal = $this->getConfigData('free_shipping_subtotal');
        $cartTotal = $request->getBaseSubtotalInclTax();

        $this->debugData['request']['free_shipping_enable'] = (int)$freeShippingEnable;
        $this->debugData['request']['free_shipping_subtotal'] = (int)$freeShippingSubtotal;

        if ($freeShippingEnable && $freeShippingSubtotal <= $cartTotal) {
            $shippingPrice = 0;
        }

        /** @var Method $method */
        $method = $this->rateMethodFactory->create();
        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));
        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getMethodTitle());
        $method->setDescription($this->getConfigData('description'));
        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);
        $result->append($method);

        $this->_debug($this->debugData);

        return $result;
    }

    /**
     * Checks if all product weights are below the weight limit
     *
     * @param RateRequest $request
     *
     * @return bool|float|int
     */
    protected function checkCartWeight(RateRequest $request)
    {
        $weightLimit = $this->getConfigData('weight_limit');
        $weightUnit = $this->_scopeConfig->getValue('chronorelais/weightunit/unit');

        $cartWeight = 0;
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }

                $itemWeight = $item->getWeight();
                if ($weightUnit === 'g') {
                    $itemWeight = $itemWeight / 1000; // conversion g => kg
                }

                if ($itemWeight > $weightLimit) {
                    $this->debugData['error'][] = __('Weight of products greater than the maximum weight');

                    return false;
                }

                $cartWeight += $itemWeight * $item->getQty();
            }
        }

        return $cartWeight;
    }

    /**
     * Additional conditions to show shipping method, each shipping method model might have their own validateMethod
     * function
     *
     * @param RateRequest $request
     *
     * @return bool
     */
    public function validateMethod(RateRequest $request)
    {
        // Test if the webservice is available
        if (static::CHECK_RELAI_WS) {
            $webservice = $this->helperWebservice->getPointsRelaisByCp($request->getDestPostcode());
            if ($webservice === false) {
                $this->debugData['error'][] = "The webservice does not respond";

                return false;
            }
        }

        // Checks if this mode is present in the customer's contract
        if (static::CHECK_CONTRACT) {
            $methodIsAllowed = $this->helperWebservice->getMethodIsAllowed($this->getChronoProductCode(), $request);
            if ($methodIsAllowed === false) {
                $this->debugData['error'][] = sprintf(
                    "Method %s (%s) not present in the contract",
                    $this->_code,
                    $this->getChronoProductCode()
                );

                return false;
            } else {
                $this->debugData['success'][] = sprintf("Method %s present in the contract", $this->_code);
            }
        }

        return true;
    }

    /**
     * Get chronopost product code
     *
     * @return string
     */
    public function getChronoProductCode()
    {
        return static::PRODUCT_CODE;
    }

    /**
     * Get shipping price
     *
     * @param RateRequest $request
     * @param string $cartWeight
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     *
     * @return bool|float
     */
    protected function getShippingPrice(RateRequest $request, $cartWeight)
    {
        $saturdaySupplement = $this->getSaturdaySupplement();
        $corsicaSupplement = (float)$this->_scopeConfig->getValue('chronorelais/tarification/corsica_supplement');

        // Price recovery via WS
        $quickcostEnable = $this->getConfigData('quickcost');
        if ($quickcostEnable) {
            $this->debugData['request']['quickcost'] = 1;
            $quickCostValues = $this->getQuickCostValue($request, $cartWeight);
            if ($quickCostValues && $quickCostValues->return->errorCode === 0) {
                $quickcostValue = (float)$quickCostValues->return->amountTTC;

                // Add margin to quickcost
                if ($quickcostValue !== false) {
                    $this->debugData['request']['quickcost_value'] = $quickcostValue;
                    $quickcostValue = $this->addMarginToQuickcost($quickcostValue);

                    return (float)$quickcostValue + $saturdaySupplement;
                }
            }
        }

        // Recovery of the price via the price list entered by the customer in BO
        $config = trim($this->getConfigData('config'));
        if ($config) {
            try {
                $fees = $this->getFeesConfig($config, $request->getDestCountryId(), $request->getDestPostcode());
                $gridPrices = $this->parseJsonConfig($fees);
                if ($request->getDestCountryId() === 'FR' && $request->getDestPostcode() >= 20000 &&
                    $request->getDestPostcode() < 21000) {

//                    410-Fix-beg
                    $gridPrices = array_map(function ($value) use ($corsicaSupplement) {
                        return $value += $corsicaSupplement;
                    }, $gridPrices);
//                    410-Fix-end
                }

                $shippingPrice = $this->getPriceFromGrid($gridPrices, $cartWeight);
                if ($shippingPrice !== false) {
                    return $shippingPrice + $saturdaySupplement;
                }
            } catch (\Exception $e) {
                return false;
            }
        }

        $this->debugData['error'][] = __('No price found');

        return false;
    }

    /**
     * Get Saturday supplement
     *
     * return float
     */
    private function getSaturdaySupplement()
    {
        if ($this->checkoutSession->getData('chronopost_saturday_option') === '1') {
            return (float)$this->_scopeConfig->getValue('chronorelais/saturday/amount');
        }

        return (float)'0';
    }

    /**
     * Get quick cost value
     *
     * @param RateRequest $request
     * @param float $cartWeight
     *
     * @return bool|Object
     */
    protected function getQuickCostValue(RateRequest $request, $cartWeight)
    {
        $accountNumber = '';
        $accountPassword = '';
        $contract = $this->helperData->getCarrierContract($this->_code);
        if ($contract != null) {
            $accountNumber = $contract['number'];
            $accountPassword = $contract['pass'];
        }

        $productCode = $this->getChronoProductCode();
        $origin_postcode = $this->_scopeConfig->getValue("chronorelais/shipperinformation/zipcode");

        //to get arrival code
        $arrCode = $request->getDestPostcode();
        if ($this->_code == 'chronoexpress' || $this->_code == 'chronocclassic') {
            $arrCode = $request->getDestCountryId();
        }

        $weightUnit = $this->_scopeConfig->getValue("chronorelais/weightunit/unit");
        if ($weightUnit == 'g') {
            $cartWeight = $cartWeight / 1000; // gram to kg
        }
        $wsParams = [
            'accountNumber' => $accountNumber,
            'password' => $accountPassword,
            'depCode' => $origin_postcode,
            'arrCode' => $arrCode,
            'weight' => $cartWeight,
            'productCode' => $productCode,
            'type' => 'M'
        ];

        $this->debugData['request']['quickcost_params'] = $wsParams;
        $quickcostUrl = $this->getConfigData("quickcost_url");

        return $this->helperWebservice->getQuickcost($wsParams, $quickcostUrl);
    }

    /**
     * Add margin to quick cost
     *
     * @param float $quickcostValue
     *
     * @return false|float|int
     */
    public function addMarginToQuickcost($quickcostValue)
    {
        $quickcostMarge = $this->getConfigData("quickcost_marge");
        $quickcostMargeType = $this->getConfigData("quickcost_marge_type");

        if ($quickcostMarge) {
            if ($quickcostMargeType === 'amount') {
                $quickcostValue += $quickcostMarge;
                $this->debugData['request']['quickcost_marge'] = $quickcostMarge;
            } elseif ($quickcostMargeType == 'prcent') {
                $quickcostValue += $quickcostValue * $quickcostMarge / 100;
                $this->debugData['request']['quickcost_marge'] = $quickcostMarge . "%";
            }
        }

        return $quickcostValue;
    }

    /**
     * Get fees config
     *
     * @param string $config
     * @param null|string $countryId
     * @param null|string $postcode
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     *
     * @return null|string
     * @throws Exception
     */
    protected function getFeesConfig($config, $countryId = null, $postcode = null)
    {
        if (!$countryId) {
            throw new \Exception((string)__('Country id is required'));
        }

        $fees = null;
        $configArray = $this->jsonSerializer->unserialize($config);
        foreach ($configArray as $item) {
            if (!isset($item['fees'])) {
                continue;
            }

            if (isset($item['destination'])) {
                $countries = explode(';', $item['destination']);
                foreach ($countries as $country) {
                    if (preg_match('/(.*)-\((.+)\)/', $country, $matches)) { // Example: FR-(12*,62400)
                        if ($matches[1] === $countryId) {
                            $fees = $item['fees'];
                        }

                        $postcodesConfig = explode(',', $matches[2]);
                        foreach ($postcodesConfig as $postcodeConfig) {
                            if (preg_match('/(.+)\*/', $postcodeConfig, $matches2)) { // Example: 12*
                                if (strpos($postcode, $matches2[1]) !== false) {
                                    $fees = null;
                                }
                            } elseif ($postcode == $postcodeConfig) { // 62400
                                $fees = null;
                            }
                        }
                    } elseif ($country === $countryId) { // Example: FR
                        $fees = $item['fees'];
                        break 2;
                    }
                }
            } else {
                $fees = $item['fees'];
            }
        }

        if (!$fees) {
            throw new \Exception('Config of fees is invalid !');
        }

        return $fees;
    }

    /**
     * Convert the weight / price grid to an array
     *
     * @param string $fees
     *
     * @return array
     */
    protected function parseJsonConfig($fees)
    {
        $array = [];

        $string = str_replace(['{', '}'], '', $fees);
        $string = explode(",", $string);
        foreach ($string as $value) {
            $value = explode(':', $value);
            if (isset($value[0]) && $value[0] && isset($value[1]) && $value[1]) {
                $array[trim($value[0])] = trim($value[1]);
            }
        }

        return $array;
    }

    /**
     * Returns price in relation to weight. If basket weight > max weight entered: no price so no delivery method
     *
     * @param $gridPrices
     * @param $cartWeight
     *
     * @return bool|float
     */
    protected function getPriceFromGrid($gridPrices, $cartWeight)
    {
        $currentPrice = false;

        $maxWeight = (float)key(array_slice($gridPrices, -1, 1, true));
        if ($cartWeight > $maxWeight) {
            $this->debugData['error'][] = __("No price found in the grid. The weight of the basket is greater than the maximum weight of the grid");

            return false;
        }

        foreach ($gridPrices as $weight => $price) {
            if ($cartWeight <= $weight) {
                $currentPrice = (float)$price;
                break;
            }
        }

        return $currentPrice;
    }

    /**
     * Method overloaded in other modes if additional price is needed
     *
     * @param $price
     *
     * @return mixed
     */
    public function additionalPrice($price)
    {
        return $price;
    }

    /**
     * Get method title
     *
     * @return false|string
     */
    public function getMethodTitle()
    {
        return $this->getConfigData('name');
    }
}
