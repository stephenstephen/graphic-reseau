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

use Chronopost\Chronorelais\Model\ResourceModel\OrderExportStatus\CollectionFactory as OrderExportStatusCollectionFactory;
use Chronopost\Chronorelais\Model\ContractsOrdersFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Shipping\Model\CarrierFactory;
use Magento\Framework\Module\Dir\Reader;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Data
 *
 * @package Chronopost\Chronorelais\Helper
 */
class Data extends AbstractHelper
{
    const MODULE_NAME = 'Chronopost_Chronorelais';

    const CHRONO_POST = '01'; // for France
    const CHRONO_POST_BAL = '58'; // For france with option BAL
    const CHRONORELAY = '86'; // for Chronorelais
    const CHRONO_EXPRESS = '17';  // for International
    const CHRONOPOST_C10 = '02'; // for Chronopost C10
    const CHRONOPOST_C18 = '16'; // for Chronopost C18
    const CHRONOPOST_C18_BAL = '2M'; // for Chronopost C18 with option BAL
    const CHRONOPOST_CCLASSIC = '44'; // for Chronopost CClassic
    const CHRONO_RELAY_EUROPE = '49'; // for Chronorelais Europe
    const CHRONO_RELAY_DOM = '4P'; // for Chronorelais DOM
    const CHRONOPOST_SMD = '4I'; // for Chronopost SAMEDAY
    const CHRONOPOST_SRDV = '2O'; // for Chronopost RDV => Uppercase 'O' and not zero

    const CHRONOPOST_REVERSE_R = '4R'; // for Chronopost Reverse 9
    const CHRONOPOST_REVERSE_S = '4S'; // for Chronopost Reverse 10
    const CHRONOPOST_REVERSE_T = '4T'; // for Chronopost Reverse 13
    const CHRONOPOST_REVERSE_U = '4U'; // for Chronopost Reverse 18
    const CHRONOPOST_REVERSE_DEFAULT = '01'; // Default value
    const CHRONOPOST_REVERSE_RELAY_EUROPE = '3T'; // for Chronopost Reverse Relay Europe

    const CHRONOPOST_REVERSE_R_SERVICE = '885'; // for Chronopost Reverse 9
    const CHRONOPOST_REVERSE_S_SERVICE = '180'; // for Chronopost Reverse 10
    const CHRONOPOST_REVERSE_T_SERVICE = '898'; // for Chronopost Reverse 13
    const CHRONOPOST_REVERSE_U_SERVICE = '835'; // for Chronopost Reverse 18
    const CHRONOPOST_REVERSE_DEFAULT_SERVICE = '226'; // Default
    const CHRONOPOST_REVERSE_RELAY_EUROPE_SERVICE = '332';

    const COUNTRY_ALLOWED_RETURN_EUROPE = [
        'DE',
        'LU',
        'BE',
        'NL',
        'PT',
        'CH',
        'GB',
        'EE',
        'LT',
        'CH',
        'AT',
        'LV'
    ];

    const SHIPPING_METHODS_RETURN_ALLOWED = [
        'chronorelaiseur',
        'chronorelais',
        'chronopost',
        'chronopostc10',
        'chronopostc18',
        'chronopostsrdv',
        'chronocclassic',
        'chronoexpress',
        'chronosameday'
    ];

    const SHIPPING_METHODS_SATURDAY_ALLOWED = [
        'chronopost',
        'chronopostc10',
        'chronopostc18',
        'chronosameday'
    ];

    const SHIPPING_METHODS_RETURN_INTERNATIONAL = [
        'chronoexpress',
        'chronorelaiseur',
        'chronorelaisdom',
        'chronocclassic',
        'chronopostsrdv'
    ];

    /**
     * @var OrderExportStatusCollectionFactory
     */
    protected $_orderExportStatusCollectionFactory;

    /**
     * @var CarrierFactory
     */
    protected $_carrierFactory;

    /**
     * @var ContractsOrdersFactory
     */
    protected $_contractsOrdersFactory;

    /**
     * @var OrderRepositoryInterface
     */
    protected $_orderRepository;

    /**
     * @var Reader
     */
    protected $_moduleReader;

