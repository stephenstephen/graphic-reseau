<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2019 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Helper;

use Colissimo\Shipping\Model\ResourceModel\Price\CollectionFactory;
use Colissimo\Shipping\Api\Data\PriceInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Directory\Model\RegionFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Zend_Measure_Weight;

/**
 * Class Data
 */
class Data extends AbstractHelper
{
    const COLISSIMO_CONFIG_PREFIX = 'carriers/colissimo/';

    const COLISSIMO_IMPORT_DIRECTORY = 'colissimo/prices';

    const COLISSIMO_EXPORT_FILENAME  = 'colissimo-prices.csv';

    const COLISSIMO_IMPORT_MODE_APPEND = 1;

    const COLISSIMO_IMPORT_MODE_ERASE  = 2;

    /**
     * @var RegionFactory $regionFactory
     */
    protected $regionFactory;

    /**
     * @var CollectionFactory $collectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Filesystem $fileSystem
     */
    protected $fileSystem;

    /**
     * @param Context $context
     * @param RegionFactory $regionFactory
     * @param CollectionFactory $collectionFactory
     * @param Filesystem $fileSystem
     */
    public function __construct(
        Context $context,
        RegionFactory $regionFactory,
        CollectionFactory $collectionFactory,
        Filesystem $fileSystem
    ) {
        $this->regionFactory = $regionFactory;
        $this->collectionFactory = $collectionFactory;
        $this->fileSystem = $fileSystem;
        parent::__construct($context);
    }

    /**
     * Retrieve configuration value
     *
     * @param string $path
     * @return array|string|bool
     */
    public function getConfig($path)
    {
        $config = [
            'homecl' => [
                'product_code' => 'DOM',
                'country' => [
                    'FR' => [
                        'max_weight' => 30,
                    ],
                    'BE' => [
                        'max_weight' => 30,
                    ],
                    'CH' => [
                        'max_weight' => 30,
                    ],
                ],
            ],
            'homesi' => [
                'product_code' => 'DOS',
                'country' => [
                    'FR' => [
                        'max_weight' => 30,
                    ],
                    'BE' => [
                        'max_weight' => 30,
                    ],
                    'NL' => [
                        'max_weight' => 30,
                    ],
                    'DE' => [
                        'max_weight' => 30,
                    ],
                    'GB' => [
                        'max_weight' => 30,
                    ],
                    'LU' => [
                        'max_weight' => 30,
                    ],
                    'ES' => [
                        'max_weight' => 30,
                    ],
                    'AT' => [
                        'max_weight' => 30,
                    ],
                    'EE' => [
                        'max_weight' => 30,
                    ],
                    'HU' => [
                        'max_weight' => 30,
                    ],
                    'LV' => [
                        'max_weight' => 30,
                    ],
                    'LT' => [
                        'max_weight' => 30,
                    ],
                    'CZ' => [
                        'max_weight' => 30,
                    ],
                    'SK' => [
                        'max_weight' => 30,
                    ],
                    'SI' => [
                        'max_weight' => 30,
                    ],
                    'CH' => [
                        'max_weight' => 30,
                    ],
                    'PT' => [
                        'max_weight' => 30,
                    ],
                    'BG' => [
                        'max_weight' => 30,
                    ],
                    'CY' => [
                        'max_weight' => 30,
                    ],
                    'HR' => [
                        'max_weight' => 30,
                    ],
                    'DK' => [
                        'max_weight' => 30,
                    ],
                    'FI' => [
                        'max_weight' => 30,
                    ],
                    'GR' => [
                        'max_weight' => 30,
                    ],
                    'IE' => [
                        'max_weight' => 30,
                    ],
                    'IS' => [
                        'max_weight' => 30,
                    ],
                    'IT' => [
                        'max_weight' => 30,
                    ],
                    'MT' => [
                        'max_weight' => 30,
                    ],
                    'NO' => [
                        'max_weight' => 30,
                    ],
                    'PL' => [
                        'max_weight' => 30,
                    ],
                    'SE' => [
                        'max_weight' => 30,
                    ],
                    'RO' => [
                        'max_weight' => 30,
                    ],
                ],
            ],
            'domtomcl' => [
                'product_code' => 'COM',
                'country' => '::domtomCountriesConfig',
            ],
            'domtomsi' => [
                'product_code' => 'CDS',
                'country' => '::domtomCountriesConfig',
            ],
            'domtomeco' => [
                'product_code' => 'ECO',
                'country' => '::domtomCountriesConfig',
            ],
            'international' => [
                'product_code' => 'COLI',
                'country' => '::internationalCountriesConfig',
            ],
            'pickup' => [
                'country' => [
                    'FR' => [
                        'option_inter' => '0',
                        'max_weight'   => 30,
                    ],
                    'BE' => [
                        'option_inter' => '2',
                        'max_weight'   => 20,
                    ],
                    'NL' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'DE' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'LU' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'ES' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'PT' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'AT' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'LT' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'LV' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'EE' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'SE' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'PL' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'CZ' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'DK' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'HU' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'SI' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'SK' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                    'FI' => [
                        'option_inter' => '1',
                        'max_weight'   => 20,
                    ],
                ],
            ],
        ];

        $keys = explode('/', $path);

        $skip = false;
        foreach ($keys as $i => $key) {
            if ($skip) {
                $skip = false;
                continue;
            }
            if (isset($config[$key])) {
                $config = $config[$key];
                if (is_string($config)) {
                    if (preg_match('/^::/', $config)) {
                        $method = preg_replace('/^::/', '', $config);
                        $config = $this->$method();
                        $skip = true;
                    }
                }
            } else {
                $config = false;
                break;
            }
        }

        return $config;
    }

