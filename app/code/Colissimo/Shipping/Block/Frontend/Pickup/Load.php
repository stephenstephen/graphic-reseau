<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2017 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Block\Frontend\Pickup;

use Colissimo\Shipping\Model\Pickup;
use Colissimo\Shipping\Helper\Data as ShippingHelper;
use Colissimo\Shipping\Model\Pickup\Collection;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Directory\Model\ResourceModel\Country\CollectionFactory;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Load
 */
class Load extends Template
{

    /**
     * @var Collection $list
     */
    protected $list;

    /**
     * @var Pickup $pickupManager
     */
    protected $pickupManager;

    /**
     * @var ShippingHelper $shippingHelper
     */
    protected $shippingHelper;

    /**
     * @var CollectionFactory $countryFactory
     */
    protected $countryFactory;

    /**
     * @param Context $context
     * @param Pickup $pickupManager
     * @param ShippingHelper $shippingHelper
     * @param CollectionFactory $countryFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Pickup $pickupManager,
        ShippingHelper $shippingHelper,
        CollectionFactory $countryFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->countryFactory = $countryFactory;
        $this->pickupManager  = $pickupManager;
        $this->shippingHelper = $shippingHelper;
    }

    /**
     * Load Pickup
     *
     * @return \Colissimo\Shipping\Model\Pickup\Collection
     */
    public function getList()
    {
        if (is_null($this->list)) {
            $this->list = $this->pickupManager->getList(
                $this->getStreet(),
                $this->getCity(),
                $this->getPostcode(),
                $this->getCountryId()
            );
        }

        return $this->list;
    }

    /**
     * Retrieve if all form field are empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return !($this->getCity() || $this->getPostcode());
    }

    /**
     * Retrieve Pickup as Json
     *
     * @return string
     */
    public function getJson()
    {
        $collection = $this->getList();

        $list = [];

        /** @var \Colissimo\Shipping\Model\Pickup $item */
        foreach ($collection as $item) {
            $list[] = [
                '<strong>' . $item->getNom() . '</strong><br />' . $item->getData('adresse1'),
                $item->getCoordGeolocalisationLatitude(),
                $item->getCoordGeolocalisationLongitude(),
                'sc-pickup-' . $item->getIdentifiant(),
                $this->getViewFileUrl('Colissimo_Shipping::images/icons/colissimo.png'),
            ];
        }

        return json_encode($list);
    }


    /**
     * Retrieve map type
     *
     * @return string
     */
    public function getMapType()
    {
        return $this->_scopeConfig->getValue(
            ShippingHelper::COLISSIMO_CONFIG_PREFIX . 'pickup/map_type',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve Countries
     *
     * @return array
     */
    public function getCountries()
    {
        $config = $this->_scopeConfig->getValue(
            ShippingHelper::COLISSIMO_CONFIG_PREFIX . 'pickup/specificcountry',
            ScopeInterface::SCOPE_STORE
        );

        $ids = [];

        if ($config) {
            $ids = explode(',', $config);
        }

        if (empty($ids)) {
            $ids[] = $this->getCountryId();
        }

        /** @var \Magento\Directory\Model\ResourceModel\Country\Collection $collection */
        $collection = $this->countryFactory->create();

        return $collection->addCountryIdFilter($ids)->toOptionArray(false);
    }

    /**
     * Retrieve Full Street
     *
     * @param \Magento\Framework\DataObject $pickup
     * @param string $separator
     * @return string
     */
    public function getFullStreet($pickup, $separator)
    {
        return $pickup->getData('adresse1') .
        ($pickup->getData('adresse2') ? $separator . $pickup->getData('adresse2') : '') .
        ($pickup->getData('adresse3') ? $separator . $pickup->getData('adresse3') : '');
    }

    /**
     * Check holiday
     *
     * @param Pickup $pickup
     * @return bool|object
     */
    public function isHoliday($pickup)
    {
        return $pickup->getListeConges();
    }

    /**
     * Retrieve Holiday Start
     *
     * @param Pickup $pickup
     * @return string
     */
    public function getHolidayStart($pickup)
    {
        if ($this->isHoliday($pickup)) {
            return date('d/m/Y', strtotime($pickup->getListeConges()['calendarDeDebut']));
        }
        return '';
    }

    /**
     * Retrieve Holiday End
     *
     * @param Pickup $pickup
     * @return string
     */
    public function getHolidayEnd($pickup)
    {
        if ($this->isHoliday($pickup)) {
            return date('d/m/Y', strtotime($pickup->getListeConges()['calendarDeFin']));
        }
        return '';
    }

    /**
     * Retrieve Telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->getData('telephone');
    }

    /**
     * Retrieve street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->getData('street');
    }

    /**
     * Retrieve postcode
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->getData('postcode');
    }

    /**
     * Retrieve city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->getData('city');
    }

    /**
     * Retrieve country id
     *
     * @return string
     */
    public function getCountryId()
    {
        return $this->shippingHelper->getCountry($this->getData('country_id'), $this->getPostcode());
    }

    /**
     * Retrieve phone regex by country
     *
     * @return string|int
     */
    public function getMobilePhoneRegex()
    {
        $phoneData = $this->getMobilePhoneData();

        if (!$phoneData) {
            return 0;
        }
        if (!isset($phoneData['regex'])) {
            return 0;
        }

        return $phoneData['regex'];
    }

    /**
     * Retrieve phone code by country
     *
     * @return string
     */
    public function getMobilePhoneCode()
    {
        $phoneData = $this->getMobilePhoneData();

        if (!$phoneData) {
            return '';
        }
        if (!isset($phoneData['code'])) {
            return '';
        }

        return $phoneData['code'];
    }

    /**
     * Retrieve mobile phone data
     *
     * @return array|false
     */
    public function getMobilePhoneData()
    {
        return $this->shippingHelper->getMobilePhoneData($this->getCountryId());
    }

    /**
     * Address setter
     *
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->setData('street', $street);
    }

    /**
     * Postcode setter
     *
     * @param string $postcode
     */
    public function setPostcode($postcode)
    {
        $this->setData('postcode', $postcode);
    }

    /**
     * City setter
     *
     * @param string $city
     */
    public function setCity($city)
    {
        $this->setData('city', $city);
    }

    /**
     * Country setter
     *
     * @param string $countryId
     */
    public function setCountryId($countryId)
    {
        $this->setData('country_id', $countryId);
    }
}