    /**
     * @var array
     */
    private $SaturdayShippingDays;

    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;

    /**
     * @var array
     */
    private $order = [];

    /**
     * Data constructor.
     *
     * @param Context                            $context
     * @param OrderExportStatusCollectionFactory $collectionFactory
     * @param ContractsOrdersFactory             $_contractsOrdersFactory
     * @param CarrierFactory                     $_carrierFactory
     * @param OrderRepositoryInterface           $_orderRepository
     * @param Reader                             $_moduleReader
     * @param StoreManagerInterface              $_storeManager
     */
    public function __construct(
        Context $context,
        OrderExportStatusCollectionFactory $collectionFactory,
        ContractsOrdersFactory $_contractsOrdersFactory,
        CarrierFactory $_carrierFactory,
        OrderRepositoryInterface $_orderRepository,
        Reader $_moduleReader,
        StoreManagerInterface $_storeManager
    ) {
        parent::__construct($context);
        $this->_orderExportStatusCollectionFactory = $collectionFactory;
        $this->_carrierFactory = $_carrierFactory;
        $this->_contractsOrdersFactory = $_contractsOrdersFactory;
        $this->_orderRepository = $_orderRepository;
        $this->_moduleReader = $_moduleReader;
        $this->_storeManager = $_storeManager;
    }

    /**
     * Get config value
     *
     * @param string $node
     *
     * @return null|string
     */
    public function getConfig(string $node)
    {
        return $this->scopeConfig->getValue($node);
    }

    /**
     * return true if method is chrono
     *
     * @param string $shippingMethod
     *
     * @return bool
     */
    public function isChronoMethod(string $shippingMethod)
    {
        $carrier = $this->_carrierFactory->get($shippingMethod);

        return $carrier ? $carrier->getIsChronoMethod() : false;
    }

    /**
     * Check if sending day
     *
     * @return bool
     * @throws \Exception
     */
    public function isSendingDay()
    {
        $shippingDays = $this->getSaturdayShippingDays();
        $currentDate = $this->getCurrentTimeByZone('Europe/Paris', 'Y-m-d H:i:s');

        $startTimestamp = strtotime($shippingDays['startday'] . ' this week ' . $shippingDays['starttime']);
        $endTimestamp = strtotime($shippingDays['endday'] . ' this week ' . $shippingDays['endtime']);
        $currentTimestamp = strtotime($currentDate);

        $sendingDay = false;
        if ($currentTimestamp >= $startTimestamp && $currentTimestamp <= $endTimestamp) {
            $sendingDay = true;
        }

        return $sendingDay;
    }

    /**
     * Get saturday shipping days
     *
     * @return array
     */
    public function getSaturdayShippingDays()
    {
        $starday = explode(':', $this->scopeConfig->getValue('chronorelais/saturday/startday'));
        $endday = explode(':', $this->scopeConfig->getValue('chronorelais/saturday/endday'));

        $saturdayDays = [];
        $saturdayDays['startday'] = (count($starday) === 3 && isset($starday[0])) ?
            $starday[0] : $this->SaturdayShippingDays['startday'];
        $saturdayDays['starttime'] = (count($starday) === 3 && isset($starday[1])) ?
            $starday[1] . ':' . $starday[2] . ':00' : $this->SaturdayShippingDays['starttime'];
        $saturdayDays['endday'] = (count($endday) === 3 && isset($endday[0])) ?
            $endday[0] : $this->SaturdayShippingDays['endday'];
        $saturdayDays['endtime'] = (count($endday) === 3 && isset($endday[1])) ?
            $endday[1] . ':' . $endday[2] . ':00' : $this->SaturdayShippingDays['endtime'];

        return $saturdayDays;
    }

    /**
     * Get current datetime by zone
     *
     * @param string $timezone
     * @param string $format
     *
     * @return string
     * @throws \Exception
     */
    public function getCurrentTimeByZone($timezone = 'Europe/Paris', $format = 'l H:i')
    {
        $currentDate = new \DateTime('NOW', new \DateTimeZone($timezone));

        return $currentDate->format($format);
    }