    /**
     * Retrieve International country
     *
     * @return array
     */
    protected function internationalCountriesConfig()
    {
        return [
            'max_weight' => 30,
        ];
    }

    /**
     * Retrieve Dom-Tom country
     *
     * @return array
     */
    protected function domtomCountriesConfig()
    {
        return [
            'max_weight' => 30,
        ];
    }

    /**
     * Retrieve country
     *
     * @param string $countryId
     * @param string $postcode
     * @return string
     */
    public function getCountry($countryId, $postcode = null)
    {
        if ($countryId == 'MC') { // Monaco
            $countryId = 'FR';
        }

        if ($countryId == 'AD') { // Andorre
            $countryId = 'FR';
        }

        if ($postcode) {
            if ($countryId == 'FR') {
                $countryId = $this->getDomTomCountry($postcode);
            }
        }

        return $countryId;
    }

    /**
     * Retrieve Dom Tom Country with postcode
     *
     * @param string $postcode
     * @return string
     */
    public function getDomTomCountry($postcode)
    {
        $countryId = 'FR';
        $postcodes = $this->getDomTomPostcodes();

        $postcode = preg_replace('/\s+/', '', $postcode);
        foreach ($postcodes as $code => $regex) {
            if (preg_match($regex, $postcode)) {
                $countryId = $code;
                break;
            }
        }
        return $countryId;
    }

    /**
     * Retrieve Dom-Tom countries code ISO-2
     *
     * @return array
     */
    public function getDomTomCountries()
    {
        return [
            'GP', // Guadeloupe
            'MQ', // Martinique
            'GF', // Guyane
            'RE', // La réunion
            'PM', // St-Pierre-et-Miquelon
            'YT', // Mayotte
            'TF', // Terres-Australes
            'WF', // Wallis-et-Futuna
            'PF', // Polynésie Française
            'NC', // Nouvelle-Calédonie
            'BL', // Saint-Barthélemy
            'MF', // Saint-Martin (partie française)
        ];
    }

