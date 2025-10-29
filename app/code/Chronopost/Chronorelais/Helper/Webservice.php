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

namespace Chronopost\Chronorelais\Helper;

use Chronopost\Chronorelais\Helper\Data as HelperData;
use Chronopost\Chronorelais\Model\Carrier\Chronorelais;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Locale\Resolver;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Address;
use Magento\Sales\Model\Order\AddressFactory;
use Magento\Shipping\Model\CarrierFactory;
use Magento\Sales\Model\Order\Shipment;
use Magento\Framework\App\Helper\AbstractHelper;
use Chronopost\Chronorelais\Model\OrderExportStatusFactory;
use Chronopost\Chronorelais\Model\Carrier\ChronopostSrdv;
use Magento\Quote\Model\Quote\Address as ModelQuoteAddress;

/**
 * Class Webservice
 *
 * @package Chronopost\Chronorelais\Helper
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Webservice extends AbstractHelper
{
    const WS_QUICKCOST = 'https://www.chronopost.fr/quickcost-cxf/QuickcostServiceWS?wsdl';
    const WS_SHIPPING_SERVICE = 'https://www.chronopost.fr/shipping-cxf/ShippingServiceWS?wsdl';
    const WS_TRACKING_SERVICE = 'https://www.chronopost.fr/tracking-cxf/TrackingServiceWS?wsdl';
    const WS_RELAY_SERVICE = 'http://wsshipping.chronopost.fr/soap.point.relais/services/ServiceRechercheBt?wsdl';
    const WS_RELAY_POINTRELAY = 'https://www.chronopost.fr/recherchebt-ws-cxf/PointRelaisServiceWS?wsdl';
    const WS_RELAI_SECOURS = 'http://mypudo.pickup-services.com/mypudo/mypudo.asmx?wsdl';
    const WS_RDV_CRENEAUX = 'https://www.chronopost.fr/rdv-cxf/services/CreneauServiceWS?wsdl';


    /**
     * @var bool
     */
    protected $methodsAllowed = [];

    /**
     * @var CarrierFactory
     */
    protected $carrierFactory;

    /**
     * @var Resolver
     */
    protected $resolver;

    /**
     * @var AuthSession
     */
    protected $authSessionver;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var AddressFactory
     */
    protected $addressFactory;

    /**
     * @var DateTime
     */
    protected $datetime;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var OrderExportStatusFactory
     */
    private $orderExportStatusFactory;

    /**
     * Webservice constructor.
     *
     * @param Context                  $context
     * @param CarrierFactory           $carrierFactory
     * @param Resolver                 $resolver
     * @param AuthSession              $authSessionver
     * @param Data                     $helperData
     * @param AddressFactory           $addressFactory
     * @param DateTime                 $dateTime
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderExportStatusFactory $exportStatusFactory
     */
    public function __construct(
        Context $context,
        CarrierFactory $carrierFactory,
        Resolver $resolver,
        AuthSession $authSessionver,
        HelperData $helperData,
        AddressFactory $addressFactory,
        DateTime $dateTime,
        OrderRepositoryInterface $orderRepository,
        OrderExportStatusFactory $exportStatusFactory
    ) {
        parent::__construct($context);
        $this->carrierFactory = $carrierFactory;
        $this->resolver = $resolver;
        $this->authSessionver = $authSessionver;
        $this->helperData = $helperData;
        $this->addressFactory = $addressFactory;
        $this->datetime = $dateTime;
        $this->orderRepository = $orderRepository;
        $this->orderExportStatusFactory = $exportStatusFactory;
    }

    /**
     * Check login
     *
     * @param array $wsParams
     *
     * @return mixed
     */
    public function checkLogin(array $wsParams)
    {
        $quickcostUrl = static::WS_QUICKCOST;

        try {
            $client = new \SoapClient($quickcostUrl);

            return $client->calculateProducts($wsParams);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Get quick cost
     *
     * @param array       $wsParams
     * @param string|null $quickcostUrl
     *
     * @return mixed
     */
    public function getQuickcost(array $wsParams, $quickcostUrl)
    {
        if (!$quickcostUrl) {
            $quickcostUrl = static::WS_QUICKCOST;
        }

        try {
            $client = new \SoapClient($quickcostUrl);

            return $client->quickCost($wsParams);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Return true if the delivery method is part of the contract
     *
     * @param string      $code
     * @param RateRequest $request
     *
     * @return bool
     */
    public function getMethodIsAllowed(string $code, RateRequest $request)
    {
        try {
            $address = $this->addressFactory->create();
            $address->setCountryId($request->getDestCountryId());
            $address->setPostcode($request->getDestPostcode());
            $address->setCity($request->getDestCity());

            $methodAllowed = $this->getMethods($address, $code);

            if (count($methodAllowed) && in_array($code, $methodAllowed)) {
                return true;
            }

            return false;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Get methods
     *
     * @param Address $address
     * @param string  $carrierCode
     *
     * @return array
     */
    public function getMethods(Address $address, string $carrierCode)
    {
        $accountNumber = '';
        $accountPassword = '';
        $contract = $this->helperData->getCarrierContract($carrierCode);
        if ($contract !== null) {
            $accountNumber = $contract['number'];
            $accountPassword = $contract['pass'];
        }

        try {
            if (count($this->methodsAllowed) === 0) {
                $this->methodsAllowed = [];
                $client = new \SoapClient(static::WS_QUICKCOST, ['trace' => 0, 'connection_timeout' => 10]);
                $params = [
                    'accountNumber'  => $accountNumber,
                    'password'       => $accountPassword,
                    'depCountryCode' => $this->scopeConfig->getValue('chronorelais/shipperinformation/country'),
                    'depZipCode'     => $this->scopeConfig->getValue('chronorelais/shipperinformation/zipcode'),
                    'arrCountryCode' => $this->getFilledValue($address->getCountryId()),
                    'arrZipCode'     => $this->getFilledValue($address->getPostcode()),
                    'arrCity'        => $address->getCity() ? $this->getFilledValue($address->getCity()) : '-',
                    'type'           => 'M',
                    'weight'         => 1
                ];

                $webservbt = $client->calculateProducts($params);
                if ($webservbt->return->errorCode == 0 && $webservbt->return->productList) {
                    if (is_array($webservbt->return->productList)) {
                        foreach ($webservbt->return->productList as $product) {
                            $this->methodsAllowed[] = $product->productCode;
                        }
                    } else {
                        $product = $webservbt->return->productList;
                        $this->methodsAllowed[] = $product->productCode;
                    }
                }
            }

            return $this->methodsAllowed;
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * Get filled value
     *
     * @param string|null $value
     *
     * @return string
     */
    protected function getFilledValue($value)
    {
        if ($value) {
            return $this->removeAccents(trim($value));
        }

        return '';
    }

    /**
     * Remove accents of string
     *
     * @param $string
     *
     * @return mixed
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function removeAccents($string)
    {
        $stringToReturn = str_replace(
            [
                'à',
                'á',
                'â',
                'ã',
                'ä',
                'ç',
                'è',
                'é',
                'ê',
                'ë',
                'ì',
                'í',
                'î',
                'ï',
                'ñ',
                'ò',
                'ó',
                'ô',
                'õ',
                'ö',
                'ù',
                'ú',
                'û',
                'ü',
                'ý',
                'ÿ',
                'À',
                'Á',
                'Â',
                'Ã',
                'Ä',
                'Ç',
                'È',
                'É',
                'Ê',
                'Ë',
                'Ì',
                'Í',
                'Î',
                'Ï',
                'Ñ',
                'Ò',
                'Ó',
                'Ô',
                'Õ',
                'Ö',
                'Ù',
                'Ú',
                'Û',
                'Ü',
                'Ý',
                '/',
                '\xa8'
            ],
            [
                'a',
                'a',
                'a',
                'a',
                'a',
                'c',
                'e',
                'e',
                'e',
                'e',
                'i',
                'i',
                'i',
                'i',
                'n',
                'o',
                'o',
                'o',
                'o',
                'o',
                'u',
                'u',
                'u',
                'u',
                'y',
                'y',
                'A',
                'A',
                'A',
                'A',
                'A',
                'C',
                'E',
                'E',
                'E',
                'E',
                'I',
                'I',
                'I',
                'I',
                'N',
                'O',
                'O',
                'O',
                'O',
                'O',
                'U',
                'U',
                'U',
                'U',
                'Y',
                ' ',
                'e'
            ],
            $string
        );

        // Remove all remaining other unknown characters
        $stringToReturn = preg_replace('/[^a-zA-Z0-9\-]/', ' ', $stringToReturn);
        $stringToReturn = preg_replace('/^[\-]+/', '', $stringToReturn);
        $stringToReturn = preg_replace('/[\-]+$/', '', $stringToReturn);
        $stringToReturn = preg_replace('/[\-]{2,}/', ' ', $stringToReturn);

        return $stringToReturn;
    }

    /**
     * Create label
     *
     * @param Shipment    $shipment
     * @param string      $mode
     * @param string|null $recipientAddressType
     * @param array       $dimensions
     * @param int         $packageNumber
     * @param null|string $contractId
     * @param null|float  $customAdValorem
     *
     * @return bool
     * @throws \SoapFault
     */
    public function createEtiquette(
        Shipment $shipment,
        string $mode = 'shipping',
        $recipientAddressType = 'returninformation',
        $dimensions = [],
        $packageNumber = 1,
        $contractId = null,
        $customAdValorem = null
    ) {
        if ($mode === 'shipping') {
            $shippingData = $this->getShipmentParams(
                (int)$packageNumber,
                $shipment,
                $dimensions,
                $contractId,
                $customAdValorem
            );
        } else {
            $shippingData = $this->getReturnParams($shipment, $recipientAddressType);
        }

        if (count($shippingData)) {
            $client = new \SoapClient(self::WS_SHIPPING_SERVICE, ['trace' => true]);
            $expedition = $client->shippingMultiParcelWithReservationV3($shippingData);

            if (!$expedition->return->errorCode) {
                return $expedition;
            } else {
                switch ($expedition->return->errorCode) {
                    case 33:
                        $message = __('An error occured during the label creation. ' .
                            'Please check if this contract can edit labels for this carrier.');
                        break;
                    default:
                        $message = __($expedition->return->errorMessage);
                        break;
                }

                throw new \Exception((string)$message);
            }
        }

        return false;
    }

    /**
     * Get shipping params
     *
     * @param int         $packageNumber
     * @param Shipment    $shipment
     * @param array       $dimensions
     * @param string|null $contractId
     * @param null|float  $customAdValorem
     *
     * @return array
     * @throws \Exception
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function getShipmentParams(
        int $packageNumber,
        Shipment $shipment,
        $dimensions = [],
        $contractId = null,
        $customAdValorem = null
    ) {
        $order = $shipment->getOrder();
        $shippingAddress = $shipment->getShippingAddress();
        $billingAddress = $shipment->getBillingAddress();

        $shippingMethod = explode('_', $order->getData('shipping_method'));
        $shippingMethod = isset($shippingMethod[1]) ? $shippingMethod[1] : $shippingMethod[0];

        $carrier = $this->carrierFactory->get($shippingMethod);
        if (!$carrier || !$carrier->getIsChronoMethod()) {
            return [];
        }

        // Header parameters
        $header = $this->getParamsHeader($order, $contractId);
        $password = $this->getPasswordContract($order, $contractId);

        // Shipper parameters
        $shipper = $this->getShipperAddress('shipping');

        // Customer parameters
        $customer = $this->getCustomerParams('shipping');

        // Recipient parameters
        $customerEmail = $shippingAddress->getEmail() ?: ($billingAddress->getEmail() ?: $order->getCustomerEmail());
        $recipient = $this->getRecipientAddress('shipping', $shippingAddress, $customerEmail);

        // Ref parameters
        $recipientRef = $this->getFilledValue($order->getRelaisId());
        if (!$recipientRef) {
            $recipientRef = $order->getCustomerId();
        }

        $shipperRef = $order->getRealOrderId();

        // Skybill parameters
        $optionSaturday = $this->getParamSaturdayShipping($order, $carrier);
        $productCode = $carrier->getChronoProductCodeToShipment();
        $serviceCode = $this->getShipmentServiceCode($shippingMethod, $optionSaturday, $shipment);

        $adValorem = 0;
        if ($packageNumber === 1) {
            if ($customAdValorem) {
                $adValorem = $customAdValorem * 100;
            } else {
                $adValorem = $this->helperData->getOrderAdValorem($order) * 100;
            }
        }

        $weightCoefficient = $this->helperData->getWeightCoef();

        $skybills = [];
        foreach (range(1, $packageNumber) as $value) {
            $skybill = [
                'codCurrency'     => 'EUR',
                'codValue'        => '',
                'content1'        => '',
                'content2'        => '',
                'content3'        => '',
                'content4'        => '',
                'content5'        => '',
                'customsCurrency' => 'EUR',
                'customsValue'    => '',
                'evtCode'         => 'DC',
                'insuredCurrency' => 'EUR',
                'insuredValue'    => $adValorem,
                'objectType'      => 'MAR',
                'productCode'     => $productCode,
                'service'         => $serviceCode,
                'shipDate'        => date('c'),
                'shipHour'        => date('H'),
                'weightUnit'      => 'KGM',
                'skybillRank'     => $value
            ];

            if ($packageNumber > 1) {
                $skybill['bulkNumber'] = $packageNumber;
            }

            $ite = $value - 1;
            $skybill['weight'] = isset($dimensions[$ite]['weight']) ? $dimensions[$ite]['weight'] / $weightCoefficient : 2;
            $skybill['height'] = isset($dimensions[$ite]['height']) ? $dimensions[$ite]['height'] : 1;
            $skybill['length'] = isset($dimensions[$ite]['length']) ? $dimensions[$ite]['length'] : 1;
            $skybill['width'] = isset($dimensions[$ite]['width']) ? $dimensions[$ite]['width'] : 1;

            if (preg_match('/chronopostsrdv/', $shippingMethod, $matches, PREG_OFFSET_CAPTURE)) {
                $chronopostsrdvCreneauxInfo = $order->getData('chronopostsrdv_creneaux_info');
                $chronopostsrdvCreneauxInfo = json_decode($chronopostsrdvCreneauxInfo, true);
                $skybill['productCode'] = $chronopostsrdvCreneauxInfo['productCode'];
                $skybill['service'] = $chronopostsrdvCreneauxInfo['serviceCode'];
                if ($chronopostsrdvCreneauxInfo['dayOfWeek'] == 7 && isset($chronopostsrdvCreneauxInfo['asCode'])) {
                    $skybill['as'] = $chronopostsrdvCreneauxInfo['asCode'];
                }
            }

            $skybills[] = $skybill;
        }

        $mode = $this->scopeConfig->getValue('chronorelais/skybillparam/mode');
        if ($shippingMethod === 'chronorelaiseur') {
            $mode = 'PPR';
        }

        $skybillParams = ['mode' => $mode];

        $shippingData = [
            'headerValue'        => $header,
            'shipperValue'       => $shipper,
            'customerValue'      => $customer,
            'recipientValue'     => $recipient,
            'refValue'           => ['recipientRef' => $recipientRef, 'shipperRef' => $shipperRef],
            'skybillValue'       => $skybills,
            'skybillParamsValue' => $skybillParams,
            'password'           => $password,
            'numberOfParcel'     => $packageNumber
        ];

        // if chronopostsrdv: add additional parameters
        if (preg_match('/chronopostsrdv/', $shippingMethod, $matches, PREG_OFFSET_CAPTURE)) {
            $chronopostsrdvCreneauxInfo = $order->getData('chronopostsrdv_creneaux_info');
            $chronopostsrdvCreneauxInfo = json_decode($chronopostsrdvCreneauxInfo, true);

            $dateRdvStart = new \DateTime($chronopostsrdvCreneauxInfo['deliveryDate']);
            $dateRdvStart->setTime(
                (int)$chronopostsrdvCreneauxInfo['startHour'],
                (int)$chronopostsrdvCreneauxInfo['startMinutes']
            );

            $dateRdvEnd = new \DateTime($chronopostsrdvCreneauxInfo['deliveryDate']);
            $dateRdvEnd->setTime(
                (int)$chronopostsrdvCreneauxInfo['endHour'],
                (int)$chronopostsrdvCreneauxInfo['endMinutes']
            );

            $scheduledValue = [
                'appointmentValue' => [
                    'timeSlotStartDate'   => $dateRdvStart->format('Y-m-d') . 'T' . $dateRdvStart->format('H:i:s'),
                    'timeSlotEndDate'     => $dateRdvEnd->format('Y-m-d') . 'T' . $dateRdvEnd->format('H:i:s'),
                    'timeSlotTariffLevel' => $chronopostsrdvCreneauxInfo['tariffLevel']
                ]
            ];

            $shippingData['scheduledValue'] = $scheduledValue;
        }

        return $shippingData;
    }

    /**
     * Get params return
     *
     * @param Shipment    $shipment
     * @param string|null $recipientAddressType
     *
     * @return array
     * @throws \Exception
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function getReturnParams(Shipment $shipment, $recipientAddressType)
    {
        $order = $shipment->getOrder();
        $shippingAddress = $shipment->getShippingAddress();
        $billingAddress = $shipment->getBillingAddress();
        $shippingMethod = explode('_', $order->getShippingMethod());
        $shippingMethod = isset($shippingMethod[1]) ? $shippingMethod[1] : $shippingMethod[0];

        $carrier = $this->carrierFactory->get($shippingMethod);
        if (!$carrier || !$carrier->getIsChronoMethod()) {
            return [];
        }

        // Header parameters
        $header = $this->getParamsHeader($order);
        $password = $this->getPasswordContract($order);

        // Shipper parameters
        $customerEmail = $shippingAddress->getEmail() ?: ($billingAddress->getEmail() ?: $order->getCustomerEmail());
        if (strpos($shippingMethod, 'chronorelais') !== false) {
            // Replace relay address by customer billing address
            $address = $billingAddress;

            // Replace customer address by relay address if customer country is not authorized
            if ($shippingMethod === 'chronorelaiseur' &&
                !$this->helperData->returnAuthorized($billingAddress->getCountryId(), $shippingMethod)) {
                $address = $shippingAddress;
            }

            $shipper = $this->getRecipientAddress('return', $address, $customerEmail);
        } else {
            $shipper = $this->getRecipientAddress('return', $shippingAddress, $customerEmail);
        }

        // Customer parameters
        $customer = $this->getCustomerParams('return', $billingAddress, $customerEmail);

        // Get recipient return address
        $recipient = $this->getShipperAddress('return', $recipientAddressType);

        // Reference parameters
        $recipientRef = $this->getFilledValue($order->getRelaisId());
        if (!$recipientRef) {
            $recipientRef = $order->getCustomerId();
        }

        $shipperRef = $order->getRealOrderId();

        // Skybill parameters
        $optionSaturday = $this->getParamSaturdayShipping($order, $carrier);
        $productCode = $this->getReturnProductCode($shippingAddress, $shippingMethod);
        //$codeService = $this->helperData->getReturnServiceCode($productCode);
        $codeService = $optionSaturday === true ? 6 : 0; // Service code automatically generated by the webservice

        $skybill = [
            'codCurrency'     => 'EUR',
            'codValue'        => '',
            'content1'        => '',
            'content2'        => '',
            'content3'        => '',
            'content4'        => '',
            'content5'        => '',
            'customsCurrency' => 'EUR',
            'customsValue'    => '',
            'evtCode'         => 'DC',
            'insuredCurrency' => 'EUR',
            'insuredValue'    => 0, // We put the weight to 0 because the packages are weighed on site
            'objectType'      => 'MAR',
            'productCode'     => $productCode,
            'service'         => $codeService,
            'shipDate'        => date('c'),
            'shipHour'        => date('H'),
            'weight'          => 0,
            'weightUnit'      => 'KGM',
            'height'          => 1,
            'length'          => 1,
            'width'           => 1
        ];

        $skybillParams = [
            'mode'            => 'SLT|XML|XML2D|PDF',
            'withReservation' => 0,
            'duplicata'       => 'N',
            'printAsSender'   => 'Y'
        ];

        if ($skybill['productCode'] === Data::CHRONOPOST_REVERSE_RELAY_EUROPE) {
            $skybillParams['mode'] = 'PPR|XML';
        }

        $shippingData = [
            'headerValue'        => $header,
            'shipperValue'       => $shipper,
            'customerValue'      => $customer,
            'recipientValue'     => $recipient,
            'refValue'           => ['recipientRef' => $recipientRef, 'shipperRef' => $shipperRef],
            'skybillValue'       => $skybill,
            'skybillParamsValue' => $skybillParams,
            'password'           => $password,
            'numberOfParcel'     => 1,
            'multiParcel'        => 'N',
            'version'            => '2.0',
            'modeRetour'         => 3
        ];

        if ($skybill['productCode'] === Data::CHRONOPOST_REVERSE_RELAY_EUROPE) {
            $shippingData['modeRetour'] = 1;
        }

        return $shippingData;
    }

    /**
     * Get header params
     *
     * @param Order $order
     * @param null  $contractId
     *
     * @return array
     */
    protected function getParamsHeader(Order $order, $contractId = null)
    {
        if ($contractId !== null && (int)$contractId >= 0) {
            $contract = $this->helperData->getSpecificContract($contractId);
        } else {
            $contract = $this->getContractData($order);
        }

        $params['idEmit'] = 'MAG';
        $params['accountNumber'] = $contract['number'];
        $params['subAccount'] = $contract['subAccount'];

        return $params;
    }

    /**
     * Get contract data
     *
     * @param Order $order
     *
     * @return array|mixed|null
     */
    public function getContractData(Order $order)
    {
        $contract = $this->helperData->getContractByOrderId($order->getId());

        if (!$contract) {
            if ($this->_request->getParam('contract') !== null) {
                $contract = $this->helperData->getSpecificContract($this->_request->getParam('contract'));
            } else {
                $shippingMethod = explode('_', $order->getData('shipping_method'));
                $shippingMethod = isset($shippingMethod[1]) ? $shippingMethod[1] : $shippingMethod[0];
                $contract = $this->helperData->getCarrierContract($shippingMethod);
            }
        } else {
            $contractTemp = $contract->getData();

            $contract = [];
            $contract['name'] = $contractTemp['contract_name'];
            $contract['number'] = $contractTemp['contract_account_number'];
            $contract['subAccount'] = $contractTemp['contract_sub_account_number'];
            $contract['pass'] = $contractTemp['contract_account_password'];
        }

        return $contract;
    }

    /**
     * Get password contract
     *
     * @param Order $order
     * @param null  $contractId
     *
     * @return mixed
     */
    protected function getPasswordContract(Order $order, $contractId = null)
    {
        $contract = $this->getContractData($order);

        if ($contractId !== null && (int)$contractId >= 0) {
            $contract = $this->helperData->getSpecificContract($contractId);
        }

        return $contract['pass'];
    }

    /**
     * Check mobile number
     *
     * @param string $value
     *
     * @return string
     */
    protected function checkMobileNumber($value)
    {
        if ($value) {
            $reqValue = trim($value);

            if ($reqValue) {
                $number = substr($reqValue, 0, 2);
                $fixedArr = ['01', '02', '03', '04', '05', '06', '07'];
                if (in_array($number, $fixedArr)) {
                    return $reqValue;
                }
            }
        }

        return '';
    }

    /**
     * Get address data
     *
     * @param string  $type
     * @param Address $address
     * @param string  $customerEmail
     *
     * @return array
     */
    protected function getRecipientAddress(string $type, Address $address, string $customerEmail)
    {
        $streetAddress = $address->getStreet();
        $streetAddress[1] = (!isset($streetAddress[1])) ? '' : $streetAddress[1];
        $cellPhone = $this->checkMobileNumber($address->getTelephone());
        $cName = $this->getFilledValue(sprintf('%s %s', $address->getFirstname(), $address->getLastname()));
        $lastname = $this->getFilledValue($address->getCompany() ?: $address->getLastname());

        $phone = '';
        if ($address->getTelephone()) {
            $phone = trim(preg_replace('/[^0-9\.\-]/', ' ', $address->getTelephone()));
        }

        if ($type === 'shipping') {
            $data = [
                'recipientAdress1'     => substr($this->getFilledValue($streetAddress[0]), 0, 38),
                'recipientAdress2'     => substr($this->getFilledValue($streetAddress[1]), 0, 38),
                'recipientCity'        => $this->getFilledValue($address->getCity()),
                'recipientContactName' => $cName,
                'recipientCountry'     => $this->getFilledValue($address->getCountryId()),
                'recipientEmail'       => $customerEmail,
                'recipientMobilePhone' => $cellPhone,
                'recipientName'        => $lastname,
                'recipientName2'       => $cName,
                'recipientPhone'       => $phone,
                'recipientPreAlert'    => '',
                'recipientZipCode'     => $this->getFilledValue($address->getPostcode())
            ];
        } else {
            $data = [
                'shipperAdress1'     => substr($this->getFilledValue($streetAddress[0]), 0, 38),
                'shipperAdress2'     => $streetAddress[1] ?
                    substr($this->getFilledValue($streetAddress[1]), 0, 38) : '',
                'shipperCity'        => $this->getFilledValue($address->getCity()),
                'shipperCivility'    => 'M',
                'shipperContactName' => $cName,
                'shipperCountry'     => $this->getFilledValue($address->getCountryId()),
                'shipperEmail'       => $customerEmail,
                'shipperMobilePhone' => $cellPhone,
                'shipperName'        => $lastname,
                'shipperName2'       => $cName,
                'shipperPhone'       => $phone,
                'shipperPreAlert'    => '',
                'shipperZipCode'     => $this->getFilledValue($address->getPostcode())
            ];
        }

        return $data;
    }

    /**
     * Get customer params
     *
     * @param string       $type
     * @param Address|null $address
     * @param null         $customerEmail
     *
     * @return array
     */
    protected function getCustomerParams(string $type, $address = null, $customerEmail = null)
    {
        if ($type === 'shipping') {
            $customerMobilePhone = $this->checkMobileNumber(
                $this->scopeConfig->getValue('chronorelais/customerinformation/mobilephone')
            );

            $data = [
                'customerAdress1'     => $this->scopeConfig->getValue('chronorelais/customerinformation/address1'),
                'customerAdress2'     => $this->scopeConfig->getValue('chronorelais/customerinformation/address2'),
                'customerCity'        => $this->scopeConfig->getValue('chronorelais/customerinformation/city'),
                'customerCivility'    => $this->scopeConfig->getValue('chronorelais/customerinformation/civility'),
                'customerContactName' => $this->scopeConfig->getValue('chronorelais/customerinformation/contactname'),
                'customerCountry'     => $this->scopeConfig->getValue('chronorelais/customerinformation/country'),
                'customerEmail'       => $this->scopeConfig->getValue('chronorelais/customerinformation/email'),
                'customerMobilePhone' => $customerMobilePhone,
                'customerName'        => $this->scopeConfig->getValue('chronorelais/customerinformation/name'),
                'customerName2'       => $this->scopeConfig->getValue('chronorelais/customerinformation/name2'),
                'customerPhone'       => $this->scopeConfig->getValue('chronorelais/customerinformation/phone'),
                'customerPreAlert'    => '',
                'customerZipCode'     => $this->scopeConfig->getValue('chronorelais/customerinformation/zipcode')
            ];
        } else {
            $streetAddress = $address->getStreet();
            $streetAddress[1] = (!isset($streetAddress[1])) ? '' : $streetAddress[1];
            $cellPhone = $this->checkMobileNumber($address->getTelephone());
            $cName = $this->getFilledValue(sprintf('%s %s', $address->getFirstname(), $address->getLastname()));
            $lastname = $this->getFilledValue($address->getCompany() ?: $address->getLastname());

            $phone = '';
            if ($address->getTelephone()) {
                $phone = trim(preg_replace('/[^0-9\.\-]/', ' ', $address->getTelephone()));
            }

            $data = [
                'customerAdress1'     => substr($this->getFilledValue($streetAddress[0]), 0, 38),
                'customerAdress2'     => $streetAddress[1] ?
                    substr($this->getFilledValue($streetAddress[1]), 0, 38) : '',
                'customerCity'        => $this->getFilledValue($address->getCity()),
                'customerCivility'    => 'M',
                'customerContactName' => $cName,
                'customerCountry'     => $this->getFilledValue($address->getCountryId()),
                'customerEmail'       => $customerEmail,
                'customerMobilePhone' => $cellPhone,
                'customerName'        => $lastname,
                'customerName2'       => $cName,
                'customerPhone'       => $phone,
                'customerPreAlert'    => '',
                'customerZipCode'     => $this->getFilledValue($address->getPostcode())
            ];
        }

        return $data;
    }

    /**
     * Get saturday shipping param
     *
     * @param Order $order
     * @param mixed $carrier
     * @param bool  $isReturn
     *
     * @return bool
     * @throws \Exception
     */
    protected function getParamSaturdayShipping(Order $order, $carrier, bool $isReturn = false)
    {
        if ($isReturn === false) {
            $saturdayOptionIsActive = (bool)$order->getData('force_saturday_option');
            $customerChoiceEnabled = (bool)$this->scopeConfig->getValue('chronorelais/saturday/display_to_customer');
            if ($saturdayOptionIsActive === false && $customerChoiceEnabled === false) {
                $saturdayOptionIsActive = $this->helperData->isSendingDay();
            }

            $shippingConfigSaturday = (bool)$this->scopeConfig->getValue(
                'carriers/' . $carrier->getCarrierCode() . '/deliver_on_saturday'
            );

            // Add generated value
            $saturdayExportStatus = $this->helperData->getShippingSaturdayStatus($order->getId());
            if ($saturdayExportStatus === 'Yes') {
                $optionSaturday = true;
                $order->setData('force_saturday_option_generated', '1');
            } elseif ($saturdayExportStatus === 'No') {
                $optionSaturday = false;
                $order->setData('force_saturday_option_generated', '0');
            } else {
                $optionSaturday = true;
                if ($shippingConfigSaturday === false || $saturdayOptionIsActive === false) {
                    $optionSaturday = false;
                }

                $order->setData('force_saturday_option_generated', $optionSaturday ? '1' : '0');
            }

            $order->save();
        } else {
            $optionSaturday = (bool)$order->getData('force_saturday_option_generated');
        }

        return $optionSaturday;
    }

    /**
     * Get param weight
     *
     * @param Shipment $shipment
     *
     * @return float|int
     */
    protected function getParamWeight(Shipment $shipment)
    {
        $weight = 0;
        foreach ($shipment->getItemsCollection() as $item) {
            $weight += $item->getWeight() * $item->getQty();
        }

        if ($this->scopeConfig->getValue('chronorelais/weightunit/unit') === 'g') {
            $weight = $weight / 1000; /* convert g => kg */
        }

        return $weight;
    }

    /**
     * Get address data
     *
     * @param string|null $recipAddressType
     *
     * @return array
     */
    protected function getShipperAddress(string $type, $recipAddressType = null)
    {
        if ($type === 'shipping') {
            $cellPhone = $this->checkMobileNumber(
                $this->scopeConfig->getValue('chronorelais/shipperinformation/mobilephone')
            );

            $data = [
                'shipperAdress1'     => $this->scopeConfig->getValue('chronorelais/shipperinformation/address1'),
                'shipperAdress2'     => $this->scopeConfig->getValue('chronorelais/shipperinformation/address2'),
                'shipperCity'        => $this->scopeConfig->getValue('chronorelais/shipperinformation/city'),
                'shipperCivility'    => $this->scopeConfig->getValue('chronorelais/shipperinformation/civility'),
                'shipperContactName' => $this->scopeConfig->getValue('chronorelais/shipperinformation/contactname'),
                'shipperCountry'     => $this->scopeConfig->getValue('chronorelais/shipperinformation/country'),
                'shipperEmail'       => $this->scopeConfig->getValue('chronorelais/shipperinformation/email'),
                'shipperMobilePhone' => $cellPhone,
                'shipperName'        => $this->scopeConfig->getValue('chronorelais/shipperinformation/name'),
                'shipperName2'       => $this->scopeConfig->getValue('chronorelais/shipperinformation/name2'),
                'shipperPhone'       => $this->scopeConfig->getValue('chronorelais/shipperinformation/phone'),
                'shipperPreAlert'    => '',
                'shipperZipCode'     => $this->scopeConfig->getValue('chronorelais/shipperinformation/zipcode')
            ];
        } else {
            $cellPhone = $this->checkMobileNumber(
                $this->scopeConfig->getValue('chronorelais/' . $recipAddressType . '/mobilephone')
            );

            $data = [
                'recipientAdress1'     => $this->scopeConfig->getValue('chronorelais/' . $recipAddressType . '/address1'),
                'recipientAdress2'     => $this->scopeConfig->getValue('chronorelais/' . $recipAddressType . '/address2'),
                'recipientCity'        => $this->scopeConfig->getValue('chronorelais/' . $recipAddressType . '/city'),
                'recipientCivility'    => $this->scopeConfig->getValue('chronorelais/' . $recipAddressType . '/civility'),
                'recipientContactName' => $this->scopeConfig->getValue('chronorelais/' . $recipAddressType . '/contactname'),
                'recipientCountry'     => $this->scopeConfig->getValue('chronorelais/' . $recipAddressType . '/country'),
                'recipientEmail'       => $this->scopeConfig->getValue('chronorelais/' . $recipAddressType . '/email'),
                'recipientMobilePhone' => $cellPhone,
                'recipientName'        => $this->scopeConfig->getValue('chronorelais/' . $recipAddressType . '/name'),
                'recipientName2'       => $this->scopeConfig->getValue('chronorelais/' . $recipAddressType . '/name2'),
                'recipientPhone'       => $this->scopeConfig->getValue('chronorelais/' . $recipAddressType . '/phone'),
                'recipientPreAlert'    => '',
                'recipientZipCode'     => $this->scopeConfig->getValue('chronorelais/' . $recipAddressType . '/zipcode')
            ];
        }

        return $data;
    }

    /**
     * Get product code and service code to reverse (return)
     *
     * @param Address     $address
     * @param string|null $carrierCode
     *
     * @return int|string
     */
    public function getReturnProductCode(Address $address, $carrierCode = null)
    {
        $productCodes = $this->getMethods($address, $carrierCode);
        $productReturnCodes = $this->helperData->getReturnProductCodesAllowed($productCodes);
        sort($productReturnCodes, SORT_STRING);

        foreach ($this->helperData->getMatrixReturnCode() as $code => $combinaisonCodes) {
            if (in_array($productReturnCodes, $combinaisonCodes)) {
                return $code;
            }
        }

        return HelperData::CHRONOPOST_REVERSE_DEFAULT;
    }

    /**
     * Cancel label
     *
     * @param string $number
     * @param null   $contract
     *
     * @return false
     * @throws \SoapFault
     */
    public function cancelEtiquette($number = '', $contract = null)
    {
        if ($number) {
            $accountNumber = '';
            $accountPassword = '';
            if ($contract != null) {
                $accountNumber = $contract['contract_account_number'];
                $accountPassword = $contract['contract_account_password'];
            }

            $client = new \SoapClient(self::WS_TRACKING_SERVICE, ['trace' => 0, 'connection_timeout' => 10]);
            $params = [
                'accountNumber' => $accountNumber,
                'password'      => $accountPassword,
                'skybillNumber' => $number,
                'language'      => $this->getLocale()
            ];

            return $client->cancelSkybill($params);
        }

        return false;
    }

    /**
     * Get locale
     *
     * @return string
     */
    protected function getLocale()
    {
        if ($this->authSessionver->getUser() && $this->authSessionver->getUser()->getId()) {
            return $this->authSessionver->getUser()->getInterfaceLocale();
        }

        return $this->resolver->getLocale();
    }

    /**
     * Get relay by postcode
     *
     * @param string $postcode
     *
     * @return bool|mixed
     */
    public function getPointsRelaisByCp(string $postcode)
    {
        try {
            $client = new \SoapClient(self::WS_RELAY_SERVICE, ['trace' => 0, 'connection_timeout' => 10]);

            return $client->__call('rechercheBtParCodeproduitEtCodepostalEtDate', [0, $postcode, 0]);
        } catch (\Exception $exception) {
            return $this->getPointsRelaisByPudo(null, $postcode);
        }
    }

    /**
     * WS emergency relay
     *
     * @param null|array $address
     * @param bool       $postcode
     *
     * @return array|false
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getPointsRelaisByPudo($address = null, $postcode = false)
    {
        $params = [
            'carrier'             => 'CHR',
            'key'                 => '75f6fe195dc88ceecbc0f8a2f70a8f3a',
            'address'             => $address ? $this->getFilledValue($address->getStreetLine(1)) : '',
            'zipCode'             => $address ? $this->getFilledValue($address->getPostcode()) : $postcode,
            'city'                => $address ? $this->getFilledValue($address->getCity()) : 'Lille',
            'countrycode'         => $address ? $this->getFilledValue($address->getCountryId()) : '',
            'requestID'           => '1',
            'date_from'           => date('d/m/Y'),
            'max_pudo_number'     => 5,
            'max_distance_search' => 10,
            'weight'              => 1,
            'category'            => '',
            'holiday_tolerant'    => 1,
        ];

        try {
            $client = new \SoapClient(
                self::WS_RELAI_SECOURS,
                ['trace' => 0, 'connection_timeout' => 10]
            );

            $webservbt = $client->GetPudoList($params);
            $webservbt = json_decode(json_encode((object)simplexml_load_string($webservbt->GetPudoListResult->any)), 1);
            if (!isset($webservbt['ERROR'])) {
                $return = [];

                $relayPoints = $webservbt['PUDO_ITEMS']['PUDO_ITEM'];
                if ($relayPoints) {
                    foreach ($relayPoints as $relayPoint) {
                        if ($relayPoint['@attributes']['active'] == 'true') {
                            $newPr = (object)[];
                            $newPr->adresse1 = $relayPoint['ADDRESS1'];
                            $newPr->adresse2 = is_array($relayPoint['ADDRESS2']) ? implode(
                                ' ',
                                $relayPoint['ADDRESS2']
                            ) : $relayPoint['ADDRESS2'];

                            $newPr->adresse3 = is_array($relayPoint['ADDRESS3']) ? implode(
                                ' ',
                                $relayPoint['ADDRESS3']
                            ) : $relayPoint['ADDRESS3'];

                            $newPr->codePostal = $relayPoint['ZIPCODE'];
                            $newPr->identifiantChronopostPointA2PAS = $relayPoint['PUDO_ID'];
                            $newPr->latitude = $relayPoint['coordGeolocalisationLatitude'];
                            $newPr->longitude = $relayPoint['coordGeolocalisationLongitude'];
                            $newPr->localite = $relayPoint['CITY'];
                            $newPr->nomEnseigne = $relayPoint['NAME'];

                            $time = new \DateTime();
                            $newPr->dateArriveColis = $time->format(\DateTime::ATOM);

                            $newPr->horairesOuvertureLundi = '';
                            $newPr->horairesOuvertureMardi = '';
                            $newPr->horairesOuvertureMercredi = '';
                            $newPr->horairesOuvertureJeudi = '';
                            $newPr->horairesOuvertureVendredi = '';
                            $newPr->horairesOuvertureSamedi = '';
                            $newPr->horairesOuvertureDimanche = '';

                            if (isset($relayPoint['OPENING_HOURS_ITEMS']['OPENING_HOURS_ITEM'])) {
                                $openingHours = $relayPoint['OPENING_HOURS_ITEMS']['OPENING_HOURS_ITEM'];
                                foreach ($openingHours as $openingHour) {
                                    switch ($openingHour['DAY_ID']) {
                                        case '1':
                                            if (!empty($newPr->horairesOuvertureLundi)) {
                                                $newPr->horairesOuvertureLundi .= ' ';
                                            }

                                            $newPr->horairesOuvertureLundi .=
                                                sprintf('%s-%s', $openingHour['START_TM'], $openingHour['END_TM']);
                                            break;
                                        case '2':
                                            if (!empty($newPr->horairesOuvertureMardi)) {
                                                $newPr->horairesOuvertureMardi .= ' ';
                                            }

                                            $newPr->horairesOuvertureMardi .=
                                                sprintf('%s-%s', $openingHour['START_TM'], $openingHour['END_TM']);
                                            break;
                                        case '3':
                                            if (!empty($newPr->horairesOuvertureMercredi)) {
                                                $newPr->horairesOuvertureMercredi .= ' ';
                                            }

                                            $newPr->horairesOuvertureMercredi .=
                                                sprintf('%s-%s', $openingHour['START_TM'], $openingHour['END_TM']);
                                            break;
                                        case '4':
                                            if (!empty($newPr->horairesOuvertureJeudi)) {
                                                $newPr->horairesOuvertureJeudi .= ' ';
                                            }

                                            $newPr->horairesOuvertureJeudi .=
                                                sprintf('%s-%s', $openingHour['START_TM'], $openingHour['END_TM']);
                                            break;
                                        case '5':
                                            if (!empty($newPr->horairesOuvertureVendredi)) {
                                                $newPr->horairesOuvertureVendredi .= ' ';
                                            }

                                            $newPr->horairesOuvertureVendredi .=
                                                sprintf('%s-%s', $openingHour['START_TM'], $openingHour['END_TM']);
                                            break;
                                        case '6':
                                            if (!empty($newPr->horairesOuvertureSamedi)) {
                                                $newPr->horairesOuvertureSamedi .= ' ';
                                            }

                                            $newPr->horairesOuvertureSamedi .=
                                                sprintf('%s-%s', $openingHour['START_TM'], $openingHour['END_TM']);
                                            break;
                                        case '7':
                                            if (!empty($newPr->horairesOuvertureDimanche)) {
                                                $newPr->horairesOuvertureDimanche .= ' ';
                                            }

                                            $newPr->horairesOuvertureDimanche .=
                                                sprintf('%s-%s', $openingHour['START_TM'], $openingHour['END_TM']);
                                            break;
                                    }
                                }
                            }

                            if (empty($newPr->horairesOuvertureLundi)) {
                                $newPr->horairesOuvertureLundi = '00:00-00:00 00:00-00:00';
                            }

                            if (empty($newPr->horairesOuvertureMardi)) {
                                $newPr->horairesOuvertureMardi = '00:00-00:00 00:00-00:00';
                            }

                            if (empty($newPr->horairesOuvertureMercredi)) {
                                $newPr->horairesOuvertureMercredi = '00:00-00:00 00:00-00:00';
                            }

                            if (empty($newPr->horairesOuvertureJeudi)) {
                                $newPr->horairesOuvertureJeudi = '00:00-00:00 00:00-00:00';
                            }

                            if (empty($newPr->horairesOuvertureVendredi)) {
                                $newPr->horairesOuvertureVendredi = '00:00-00:00 00:00-00:00';
                            }

                            if (empty($newPr->horairesOuvertureSamedi)) {
                                $newPr->horairesOuvertureSamedi = '00:00-00:00 00:00-00:00';
                            }

                            if (empty($newPr->horairesOuvertureDimanche)) {
                                $newPr->horairesOuvertureDimanche = '00:00-00:00 00:00-00:00';
                            }
                            $return[] = $newPr;
                        }
                    }

                    return $return;
                }
            }
        } catch (\Exception $exception) {
            return false;
        }

        return false;
    }

    /**
     * Get relay by address
     *
     * @param string $shippingMethodCode
     * @param false  $address
     *
     * @return array|bool
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getPointRelaisByAddress($shippingMethodCode = 'chronorelais', $address = false)
    {
        if (!$shippingMethodCode || !$address) {
            return false;
        }

        $accountNumber = '';
        $accountPassword = '';
        $contract = $this->helperData->getCarrierContract($shippingMethodCode);
        if ($contract != null) {
            $accountNumber = $contract['number'];
            $accountPassword = $contract['pass'];
        }

        try {
            $carrier = $this->carrierFactory->get($shippingMethodCode);

            $pointRelaisWsMethod = $carrier->getConfigData('point_relai_ws_method');
            $pointRelaisProductCode = $carrier->getChronoProductCode();
            $pointRelaisService = 'T';
            $addAddressToWs = $carrier->getConfigData('add_address_to_ws');
            $maxPointChronopost = $carrier->getConfigData('max_point_chronopost');
            $maxDistanceSearch = $carrier->getConfigData('max_distance_search');
            $displayType = $this->scopeConfig->getValue('chronorelais/dropoff/mode');

            $client = new \SoapClient(self::WS_RELAY_POINTRELAY, ['trace' => 0, 'connection_timeout' => 10]);

            // if dom => we do not put the ISO code but a specific code, otherwise the dom relay does not work
            $countryDomCode = $this->getCountryDomCode();
            $countryId = $address->getCountryId();
            if (isset($countryDomCode[$countryId])) {
                $countryId = $countryDomCode[$countryId];
            }

            $params = [
                'accountNumber'      => $accountNumber,
                'password'           => $accountPassword,
                'zipCode'            => $this->getFilledValue($address->getPostcode()),
                'city'               => $address->getCity() ? $this->getFilledValue($address->getCity()) : '',
                'countryCode'        => $this->getFilledValue($countryId),
                'type'               => $displayType ? $displayType : 'P',
                'productCode'        => $pointRelaisProductCode,
                'service'            => $pointRelaisService,
                'weight'             => 2000,
                'shippingDate'       => date('d/m/Y'),
                'maxPointChronopost' => $maxPointChronopost,
                'maxDistanceSearch'  => $maxDistanceSearch,
                'holidayTolerant'    => 1
            ];

            if ($addAddressToWs) {
                $params['address'] = $address->getStreetLine(0) ?
                    $this->getFilledValue($address->getStreetLine(0)) : '';
            }

            // Format $webservbt to have the same format as when calling the WS by postal code
            $webservbt = $client->$pointRelaisWsMethod($params);
            if ($webservbt->return->errorCode == 0) {
                $relayPoints = $webservbt->return->listePointRelais;
                if (!is_array($relayPoints)) {
                    $relayPoints = [$relayPoints];
                }

                $return = [];
                foreach ($relayPoints as $relayPoint) {
                    $newPr = (object)[];
                    $newPr->adresse1 = $relayPoint->adresse1;
                    $newPr->adresse2 = $relayPoint->adresse2;
                    $newPr->adresse3 = $relayPoint->adresse3;
                    $newPr->latitude = $relayPoint->coordGeolocalisationLatitude;
                    $newPr->longitude = $relayPoint->coordGeolocalisationLongitude;
                    $newPr->codePostal = $relayPoint->codePostal;
                    $newPr->identifiantChronopostPointA2PAS = $relayPoint->identifiant;
                    $newPr->localite = $relayPoint->localite;
                    $newPr->nomEnseigne = $relayPoint->nom;

                    $time = new \DateTime();
                    $newPr->dateArriveColis = $time->format(\DateTime::ATOM);

                    $newPr->horairesOuvertureLundi = '';
                    $newPr->horairesOuvertureMardi = '';
                    $newPr->horairesOuvertureMercredi = '';
                    $newPr->horairesOuvertureJeudi = '';
                    $newPr->horairesOuvertureVendredi = '';
                    $newPr->horairesOuvertureSamedi = '';
                    $newPr->horairesOuvertureDimanche = '';
                    foreach ($relayPoint->listeHoraireOuverture as $openingHour) {
                        switch ($openingHour->jour) {
                            case '1':
                                $newPr->horairesOuvertureLundi = $openingHour->horairesAsString;
                                break;
                            case '2':
                                $newPr->horairesOuvertureMardi = $openingHour->horairesAsString;
                                break;
                            case '3':
                                $newPr->horairesOuvertureMercredi = $openingHour->horairesAsString;
                                break;
                            case '4':
                                $newPr->horairesOuvertureJeudi = $openingHour->horairesAsString;
                                break;
                            case '5':
                                $newPr->horairesOuvertureVendredi = $openingHour->horairesAsString;
                                break;
                            case '6':
                                $newPr->horairesOuvertureSamedi = $openingHour->horairesAsString;
                                break;
                            case '7':
                                $newPr->horairesOuvertureDimanche = $openingHour->horairesAsString;
                                break;
                            default:
                                break;
                        }
                    }

                    if (empty($newPr->horairesOuvertureLundi)) {
                        $newPr->horairesOuvertureLundi = '00:00-00:00 00:00-00:00';
                    }

                    if (empty($newPr->horairesOuvertureMardi)) {
                        $newPr->horairesOuvertureMardi = '00:00-00:00 00:00-00:00';
                    }

                    if (empty($newPr->horairesOuvertureMercredi)) {
                        $newPr->horairesOuvertureMercredi = '00:00-00:00 00:00-00:00';
                    }

                    if (empty($newPr->horairesOuvertureJeudi)) {
                        $newPr->horairesOuvertureJeudi = '00:00-00:00 00:00-00:00';
                    }

                    if (empty($newPr->horairesOuvertureVendredi)) {
                        $newPr->horairesOuvertureVendredi = '00:00-00:00 00:00-00:00';
                    }

                    if (empty($newPr->horairesOuvertureSamedi)) {
                        $newPr->horairesOuvertureSamedi = '00:00-00:00 00:00-00:00';
                    }

                    if (empty($newPr->horairesOuvertureDimanche)) {
                        $newPr->horairesOuvertureDimanche = '00:00-00:00 00:00-00:00';
                    }

                    $return[] = $newPr;
                }

                return $return;
            }
        } catch (\Exception $exception) {
            return $this->getPointsRelaisByPudo($address);
        }
    }

    /**
     * @return array
     */
    protected function getCountryDomCode()
    {
        return [
            'RE' => 'REU',
            'MQ' => 'MTQ',
            'GP' => 'GLP',
            'MX' => 'MYT',
            'GF' => 'GUF'
        ];
    }

    /**
     * Get info relais
     *
     * @param $relaisId
     *
     * @return mixed
     */
    public function getDetailRelaisPoint($relaisId)
    {
        $accountNumber = '';
        $accountPassword = '';
        $contract = $this->helperData->getCarrierContract(Chronorelais::CARRIER_CODE);
        if ($contract != null) {
            $accountNumber = $contract['number'];
            $accountPassword = $contract['pass'];
        }

        try {
            $params = [
                'accountNumber' => $accountNumber,
                'password'      => $accountPassword,
                'identifiant'   => $relaisId
            ];

            $client = new \SoapClient(self::WS_RELAY_POINTRELAY);
            $webservbt = $client->rechercheDetailPointChronopost($params);

            if ($webservbt->return->errorCode == 0) {
                return $webservbt->return->listePointRelais;
            } else {
                return $this->getDetailRelaisPointByPudo($relaisId);
            }
        } catch (\Exception $exception) {
            return $this->getDetailRelaisPointByPudo($relaisId);
        }
    }

    /**
     * Get relay informations (emergency WS)
     *
     * @param string $relaisId
     *
     * @return bool|object
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getDetailRelaisPointByPudo($relaisId)
    {
        $params = [
            'carrier' => 'CHR',
            'key'     => '75f6fe195dc88ceecbc0f8a2f70a8f3a',
            'pudo_id' => $relaisId,
        ];

        try {
            $client = new \SoapClient(self::WS_RELAI_SECOURS, ['trace' => 0, 'connection_timeout' => 10]);
            $webservbt = $client->GetPudoDetails($params);
            $xml = (object)simplexml_load_string($webservbt->GetPudoDetailsResult->any);
            $webservbt = json_decode(json_encode($xml), 1);

            if (!isset($webservbt['ERROR'])) {
                $relay = $webservbt['PUDO_ITEMS']['PUDO_ITEM'];
                if ($relay && $relay['@attributes']['active'] == 'true') {
                    $newPr = (object)[];
                    $newPr->adresse1 = $relay['ADDRESS1'];
                    $newPr->adresse2 = is_array($relay['ADDRESS2']) ? implode(
                        ' ',
                        $relay['ADDRESS2']
                    ) : $relay['ADDRESS2'];
                    $newPr->adresse3 = is_array($relay['ADDRESS3']) ? implode(
                        ' ',
                        $relay['ADDRESS3']
                    ) : $relay['ADDRESS3'];
                    $newPr->latitude = $relay['coordGeolocalisationLatitude'];
                    $newPr->longitude = $relay['coordGeolocalisationLongitude'];
                    $newPr->codePostal = $relay['ZIPCODE'];
                    $newPr->identifiantChronopostPointA2PAS = $relay['PUDO_ID'];
                    $newPr->localite = $relay['CITY'];
                    $newPr->nomEnseigne = $relay['NAME'];

                    $time = new \DateTime();
                    $newPr->dateArriveColis = $time->format(\DateTime::ATOM);

                    $newPr->horairesOuvertureLundi = '';
                    $newPr->horairesOuvertureMardi = '';
                    $newPr->horairesOuvertureMercredi = '';
                    $newPr->horairesOuvertureJeudi = '';
                    $newPr->horairesOuvertureVendredi = '';
                    $newPr->horairesOuvertureSamedi = '';
                    $newPr->horairesOuvertureDimanche = '';

                    if (isset($relay['OPENING_HOURS_ITEMS']['OPENING_HOURS_ITEM'])) {
                        $openingHours = $relay['OPENING_HOURS_ITEMS']['OPENING_HOURS_ITEM'];
                        foreach ($openingHours as $openingHour) {
                            switch ($openingHour['DAY_ID']) {
                                case '1':
                                    if (!empty($newPr->horairesOuvertureLundi)) {
                                        $newPr->horairesOuvertureLundi .= ' ';
                                    }
                                    $newPr->horairesOuvertureLundi .=
                                        sprintf('%s-%s', $openingHour['START_TM'], $openingHour['END_TM']);
                                    break;
                                case '2':
                                    if (!empty($newPr->horairesOuvertureMardi)) {
                                        $newPr->horairesOuvertureMardi .= ' ';
                                    }
                                    $newPr->horairesOuvertureMardi .=
                                        sprintf('%s-%s', $openingHour['START_TM'], $openingHour['END_TM']);
                                    break;
                                case '3':
                                    if (!empty($newPr->horairesOuvertureMercredi)) {
                                        $newPr->horairesOuvertureMercredi .= ' ';
                                    }
                                    $newPr->horairesOuvertureMercredi .=
                                        sprintf('%s-%s', $openingHour['START_TM'], $openingHour['END_TM']);
                                    break;
                                case '4':
                                    if (!empty($newPr->horairesOuvertureJeudi)) {
                                        $newPr->horairesOuvertureJeudi .= ' ';
                                    }
                                    $newPr->horairesOuvertureJeudi .=
                                        sprintf('%s-%s', $openingHour['START_TM'], $openingHour['END_TM']);
                                    break;
                                case '5':
                                    if (!empty($newPr->horairesOuvertureVendredi)) {
                                        $newPr->horairesOuvertureVendredi .= ' ';
                                    }
                                    $newPr->horairesOuvertureVendredi .=
                                        sprintf('%s-%s', $openingHour['START_TM'], $openingHour['END_TM']);
                                    break;
                                case '6':
                                    if (!empty($newPr->horairesOuvertureSamedi)) {
                                        $newPr->horairesOuvertureSamedi .= ' ';
                                    }
                                    $newPr->horairesOuvertureSamedi .=
                                        sprintf('%s-%s', $openingHour['START_TM'], $openingHour['END_TM']);
                                    break;
                                case '7':
                                    if (!empty($newPr->horairesOuvertureDimanche)) {
                                        $newPr->horairesOuvertureDimanche .= ' ';
                                    }
                                    $newPr->horairesOuvertureDimanche .=
                                        sprintf('%s-%s', $openingHour['START_TM'], $openingHour['END_TM']);
                                    break;
                                default:
                                    break;
                            }
                        }
                    }

                    if (empty($newPr->horairesOuvertureLundi)) {
                        $newPr->horairesOuvertureLundi = '00:00-00:00 00:00-00:00';
                    }

                    if (empty($newPr->horairesOuvertureMardi)) {
                        $newPr->horairesOuvertureMardi = '00:00-00:00 00:00-00:00';
                    }

                    if (empty($newPr->horairesOuvertureMercredi)) {
                        $newPr->horairesOuvertureMercredi = '00:00-00:00 00:00-00:00';
                    }

                    if (empty($newPr->horairesOuvertureJeudi)) {
                        $newPr->horairesOuvertureJeudi = '00:00-00:00 00:00-00:00';
                    }

                    if (empty($newPr->horairesOuvertureVendredi)) {
                        $newPr->horairesOuvertureVendredi = '00:00-00:00 00:00-00:00';
                    }

                    if (empty($newPr->horairesOuvertureSamedi)) {
                        $newPr->horairesOuvertureSamedi = '00:00-00:00 00:00-00:00';
                    }

                    if (empty($newPr->horairesOuvertureDimanche)) {
                        $newPr->horairesOuvertureDimanche = '00:00-00:00 00:00-00:00';
                    }

                    return $newPr;
                }
            }
        } catch (\Exception $exception) {
            return false;
        }

        return false;
    }

    /**
     * Get planning by shipping address
     *
     * @param bool|ModelQuoteAddress $shippingAddress
     *
     * @return bool
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getPlanning($shippingAddress)
    {
        $recipientStreetAddress = $shippingAddress->getStreet();
        if (!isset($recipientStreetAddress[1])) {
            $recipientStreetAddress[1] = '';
        }

        try {
            $accountNumber = '';
            $accountPassword = '';
            $contract = $this->helperData->getCarrierContract(ChronopostSrdv::CARRIER_CODE);
            if ($contract != null) {
                $accountNumber = $contract['number'];
                $accountPassword = $contract['pass'];
            }

            $soapHeaders = [];
            $namespace = 'http://cxf.soap.ws.creneau.chronopost.fr/';
            $soapHeaders[] = new \SoapHeader($namespace, 'password', $accountPassword);
            $soapHeaders[] = new \SoapHeader($namespace, 'accountNumber', $accountNumber);

            $client = new \SoapClient(
                self::WS_RDV_CRENEAUX,
                ['trace' => 1, 'connection_timeout' => 10]
            );

            $client->__setSoapHeaders($soapHeaders);

            $srdvConfig = json_decode($this->scopeConfig->getValue("carriers/chronopostsrdv/rdv_config"), true);

            // Begin date
            $dateBegin = date('Y-m-d H:i:s');
            if (isset($srdvConfig['dateRemiseColis_nbJour']) && $srdvConfig['dateRemiseColis_nbJour'] > 0) {
                $dateBegin = date('Y-m-d', strtotime('+' . (int)$srdvConfig['dateRemiseColis_nbJour'] . ' day'));
            } elseif (isset($srdvConfig['dateRemiseColis_jour']) && isset($srdvConfig['dateRemiseColis_heures'])) {
                $dayTxt = date('l', strtotime("Sunday +" . $srdvConfig['dateRemiseColis_jour'] . " days"));
                $dateBegin = sprintf(
                    '%s %s:%s:00',
                    date('Y-m-d', strtotime('next ' . $dayTxt)),
                    $srdvConfig['dateRemiseColis_heures'],
                    $srdvConfig['dateRemiseColis_minutes']
                );
            }

            $dateBegin = date('Y-m-d', strtotime($dateBegin)) . 'T' . date('H:i:s', strtotime($dateBegin));

            $params = [
                'callerTool'                => 'RDVWS',
                'productType'               => 'RDV',
                'shipperAdress1'            => $this->scopeConfig->getValue('chronorelais/shipperinformation/address1'),
                'shipperAdress2'            => $this->scopeConfig->getValue('chronorelais/shipperinformation/address2'),
                'shipperZipCode'            => $this->scopeConfig->getValue('chronorelais/shipperinformation/zipcode'),
                'shipperCity'               => $this->scopeConfig->getValue('chronorelais/shipperinformation/city'),
                'shipperCountry'            => $this->scopeConfig->getValue('chronorelais/shipperinformation/country'),
                'recipientAdress1'          => substr($this->getFilledValue($recipientStreetAddress[0]), 0, 38),
                'recipientAdress2'          => substr($this->getFilledValue($recipientStreetAddress[1]), 0, 38),
                'recipientZipCode'          => $this->getFilledValue($shippingAddress->getPostcode()),
                'recipientCity'             => $this->getFilledValue($shippingAddress->getCity()),
                'recipientCountry'          => $this->getFilledValue($shippingAddress->getCountryId()),
                'weight'                    => 1,
                'dateBegin'                 => $dateBegin,
                'shipperDeliverySlotClosed' => '',
                'currency'                  => 'EUR',
                'isDeliveryDate'            => 0,
                'slotType'                  => ''
            ];

            for ($ite = 1; $ite <= 4; $ite++) {
                if (isset($srdvConfig['N' . $ite . '_price'])) {
                    $params['rateN' . $ite] = $srdvConfig['N' . $ite . '_price'];
                }

                if (isset($srdvConfig['N' . $ite . '_status']) && $srdvConfig['N' . $ite . '_status'] == 0) {
                    if (!isset($params['rateLevelsNotShow'])) {
                        $params['rateLevelsNotShow'] = [];
                    }

                    $params['rateLevelsNotShow'][] = 'N' . $ite;
                }
            }

            // Slots to close
            if (isset($srdvConfig['creneaux'])) {
                foreach ($srdvConfig['creneaux'] as $slot) {
                    $endDate = '';
                    $beginDate = '';

                    $times = $this->datetime->timestamp(strtotime('Sunday +' . $slot['creneaux_debut_jour'] . ' days'));
                    $beginDayTxt = date('l', $times);

                    $times = $this->datetime->timestamp(strtotime('Sunday +' . $slot['creneaux_fin_jour'] . ' days'));
                    $endDayTxt = date('l', $times);

                    // Creation of slots in the right formats, for 6 consecutive weeks
                    for ($indiceWeek = 0; $indiceWeek < 6; $indiceWeek++) {
                        if (empty($beginDate)) {
                            $slotBeginHour = (int)$slot['creneaux_debut_heures'];
                            $slotBeginMin = (int)$slot['creneaux_debut_minutes'];
                            $date = date('Y-m-d', $this->datetime->timestamp(strtotime('next ' . $beginDayTxt)));
                            $beginDate = sprintf('%s %s:%s:00', $date, $slotBeginHour, $slotBeginMin);

                            $slotEndHour = (int)$slot['creneaux_fin_heures'];
                            $slotEndMin = (int)$slot['creneaux_fin_minutes'];
                            $date = date('Y-m-d', $this->datetime->timestamp(strtotime('next ' . $endDayTxt)));
                            $endDate = sprintf('%s %s:%s:00', $date, $slotEndHour, $slotEndMin);

                            if (date('N') >= $slot['creneaux_debut_jour']) {
                                $slotBeginHour = (int)$slot['creneaux_debut_heures'];
                                $slotBeginMin = (int)$slot['creneaux_debut_minutes'];
                                $dateNext = strtotime(date('Y-m-d', strtotime($beginDate)) . ' -7 days');
                                $date = date('Y-m-d', $this->datetime->timestamp($dateNext));
                                $beginDate = sprintf('%s %s:%s:00', $date, $slotBeginHour, $slotBeginMin);
                            }

                            if (date('N') >= $slot['creneaux_fin_jour']) {
                                $slotEndHour = (int)$slot['creneaux_fin_heures'];
                                $slotEndMin = (int)$slot['creneaux_fin_minutes'];
                                $datePrev = strtotime(date('Y-m-d', strtotime($endDate)) . ' -7 days');
                                $date = date('Y-m-d', $this->datetime->timestamp($datePrev));
                                $endDate = sprintf('%s %s:%s:00', $date, $slotEndHour, $slotEndMin);
                            }
                        } else {
                            $dateFtdBegin = date('Y-m-d', $this->datetime->timestamp(strtotime($beginDate)));
                            $dateNext = strtotime($beginDayTxt . ' next week ' . $dateFtdBegin);
                            $date = date('Y-m-d', $this->datetime->timestamp($dateNext));
                            $slotBeginHour = (int)$slot['creneaux_debut_heures'];
                            $slotBeginMin = (int)$slot['creneaux_debut_minutes'];
                            $beginDate = sprintf('%s %s:%s:00', $date, $slotBeginHour, $slotBeginMin);

                            $dateFtdEnd = date('Y-m-d', $this->datetime->timestamp(strtotime($endDate)));
                            $dateNext = strtotime($endDayTxt . ' next week ' . $dateFtdEnd);
                            $date = date('Y-m-d', $this->datetime->timestamp($dateNext));
                            $slotEndHour = (int)$slot['creneaux_fin_heures'];
                            $slotEndMin = (int)$slot['creneaux_fin_minutes'];
                            $endDate = sprintf('%s %s:%s:00', $date, $slotEndHour, $slotEndMin);
                        }

                        $beginDateStr = date('Y-m-d', $this->datetime->timestamp(strtotime($beginDate)));
                        $beginDateStr .= 'T' . date('H:i:s', $this->datetime->timestamp(strtotime($beginDate)));

                        $endDateStr = date('Y-m-d', $this->datetime->timestamp(strtotime($endDate)));
                        $endDateStr .= 'T' . date('H:i:s', $this->datetime->timestamp(strtotime($endDate)));

                        if (!isset($params['shipperDeliverySlotClosed'])) {
                            $params['shipperDeliverySlotClosed'] = [];
                        }

                        $params['shipperDeliverySlotClosed'][] = $beginDateStr . "/" . $endDateStr;
                    }
                }
            }

            $webservbt = $client->searchDeliverySlot($params);
            if ($webservbt->return->code === 0) {
                return $webservbt;
            }

            return false;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Confirm delivery slot
     *
     * @param array $rdvInfo
     *
     * @return bool
     */
    public function confirmDeliverySlot(array $rdvInfo = [])
    {
        try {
            $accountNumber = '';
            $accountPassword = '';

            $contract = $this->helperData->getCarrierContract(ChronopostSrdv::CARRIER_CODE);
            if ($contract != null) {
                $accountNumber = $contract['number'];
                $accountPassword = $contract['pass'];
            }

            $soapHeaders = [];
            $namespace = 'http://cxf.soap.ws.creneau.chronopost.fr/';
            $soapHeaders[] = new \SoapHeader($namespace, 'password', $accountPassword);
            $soapHeaders[] = new \SoapHeader($namespace, 'accountNumber', $accountNumber);

            $client = new \SoapClient(
                self::WS_RDV_CRENEAUX,
                ['trace' => 1, 'connection_timeout' => 10]
            );

            $client->__setSoapHeaders($soapHeaders);

            $params = [
                'callerTool'    => 'RDVWS',
                'productType'   => 'RDV',
                'codeSlot'      => $rdvInfo['deliverySlotCode'],
                'meshCode'      => $rdvInfo['meshCode'],
                'transactionID' => $rdvInfo['transactionID'],
                'rank'          => $rdvInfo['rank'],
                'position'      => $rdvInfo['rank'],
                'dateSelected'  => $rdvInfo['deliveryDate']
            ];

            return $client->confirmDeliverySlotV2($params);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Get labels by reservation number
     *
     * @param string      $number
     * @param string|null $shippingMethod
     * @param string      $type
     * @param Address     $shippingAddress
     *
     * @return mixed
     * @throws \SoapFault
     */
    public function getEtiquetteByReservationNumber(
        string $number,
        string $shippingMethod,
        string $type,
        Address $shippingAddress
    ) {
        $client = new \SoapClient(self::WS_SHIPPING_SERVICE, ['trace' => true]);

        if ($type === 'return') {
            $mode = 'PDF';
            $productCode = $this->getReturnProductCode($shippingAddress, $shippingMethod);
            if ($productCode === Data::CHRONOPOST_REVERSE_RELAY_EUROPE) {
                $mode = 'PPR';
            }
        } else {
            $mode = $this->scopeConfig->getValue('chronorelais/skybillparam/mode');
            if ($shippingMethod === 'chronorelaiseur') {
                $mode = 'PPR';
            }
        }

        $expedition = $client->getReservedSkybillWithTypeAndMode(['reservationNumber' => $number, 'mode' => $mode]);
        if (!$expedition->return->errorCode) {
            return $expedition->return->skybill;
        } else {
            switch ($expedition->return->errorCode) {
                case 33:
                    $message = __('An error occured during the label creation. Please check if this contract can edit labels for this carrier.');
                    break;
                default:
                    $message = __($expedition->return->errorMessage);
                    break;
            }

            throw new \Exception((string)$message);
        }
    }

    /**
     * Check contract
     *
     * @param array $contract
     *
     * @return bool
     */
    public function checkContract(array $contract)
    {
        $WSParams = [
            'accountNumber'  => $contract['number'],
            'password'       => $contract['pass'],
            'depCountryCode' => $this->helperData->getConfig('chronorelais/shipperinformation/country'),
            'depZipCode'     => $this->helperData->getConfig('chronorelais/shipperinformation/zipcode'),
            'arrCountryCode' => $this->helperData->getConfig('chronorelais/shipperinformation/country'),
            'arrZipCode'     => $this->helperData->getConfig('chronorelais/shipperinformation/zipcode'),
            'arrCity'        => $this->helperData->getConfig('chronorelais/shipperinformation/city'),
            'type'           => 'M',
            'weight'         => 1
        ];

        return $this->checkLogin($WSParams);
    }

    /**
     * Check if shipping method is enabled
     *
     * @param string      $shippingMethod
     * @param null|string $contractId
     *
     * @return bool
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function shippingMethodEnabled(string $shippingMethod, $contractId = null)
    {
        if ($contractId !== null) {
            $contract = $this->helperData->getSpecificContract($contractId);
        } else {
            $contract = $this->helperData->getCarrierContract($shippingMethod);
        }

        if (!$contract) {
            return false;
        }

        $WSParams = [
            'accountNumber'  => $contract['number'],
            'password'       => $contract['pass'],
            'depCountryCode' => $this->helperData->getConfigurationShipperInfo('country'),
            'depZipCode'     => $this->helperData->getConfigurationShipperInfo('zipcode'),
            'arrCountryCode' => $this->helperData->getConfigurationShipperInfo('country'),
            'arrZipCode'     => $this->helperData->getConfigurationShipperInfo('zipcode'),
            'arrCity'        => $this->helperData->getConfigurationShipperInfo('city'),
            'type'           => 'M',
            'weight'         => 1
        ];

        $webservbt = $this->checkLogin($WSParams);
        if (!$webservbt->return->errorCode) {
            $products = $webservbt->return->productList;

            $WSParams['arrCountryCode'] = 'ES';
            $WSParams['arrZipCode'] = '28013';
            $WSParams['arrCity'] = 'ES';

            $webservbt = $this->checkLogin($WSParams);
            if (!$webservbt->return->errorCode) {
                if ($productsInter = $webservbt->return->productList) {
                    if (is_array($products) && is_array($productsInter)) {
                        $products = array_merge($products, $productsInter);
                    } else {
                        array_push($products, $productsInter);
                    }
                }
            }

            $WSParams['arrCountryCode'] = 'RE';
            $WSParams['arrZipCode'] = '97400';
            $WSParams['arrCity'] = 'Saint-Denis';

            $webservbt = $this->checkLogin($WSParams);
            if (!$webservbt->return->errorCode) {
                if ($productsDom = $webservbt->return->productList) {
                    if (is_array($products) && is_array($productsDom)) {
                        $products = array_merge($products, $productsDom);
                    } else {
                        array_push($products, $productsDom);
                    }
                }
            }

            if (is_array($products)) {
                foreach ($products as $product) {
                    if ($this->helperData->getChronoProductCode($shippingMethod) == $product->productCode) {
                        return true;
                    }
                }
            } elseif ($this->helperData->getChronoProductCodeToShipment($shippingMethod) == $products->productCode) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get contracts in html
     *
     * @param string $orderId
     *
     * @return string
     */
    public function getContractsHtml(string $orderId)
    {
        $order = $this->orderRepository->get($orderId);
        $contracts = $this->helperData->getConfigContracts();

        if ($contract = $this->helperData->getContractByOrderId($orderId)) {
            $html = '';
            foreach ($contracts as $id => $item) {
                if ($item['number'] === $contract['contract_account_number']) {
                    $html = '<select name="contract" style="display:none">';
                    $html .= '<option value="' . $id . '" selected="selected">' . $contract['contract_name'] . '</option>';
                    $html .= '</select>';
                    $html .= '<span>' . $contract['contract_name'] . '</span>';
                    break;
                }
            }

            return $html;
        } else {
            $html = '<select name="contract">';

            $chronoShippingMethod = strtolower(str_replace(' ', '', $order->getShippingMethod()));
            $chronoShippingMethod = preg_replace('/.*\_/', '', $chronoShippingMethod);
            $contractShippingMethod = $this->helperData->getCarrierContract($chronoShippingMethod);

            foreach ($contracts as $id => $contract) {
                $shippingMethodCode = explode('_', $chronoShippingMethod);
                $shippingMethodCode = isset($shippingMethodCode[1]) ? $shippingMethodCode[1] : $shippingMethodCode[0];
                if (!$this->shippingMethodEnabled($shippingMethodCode, $id)) {
                    continue;
                }

                if ($contract['number'] == $contractShippingMethod['number']) {
                    $html .= '<option value="' . $id . '" selected="selected">' . $contract['name'] . '</option>';
                } else {
                    $html .= '<option value="' . $id . '">' . $contract['name'] . '</option>';
                }
            }

            $html .= '</select>';

            return $html;
        }
    }

    /**
     * Get shipment service code
     *
     * @param string   $shippingMethod
     * @param bool     $optionSaturday
     * @param Shipment $shipment
     *
     * @return int
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function getShipmentServiceCode(string $shippingMethod, bool $optionSaturday, Shipment $shipment)
    {
        switch ($shippingMethod) {
            case 'chronopost':
                $serviceCode = ($optionSaturday === true) ? 897 : 899;
                break;
            case 'chronopostc10':
                $serviceCode = ($optionSaturday === true) ? 182 : 179;
                break;
            case 'chronopostc18':
                $serviceCode = ($optionSaturday === true) ? 832 : 830;
                break;
            case 'chronoexpress':
                $serviceCode = 302;
                break;
            case 'chronocclassic':
                $serviceCode = 101;
                break;
            case 'chronorelais':
                $serviceCode = 848;
                break;
            case 'chronorelaiseur':
                $serviceCode = $this->getParamWeight($shipment) <= 3 ? 337 : 338;
                break;
            case 'chronorelaisdom':
                $serviceCode = 368;
                break;
            case 'chronopostsrdv':
                $serviceCode = 976;
                break;
            case 'chronosameday':
                $serviceCode = ($optionSaturday === true) ? 974 : 973;
                break;
            default:
                $serviceCode = ($optionSaturday === true) ? 6 : 0;
                break;
        }

        return $serviceCode;
    }
}