    /**
     * Get status shipping
     *
     * @param string $orderId
     *
     * @return mixed
     */
    public function getShippingSaturdayStatus($orderId)
    {
        $collection = $this->_orderExportStatusCollectionFactory->create();
        $collection->addFieldToFilter('order_id', $orderId)->addFieldToSelect('livraison_le_samedi');
        $status = $collection->getFirstItem();

        return $status->getData('livraison_le_samedi');
    }

    /**
     * Get return product codes allowed
     *
     * @param array $productCodes
     *
     * @return array
     */
    public function getReturnProductCodesAllowed(array $productCodes)
    {
        $possibleReturnProductCode = [
            static::CHRONOPOST_REVERSE_R,
            static::CHRONOPOST_REVERSE_S,
            static::CHRONOPOST_REVERSE_T,
            static::CHRONOPOST_REVERSE_U,
            static::CHRONOPOST_REVERSE_RELAY_EUROPE
        ];

        $returnProductCode = [];
        foreach ($productCodes as $code) {
            if (in_array($code, $possibleReturnProductCode)) {
                array_push($returnProductCode, $code);
            }

            if ($code === static::CHRONO_EXPRESS) {
                array_push($returnProductCode, self::CHRONOPOST_REVERSE_RELAY_EUROPE);
            }
        }

        return (sizeof($returnProductCode) > 0) ? $returnProductCode : [static::CHRONOPOST_REVERSE_DEFAULT];
    }

    /**
     * Get return service code
     *
     * @param string $code
     *
     * @return string
     */
    public function getReturnServiceCode(string $code)
    {
        switch ($code) {
            case static::CHRONOPOST_REVERSE_R:
                return static::CHRONOPOST_REVERSE_R_SERVICE;
            case static::CHRONOPOST_REVERSE_S:
                return static::CHRONOPOST_REVERSE_S_SERVICE;
            case static::CHRONOPOST_REVERSE_T:
                return static::CHRONOPOST_REVERSE_T_SERVICE;
            case static::CHRONOPOST_REVERSE_U:
                return static::CHRONOPOST_REVERSE_U_SERVICE;
            case static::CHRONOPOST_REVERSE_RELAY_EUROPE:
                return static::CHRONOPOST_REVERSE_RELAY_EUROPE_SERVICE;
            default:
                return static::CHRONOPOST_REVERSE_DEFAULT_SERVICE;
        }
    }

    /**
     * Get matrix return code
     *
     * @return array
     */
    public function getMatrixReturnCode()
    {
        return [
            static::CHRONOPOST_REVERSE_R            => [
                [static::CHRONOPOST_REVERSE_R],
                [static::CHRONOPOST_REVERSE_R, static::CHRONOPOST_REVERSE_U]
            ],
            static::CHRONOPOST_REVERSE_S            => [
                [static::CHRONOPOST_REVERSE_S],
                [static::CHRONOPOST_REVERSE_R, static::CHRONOPOST_REVERSE_S],
                [static::CHRONOPOST_REVERSE_S, static::CHRONOPOST_REVERSE_U],
                [static::CHRONOPOST_REVERSE_R, static::CHRONOPOST_REVERSE_S, static::CHRONOPOST_REVERSE_U]
            ],
            static::CHRONOPOST_REVERSE_U            => [
                [static::CHRONOPOST_REVERSE_U]
            ],
            static::CHRONOPOST_REVERSE_RELAY_EUROPE => [
                [static::CHRONOPOST_REVERSE_RELAY_EUROPE]
            ],
            static::CHRONOPOST_REVERSE_T            => [
                [static::CHRONOPOST_REVERSE_T],
                [static::CHRONOPOST_REVERSE_R, static::CHRONOPOST_REVERSE_T],
                [static::CHRONOPOST_REVERSE_S, static::CHRONOPOST_REVERSE_T],
                [static::CHRONOPOST_REVERSE_T, static::CHRONOPOST_REVERSE_U],
                [static::CHRONOPOST_REVERSE_R, static::CHRONOPOST_REVERSE_S, static::CHRONOPOST_REVERSE_T],
                [static::CHRONOPOST_REVERSE_R, static::CHRONOPOST_REVERSE_T, static::CHRONOPOST_REVERSE_U],
                [static::CHRONOPOST_REVERSE_S, static::CHRONOPOST_REVERSE_T, static::CHRONOPOST_REVERSE_U],
                [
                    static::CHRONOPOST_REVERSE_R,
                    static::CHRONOPOST_REVERSE_S,
                    static::CHRONOPOST_REVERSE_T,
                    static::CHRONOPOST_REVERSE_U
                ]
            ],
            static::CHRONOPOST_REVERSE_DEFAULT      => [
                [static::CHRONOPOST_REVERSE_DEFAULT]
            ]
        ];
    }