    /**
     * Retrieve Dom-Tom postcodes
     *
     * @return array
     */
    public function getDomTomPostcodes()
    {
        return [
            'BL' => '/^97133$/', // Saint-Barthélemy
            'MF' => '/^97150$/', // Saint-Martin (partie française)
            'GP' => '/^971[0-9]{2}$/', // Guadeloupe
            'MQ' => '/^972[0-9]{2}$/', // Martinique
            'GF' => '/^973[0-9]{2}$/', // Guyane
            'RE' => '/^974[0-9]{2}$/', // La réunion
            'PM' => '/^975[0-9]{2}$/', // St-Pierre-et-Miquelon
            'YT' => '/^976[0-9]{2}$/', // Mayotte
            'TF' => '/^984[0-9]{2}$/', // Terres-Australes
            'WF' => '/^986[0-9]{2}$/', // Wallis-et-Futuna
            'PF' => '/^987[0-9]{2}$/', // Polynésie Française
            'NC' => '/^988[0-9]{2}$/', // Nouvelle-Calédonie
        ];
    }

    /**
     * Retrieve region
     *
     * @param string $countryId
     * @param string $postcode
     * @return \Magento\Directory\Model\Region
     */
    public function getRegion($countryId, $postcode)
    {
        $code = (int)substr($postcode, 0, 2);
        if ($code == 20) {
            $code = (int)$postcode >= 20200 ? '2B' : '2A';
        }
        if ($this->getDomTomCountry($postcode) != 'FR') {
            $countryId = 'FR';
            $code = 'OM';
        }
        $instance = $this->regionFactory->create();

        return $instance->loadByCode($code, $countryId);
    }

    /**
     * Retrieve API configuration
     *
     * @param int $storeId
     * @param int $websiteId
     * @return array
     */
    public function getApiConfig($storeId = null, $websiteId = null)
    {
        $scopeType = ScopeInterface::SCOPE_STORE;
        $scopeCode = $storeId;
        if (!$storeId && $websiteId) {
            $scopeType = ScopeInterface::SCOPE_WEBSITE;
            $scopeCode = $websiteId;
        }

        $prefix = self::COLISSIMO_CONFIG_PREFIX;

        return [
            'wsdl'     => 'https://ws.colissimo.fr/pointretrait-ws-cxf/PointRetraitServiceWS/2.0?wsdl',
            'login'    => $this->scopeConfig->getValue($prefix . 'pickup/account_number', $scopeType, $scopeCode),
            'password' => $this->scopeConfig->getValue($prefix . 'pickup/account_password', $scopeType, $scopeCode),
        ];
    }

    /**
     * Retrieve default country
     *
     * @return string
     */
    public function getDefaultCountry()
    {
        return $this->scopeConfig->getValue(
            'general/country/default',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve Mobile Phone data
     *
     * @param string $countryId
     * @return array|false
     */
    public function getMobilePhoneData($countryId)
    {
        $data = [
            'FR' => [
                'regex'   => '/^0(6|7)[0-9]{8}$/',
                'example' => '0610203040',
                'code'    => '',
            ],
            'BE' => [
                'regex'   => '/^\+324[0-9]{8}$/',
                'example' => '+32 410203040',
                'code'    => '+32',
            ],
            'NL' => [
                'regex'   => '/^\+316[0-9]{8}$/',
                'example' => '+31 610203040',
                'code'    => '+31',
            ],
            'DE' => [
                'regex'   => '/^\+491[5-7]{1}[0-9]{7,9}$/',
                'example' => '+49 161020304',
                'code'    => '+49',
            ],
            'GB' => [
                'regex'   => '/^\+447[3-9]{1}[0-9]{8}$/',
                'example' => '+44 7510203040',
                'code'    => '+44',
            ],
            'LU' => [
                'regex'   => '/^\+3526[0-9]{8}$/',
                'example' => '+352 621203040',
                'code'    => '+352',
            ],
            'ES' => [
                'regex'   => '/^\+346[0-9]{8}$/',
                'example' => '+34 610203040',
                'code'    => '+34',
            ],
            'AT' => [
                'regex'   => '/^\+43/',
                'example' => '+43 Z XXX XXX',
                'code'    => '+43',
            ],
            'EE' => [
                'regex'   => '/^\+372/',
                'example' => '+372 Z XXX XXX',
                'code'    => '+372',
            ],
            'HU' => [
                'regex'   => '/^\+36/',
                'example' => '+36 Z XXX XXX',
                'code'    => '+36',
            ],
            'LV' => [
                'regex'   => '/^\+371/',
                'example' => '+371 Z XXX XXX',
                'code'    => '+371',
            ],
            'LT' => [
                'regex'   => '/^\+370/',
                'example' => '+370 Z XXX XXX',
                'code'    => '+370',
            ],
            'CZ' => [
                'regex'   => '/^\+420/',
                'example' => '+420 Z XXX XXX',
                'code'    => '+420',
            ],
            'SK' => [
                'regex'   => '/^\+421/',
                'example' => '+421 Z XXX XXX',
                'code'    => '+421',
            ],
            'SI' => [
                'regex'   => '/^\+386/',
                'example' => '+386 Z XXX XXX',
                'code'    => '+386',
            ],
            'CH' => [
                'regex'   => '/^\+41/',
                'example' => '+41 Z XXX XXX',
                'code'    => '+41',
            ],
            'PT' => [
                'regex'   => '/^\+351/',
                'example' => '+351 Z XXX XXX',
                'code'    => '+351',
            ],
            'BG' => [
                'regex'   => '/^\+359/',
                'example' => '+359 Z XXX XXX',
                'code'    => '+359',
            ],
            'CY' => [
                'regex'   => '/^\+357/',
                'example' => '+357 Z XXX XXX',
                'code'    => '+357',
            ],
            'HR' => [
                'regex'   => '/^\+385/',
                'example' => '+385 Z XXX XXX',
                'code'    => '+385',
            ],
            'DK' => [
                'regex'   => '/^\+45/',
                'example' => '+45 Z XXX XXX',
                'code'    => '+45',
            ],
            'FI' => [
                'regex'   => '/^\+358/',
                'example' => '+358 Z XXX XXX',
                'code'    => '+358',
            ],
            'GR' => [
                'regex'   => '/^\+30/',
                'example' => '+30 Z XXX XXX',
                'code'    => '+30',
            ],
            'IE' => [
                'regex'   => '/^\+353/',
                'example' => '+353 Z XXX XXX',
                'code'    => '+353',
            ],
            'IS' => [
                'regex'   => '/^\+354/',
                'example' => '+354 Z XXX XXX',
                'code'    => '+354',
            ],
            'IT' => [
                'regex'   => '/^\+39/',
                'example' => '+39 Z XXX XXX',
                'code'    => '+39',
            ],
            'MT' => [
                'regex'   => '/^\+356/',
                'example' => '+356 Z XXX XXX',
                'code'    => '+356',
            ],
            'NO' => [
                'regex'   => '/^\+47/',
                'example' => '+47 Z XXX XXX',
                'code'    => '+47',
            ],
            'PL' => [
                'regex'   => '/^\+48/',
                'example' => '+48 Z XXX XXX',
                'code'    => '+48',
            ],
            'SE' => [
                'regex'   => '/^\+46/',
                'example' => '+46 Z XXX XXX',
                'code'    => '+46',
            ],
            'RO' => [
                'regex'   => '/^\+40/',
                'example' => '+40 Z XXX XXX',
                'code'    => '+40',
            ],
        ];

        if (isset($data[$countryId])) {
            return $data[$countryId];
        }

        return false;
    }

    /**
     * Retrieve default address
     *
     * @return array
     */
    public function getDefaultAddress()
    {
        $address = [
            'street'     => '',
            'city'       => '',
            'postcode'   => '',
            'country_id' => $this->getDefaultCountry(),
            'telephone'  => '',
        ];

        $path = self::COLISSIMO_CONFIG_PREFIX . 'pickup/apply_default';

        if ($this->scopeConfig->isSetFlag($path, ScopeInterface::SCOPE_STORE)) {
            $address['city'] = $this->scopeConfig->getValue(
                self::COLISSIMO_CONFIG_PREFIX . 'pickup/default_city',
                ScopeInterface::SCOPE_STORE
            );
            $address['postcode'] = $this->scopeConfig->getValue(
                self::COLISSIMO_CONFIG_PREFIX . 'pickup/default_postcode',
                ScopeInterface::SCOPE_STORE
            );
            $address['country_id'] = $this->scopeConfig->getValue(
                self::COLISSIMO_CONFIG_PREFIX . 'pickup/default_country',
                ScopeInterface::SCOPE_STORE
            );
        }

        return $address;
    }

    /**
     * Convert weight between lbs and kgs
     *
     * @param float $weight
     * @param string $from
     * @param string $to
     * @return float
     */
    public function convertWeight($weight, $from, $to)
    {
        $kgs = ['kgs', Zend_Measure_Weight::KILOGRAM];
        $lbs = ['lbs', Zend_Measure_Weight::POUND];

        if (in_array($from, $kgs) && in_array($to, $lbs)) {
            $weight = $weight * 2.20462;
        }

        if (in_array($from, $lbs) && in_array($to, $kgs)) {
            $weight = $weight / 2.20462;
        }

        if ($weight < 0.01) {
            $weight = 0.01;
        }

        return $weight;
    }

    /**
     * Retrieve store weight unit
     *
     * @param int $storeId
     * @param bool $fullName
     * @return string
     */
    public function getStoreWeightUnit($storeId = null, $fullName = false)
    {
        $value = $this->scopeConfig->getValue(
            'general/locale/weight_unit',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if ($fullName) {
            if ($value == 'kgs') {
                $value = Zend_Measure_Weight::KILOGRAM;
            }
            if ($value == 'lbs') {
                $value = Zend_Measure_Weight::POUND;
            }
        }

        return $value;
    }

    /**
     * Retrieve weight calculation
     *
     * @param int $storeId
     * @return string
     */
    public function getWeightCalculation($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'carriers/colissimo/weight_calculation',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Retrieve shipping price value
     *
     * @param string $method
     * @param string $countryId
     * @param float $weight
     * @param int $storeId
     *
     * @return float|null
     */
    public function getShippingPrice($method, $countryId, $weight, $storeId)
    {
        $instance = $this->collectionFactory->create();

        $instance->clear();

        $price = $instance->addFieldToFilter(PriceInterface::METHOD, $method)
            ->addFieldToFilter(PriceInterface::IS_ACTIVE, 1)
            ->addFieldToFilter(PriceInterface::COUNTRY_ID, $countryId)
            ->addFieldToFilter(PriceInterface::WEIGHT_FROM, [['lteq' => $weight], ['null' => true]])
            ->addFieldToFilter(PriceInterface::WEIGHT_TO, [['gt' => $weight], ['null' => true]])
            ->addFieldToFilter(PriceInterface::STORE_ID, [['eq' => $storeId], ['eq' => 0]])
            ->setOrder(PriceInterface::STORE_ID, 'DESC')
            ->setOrder(PriceInterface::WEIGHT_FROM, 'ASC')
            ->setPageSize(1);

        return $price->getFirstItem()->getData(PriceInterface::PRICE) ?: null;
    }

    /**
     * Retrieve upload directory
     *
     * @return string
     */
    public function getImportUploadDir()
    {
        $directory = $this->fileSystem->getDirectoryRead(DirectoryList::VAR_DIR);

        return $directory->getAbsolutePath(self::COLISSIMO_IMPORT_DIRECTORY);
    }

    /**
     * Retrieve Export File Name
     *
     * @return string
     */
    public function getExportFileName()
    {
        return self::COLISSIMO_EXPORT_FILENAME;
    }

    /**
     * Retrieve Price CSV required fields
     *
     * @return array
     */
    public function getCsvPriceFields()
    {
        return [
            PriceInterface::METHOD,
            PriceInterface::STORE_ID,
            PriceInterface::COUNTRY_ID,
            PriceInterface::WEIGHT_FROM,
            PriceInterface::WEIGHT_TO,
            PriceInterface::PRICE,
            PriceInterface::IS_ACTIVE
        ];
    }
}