    /**
     * Check if has option BAL
     *
     * @param Order $order
     *
     * @return bool
     */
    public function hasOptionBAL(Order $order)
    {
        $shippingMethod = $order->getShippingMethod() ? explode('_', $order->getShippingMethod()) : "";
        if (isset($shippingMethod[1])) {
            $shippingMethod = $shippingMethod[1];
            $carrier = $this->_carrierFactory->get($shippingMethod);

            return $carrier && $carrier->getIsChronoMethod() ? $carrier->optionBalEnable() : false;
        }

        return false;
    }

    /**
     * Get order
     *
     * @param string $orderId
     *
     * @return OrderInterface
     */
    public function getOrder(string $orderId)
    {
        if (!isset($this->order[$orderId])) {
            $this->order[$orderId] = $this->_orderRepository->get($orderId);
        }

        return $this->order[$orderId];
    }

    /**
     * Get ad valorem insurance by order id
     *
     * @param string $orderId
     *
     * @return int|mixed
     */
    public function getOrderAdValoremById(string $orderId)
    {
        $order = $this->getOrder($orderId);
        if ($order->getId()) {
            return $this->getOrderAdValorem($order);
        }

        return 0;
    }

    /**
     * Get ad valorem order
     *
     * @param Order|OrderRepositoryInterface $order
     *
     * @return int|mixed
     */
    public function getOrderAdValorem($order)
    {
        $totalAdValorem = 0;

        if ($this->getConfig('chronorelais/assurance/enabled')) {
            $minAmount = $this->getConfig('chronorelais/assurance/amount');
            $maxAmount = $this->getMaxAdValoremAmount();

            $items = $order->getAllItems();
            foreach ($items as $item) {
                if ($item->getParentItemId() === null) {
                    $totalAdValorem += $item->getPrice() * $item->getQtyOrdered();
                }
            }

            $totalAdValorem = min($totalAdValorem, $maxAmount);
            if ($totalAdValorem < $minAmount) {
                $totalAdValorem = 0;
            }
        }

        return $totalAdValorem;
    }

    /**
     * Get max ad valorem amount
     *
     * @return int
     */
    public function getMaxAdValoremAmount()
    {
        return 20000;
    }

    /**
     * Get specific contract
     *
     * @param string $id
     *
     * @return mixed|null
     */
    public function getSpecificContract($id)
    {
        if ($id !== null && (int)$id >= 0) {
            $contracts = $this->getConfigContracts();
            if (isset($contracts[$id])) {
                return $contracts[$id];
            }
        }

        return null;
    }

    /**
     * Get config contracts
     *
     * @param bool $JSONformat
     *
     * @return mixed
     */
    public function getConfigContracts($JSONformat = false)
    {
        $config = $this->scopeConfig->getValue('chronorelais/contracts/contracts', ScopeInterface::SCOPE_STORE);

        if ($JSONformat) {
            return $config;
        }

        return json_decode($config, true);
    }

    /**
     * Get carrier contract by code
     *
     * @param string $code
     *
     * @return mixed
     */
    public function getCarrierContract(string $code)
    {
        $contracts = $this->getConfigContracts();
        $numContract = $this->scopeConfig->getValue(
            'carriers/' . $code . '/contracts',
            ScopeInterface::SCOPE_STORE
        );

        $numContract = (!$numContract || count($contracts) - 1 < $numContract) ? 0 : $numContract;
        $contracts[$numContract]['numContract'] = (int)$numContract;

        return $contracts[$numContract];
    }

    /**
     * Get contract by order
     *
     * @param string $orderId
     *
     * @return false
     */
    public function getContractByOrderId(string $orderId)
    {
        $contract = false;
        $collection = $this->_contractsOrdersFactory->create()->getCollection()
            ->addFieldToFilter('order_id', $orderId);

        if (count($collection) !== 0) {
            $contract = $collection->getFirstItem();
        }

        return $contract;
    }

    /**
     * Get weight of specific order
     *
     * @param string $orderId
     * @param bool   $fromGrid
     *
     * @return float|int
     * @throws NoSuchEntityException
     */
    public function getWeightOfOrder(string $orderId, bool $fromGrid = false)
    {
        $order = $this->getOrder($orderId);

        $weightCoef = 1;
        if ($fromGrid === false) {
            $weightCoef = $this->getWeightCoef();
        }

        $totalWeight = 0;
        foreach ($order->getAllVisibleItems() as $item) {
            $weightUnit = $this->getWeightUnit($order->getId(), $order->getStoreId());
            if ($weightUnit === 'kgs') {
                $totalWeight += $item->getWeight() * $weightCoef * $item->getQtyOrdered();
            } elseif ($weightUnit === 'lbs') {
                $totalWeight += $item->getWeight() * 0.453592 * $weightCoef * $item->getQtyOrdered();
            } else {
                $totalWeight += $item->getWeight() * $item->getQtyOrdered();
            }
        }

        return $totalWeight;
    }

    /**
     * Get chronopost product code by code
     *
     * @param string $code
     *
     * @return string
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getChronoProductCode($code = '')
    {
        $code = strtolower($code);

        switch ($code) {
            case 'chronorelais':
                $productcode = static::CHRONORELAY;
                break;
            case 'chronoexpress':
                $productcode = static::CHRONO_EXPRESS;
                break;
            case 'chronopostc10':
                $productcode = static::CHRONOPOST_C10;
                break;
            case 'chronopostc18':
                $productcode = static::CHRONOPOST_C18;
                break;
            case 'chronocclassic':
                $productcode = static::CHRONOPOST_CCLASSIC;
                break;
            case 'chronorelaiseur':
                $productcode = static::CHRONO_RELAY_EUROPE;
                break;
            case 'chronorelaisdom':
                $productcode = static::CHRONO_RELAY_DOM;
                break;
            case 'chronosameday':
                $productcode = static::CHRONOPOST_SMD;
                break;
            case 'chronopostsrdv':
                $productcode = static::CHRONOPOST_SRDV;
                break;
            default:
                $productcode = static::CHRONO_POST;
                break;
        }

        return $productcode;
    }

    /**
     * Get chronopost product code to shipment
     *
     * @param string $code
     *
     * @return string
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getChronoProductCodeToShipment(string $code = '')
    {
        $code = strtolower($code);

        switch ($code) {
            case 'chronorelais':
                $productcode = static::CHRONORELAY;
                break;
            case 'chronopost':
                if ($this->getConfigOptionBAL()) {
                    $productcode = static::CHRONO_POST_BAL;
                } else {
                    $productcode = static::CHRONO_POST;
                }
                break;
            case 'chronoexpress':
                $productcode = static::CHRONO_EXPRESS;
                break;
            case 'chronopostc10':
                $productcode = static::CHRONOPOST_C10;
                break;
            case 'chronopostc18':
                if ($this->getConfigOptionBAL()) {
                    $productcode = static::CHRONOPOST_C18_BAL;
                } else {
                    $productcode = static::CHRONOPOST_C18;
                }
                break;
            case 'chronopostcclassic':
                $productcode = static::CHRONOPOST_CCLASSIC;
                break;
            case 'chronorelaiseurope':
                $productcode = static::CHRONO_RELAY_EUROPE;
                break;
            case 'chronorelaisdom':
                $productcode = static::CHRONO_RELAY_DOM;
                break;
            case 'chronopostsameday':
                $productcode = static::CHRONOPOST_SMD;
                break;
            case 'chronopostsrdv':
                $productcode = static::CHRONOPOST_SRDV;
                break;
            default:
                $productcode = static::CHRONO_POST;
                break;
        }

        return $productcode;
    }

    /**
     * Check if enable
     *
     * @return string|null
     */
    public function getConfigOptionBAL()
    {
        return $this->getConfig('chronorelais/optionbal/enabled');
    }

    /**
     * Shipper Information
     *
     * @param $field
     *
     * @return String
     */
    public function getConfigurationShipperInfo($field)
    {
        $fieldValue = '';
        if ($field && $this->getConfig('chronorelais/shipperinformation/' . $field)) {
            $fieldValue = $this->getConfig('chronorelais/shipperinformation/' . $field);
        }

        return $fieldValue;
    }

    /**
     * Get config file path
     *
     * @param string $name
     *
     * @return string
     */
    public function getConfigFilePath(string $name)
    {
        $path = $this->_moduleReader->getModuleDir('', 'Chronopost_Chronorelais');

        return $path . '/config/' . $name;
    }

    /**
     * Get country return allowed
     *
     * @param string $countryId
     * @param string $shippingMethod
     *
     * @return bool
     */
    public function returnAuthorized(string $countryId, string $shippingMethod)
    {
        if ($shippingMethod === 'chronorelaiseur') {
            return in_array($countryId, self::COUNTRY_ALLOWED_RETURN_EUROPE);
        }

        return in_array($countryId, ['FR']);
    }

    /**
     * Get label map
     *
     * @param string $label
     *
     * @return mixed
     */
    public function getLabelGmap($label)
    {
        return $this->getStoreConfigData('chronorelais/libelles_gmap/' . $label, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get store config data
     *
     * @param string $node
     * @param string $store
     *
     * @return mixed
     */
    public function getStoreConfigData(string $node, string $store)
    {
        return $this->scopeConfig->getValue($node, $store);
    }

    /**
     * Get weight limit
     *
     * @param string $shippingMethod
     *
     * @return int
     */
    public function getWeightLimit(string $shippingMethod)
    {
        if ($shippingMethod === 'chronorelaiseur_chronorelaiseur' || $shippingMethod === 'chronorelais_chronorelais') {
            return 20 * $this->getWeightCoef();
        }

        return 30 * $this->getWeightCoef();
    }

    /**
     * Get input dimensions limit
     *
     * @param string $shippingMethod
     *
     * @return int
     */
    public function getInputDimensionsLimit(string $shippingMethod)
    {
        if ($shippingMethod === 'chronorelaiseur_chronorelaiseur' || $shippingMethod === 'chronorelais_chronorelais') {
            return 100;
        }

        return 150;
    }

    /**
     * Get global dimensions limit
     *
     * @param string $shippingMethod
     *
     * @return int
     */
    public function getGlobalDimensionsLimit(string $shippingMethod)
    {
        if ($shippingMethod === 'chronorelaiseur_chronorelaiseur' || $shippingMethod === 'chronorelais_chronorelais') {
            return 250;
        }

        return 300;
    }

    /**
     * Get chrono weight unit
     *
     * @param string $orderId
     * @param string $storeId
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getChronoWeightUnit(string $orderId, $storeId = '')
    {
        if ($storeId === '') {
            $order = $this->getOrder($orderId);
            $storeId = $order->getStoreId();
        }

        $store = $this->_storeManager->getStore($storeId);

        return $this->scopeConfig->getValue(
            'chronorelais/weightunit/unit',
            ScopeInterface::SCOPE_STORE,
            (string)$store->getCode()
        );
    }

    /**
     * Get weight unit
     *
     * @param string $orderId
     * @param string $storeId
     *
     * @return null|string
     * @throws NoSuchEntityException
     */
    public function getWeightUnit(string $orderId, $storeId = '')
    {
        if ($storeId === '') {
            $order = $this->getOrder($orderId);
            $storeId = $order->getStoreId();
        }

        $store = $this->_storeManager->getStore($storeId);

        return $this->scopeConfig->getValue(
            'general/locale/weight_unit',
            ScopeInterface::SCOPE_STORE,
            (string)$store->getCode()
        );
    }

    /**
     * Get weight coefficient
     *
     * @return int
     */
    public function getWeightCoef()
    {
        $coefficient = 1;

        if ($this->scopeConfig->getValue('chronorelais/weightunit/unit') === 'g') {
            $coefficient = 1000; // Convert g to kg
        }

        return $coefficient;
    }
}
