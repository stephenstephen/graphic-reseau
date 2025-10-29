<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2017 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Model;

use Colissimo\Shipping\Api\Data\PickupInterface;
use Colissimo\Shipping\Model\Pickup\Collection;
use Colissimo\Shipping\Model\ResourceModel\Pickup as ResourceModel;
use Colissimo\Shipping\Helper\Data as ShippingHelper;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Pickup
 */
class Pickup extends DataObject implements PickupInterface
{

    /**
     * @var ShippingHelper $shippingHelper
     */
    protected $shippingHelper;

    /**
     * @var Collection $pickupCollection
     */
    protected $pickupCollection;

    /**
     * @var Soap $soap
     */
    protected $soap;

    /**
     * @var ResourceModel $pickup
     */
    protected $pickup;

    /**
     * @var ShippingData $shippingData
     */
    protected $shippingData;

    /**
     * @param Collection $pickupCollection
     * @param ShippingHelper $shippingHelper
     * @param Soap $soap
     * @param ResourceModel $pickup
     * @param ShippingData $shippingData
     * @param array $data
     */
    public function __construct(
        Collection $pickupCollection,
        ShippingHelper $shippingHelper,
        Soap $soap,
        ResourceModel $pickup,
        ShippingData $shippingData,
        array $data = []
    ) {
        $this->shippingHelper = $shippingHelper;
        $this->pickupCollection = $pickupCollection;
        $this->soap = $soap;
        $this->pickup = $pickup;
        $this->shippingData = $shippingData;
        parent::__construct($data);
    }

    /**
     * Retrieve Pickup List
     *
     * @param string $street
     * @param string $city
     * @param string $postcode
     * @param string $country
     * @return \Colissimo\Shipping\Model\Pickup\Collection
     */
    public function getList($street, $city, $postcode, $country)
    {
        return $this->pickupCollection->loadItems($this, $street, $city, $postcode, $country);
    }

    /**
     * Load specific pickup
     *
     * @param string $pickupId
     * @param string $network
     * @return \Colissimo\Shipping\Api\Data\PickupInterface
     */
    public function load($pickupId, $network)
    {
        if (!$pickupId || !$network) {
            return $this;
        }

        $data = [
            'date'        => date('d/m/Y'),
            'filterRelay' => '1',
            'id'          => $pickupId,
            'reseau'      => $network ?: '',
        ];

        $response = $this->soap->execute('findPointRetraitAcheminementByID', $data);

        if ($response['error']) {
            return $this;
        }

        if (isset($response['response']->pointRetraitAcheminement)) {
            foreach ($response['response']->pointRetraitAcheminement as $k => $v) {
                $key = preg_replace_callback(
                    '/([A-Z])/',
                    function ($m) {
                        return "_" . strtolower($m[1]);
                    },
                    $k
                );
                if (is_string($v)) {
                    $v = utf8_encode($v);
                }
                $this->setData(trim($key, '_'), $v);
            }
        }

        return $this;
    }

    /**
     * Retrieve current pickup for quote
     *
     * @param string|int $cartId
     *
     * @return $this
     * @throws LocalizedException
     */
    public function current($cartId)
    {
        $pickup = $this->getPickupAddress($cartId);

        if (is_array($pickup)) {
            $this->load($pickup['pickup_id'], $pickup['network_code']);
            $this->setTelephone($pickup['telephone']);
        }

        return $this;
    }

    /**
     * Retrieve current Pickup Address
     *
     * @param string|int $cartId
     *
     * @return string[]|false
     * @throws LocalizedException
     */
    public function getPickupAddress($cartId)
    {
        return $this->pickup->currentPickup($cartId);
    }

    /**
     * Save pickup data for quote
     *
     * @param string $cartId
     * @param string $pickupId
     * @param string $networkCode
     * @param string $telephone
     * @return bool
     * @throws LocalizedException
     */
    public function save($cartId, $pickupId, $networkCode, $telephone)
    {
        $this->load($pickupId, $networkCode);

        if (!$this->getIdentifiant()) {
            return false;
        }

        $street = array_filter(
            [trim($this->getAdresse1()), trim($this->getAdresse2())]
        );

        $address = [
            'company'     => trim($this->getNom()),
            'street'      => join("\n", $street),
            'postcode'    => trim($this->getCodePostal()),
            'city'        => trim($this->getLocalite()),
            'country_id'  => trim($this->getCodePays()),
            'pickup_type' => trim($this->getTypeDePoint()),
        ];

        return $this->pickup->savePickup($cartId, $pickupId, $networkCode, $telephone, $address);
    }

    /**
     * Reset pickup data for quote
     *
     * @param string $cartId
     * @return bool
     * @throws LocalizedException
     */
    public function reset($cartId)
    {
        return $this->pickup->resetPickup($cartId);
    }

    /**
     * Retrieve shipping data for order
     *
     * @param int $orderId
     * @return ShippingData
     */
    public function shippingData($orderId)
    {
        $shippingData = $this->shippingData;
        $data = $this->pickup->shippingData($orderId);

        $shippingData->setData($data);

        return $shippingData;
    }

    /**
     * Pickup name
     *
     * @return string
     */
    public function getNom()
    {
        return $this->getData('nom');
    }

    /**
     * Pickup address line 1
     *
     * @return string
     */
    public function getAdresse1()
    {
        return $this->getData('adresse1');
    }

    /**
     * Pickup address line 2
     *
     * @return string
     */
    public function getAdresse2()
    {
        return $this->getData('address2');
    }

    /**
     * Pickup address line 3
     *
     * @return string
     */
    public function getAdresse3()
    {
        return $this->getData('adresse3');
    }

    /**
     * Pickup postcode
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->getData('code_postal');
    }

    /**
     * Pickup city
     *
     * @return string
     */
    public function getLocalite()
    {
        return $this->getData('localite');
    }

    /**
     * Pickup country code
     *
     * @return string
     */
    public function getCodePays()
    {
        return $this->getData('code_pays');
    }

    /**
     * Pickup language
     *
     * @return string
     */
    public function getLangue()
    {
        return $this->getData('langue');
    }

    /**
     * Pickup country
     *
     * @return string
     */
    public function getLibellePays()
    {
        return $this->getData('libelle_pays');
    }

    /**
     * Pickup has parking
     *
     * @return string
     */
    public function getParking()
    {
        return $this->getData('parking');
    }

    /**
     * Pickup identifier
     *
     * @return string
     */
    public function getIdentifiant()
    {
        return $this->getData('identifiant');
    }

    /**
     * Pickup product code
     *
     * @return string
     */
    public function getTypeDePoint()
    {
        return $this->getData('type_de_point');
    }

    /**
     * Pickup network code
     *
     * @return string
     */
    public function getReseau()
    {
        return $this->getData('reseau');
    }

    /**
     * Pickup latitude
     *
     * @return string
     */
    public function getCoordGeolocalisationLatitude()
    {
        return $this->getData('coord_geolocalisation_latitude');
    }

    /**
     * Pickup longitude
     *
     * @return string
     */
    public function getCoordGeolocalisationLongitude()
    {
        return $this->getData('coord_geolocalisation_longitude');
    }

    /**
     * Pickup has handicap access
     *
     * @return int
     */
    public function getAccesPersonneMobiliteReduite()
    {
        return $this->getData('acces_personneMobilite_reduite');
    }

    /**
     * Pickup partial holidays
     *
     * @return string
     */
    public function getCongesPartiel()
    {
        return $this->getData('conges_partiel');
    }

    /**
     * Pickup distance from address in meter
     *
     * @return string
     */
    public function getDistanceEnMetre()
    {
        return $this->getData('distance_en_metre');
    }

    /**
     * Pickup monday opening
     *
     * @return string
     */
    public function getHorairesOuvertureLundi()
    {
        return $this->formatOpening(
            $this->getData('horaires_ouverture_lundi')
        );
    }

    /**
     * Pickup tuesday opening
     *
     * @return string
     */
    public function getHorairesOuvertureMardi()
    {
        return $this->formatOpening(
            $this->getData('horaires_ouverture_mardi')
        );
    }

    /**
     * Pickup wednesday opening
     *
     * @return string
     */
    public function getHorairesOuvertureMercredi()
    {
        return $this->formatOpening(
            $this->getData('horaires_ouverture_mercredi')
        );
    }

    /**
     * Pickup thursday opening
     *
     * @return string
     */
    public function getHorairesOuvertureJeudi()
    {
        return $this->formatOpening(
            $this->getData('horaires_ouverture_jeudi')
        );
    }

    /**
     * Pickup friday opening
     *
     * @return string
     */
    public function getHorairesOuvertureVendredi()
    {
        return $this->formatOpening(
            $this->getData('horaires_ouverture_vendredi')
        );
    }

    /**
     * Pickup saturday opening
     *
     * @return string
     */
    public function getHorairesOuvertureSamedi()
    {
        return $this->formatOpening(
            $this->getData('horaires_ouverture_samedi')
        );
    }

    /**
     * Pickup sunday opening
     *
     * @return string
     */
    public function getHorairesOuvertureDimanche()
    {
        return $this->formatOpening(
            $this->getData('horaires_ouverture_dimanche')
        );
    }

    /**
     * Pickup localisation tip
     *
     * @return string
     */
    public function getIndiceDeLocalisation()
    {
        return $this->getData('indice_de_localisation');
    }

    /**
     * Pickup activity beginning
     *
     * @return string
     */
    public function getPeriodeActiviteHoraireDeb()
    {
        return $this->getData('periode_activite_horaire_deb');
    }

    /**
     * Pickup activity ending
     *
     * @return string
     */
    public function getPeriodeActiviteHoraireFin()
    {
        return $this->getData('periode_activite_horaire_fin');
    }

    /**
     * Pickup maximum weight
     *
     * @return string
     */
    public function getPoidsMaxi()
    {
        return $this->getData('poids_maxi');
    }

    /**
     * Pickup has handling tool
     *
     * @return string
     */
    public function getLoanOfHandlingTool()
    {
        return $this->getData('loan_of_handling_tool');
    }

    /**
     * Pickup data for pickup shipping label
     *
     * @return string
     */
    public function getDistributionSort()
    {
        return $this->getData('distribution_sort');
    }

    /**
     * Pickup data for pickup shipping label
     *
     * @return string
     */
    public function getLotAcheminement()
    {
        return $this->getData('lot_acheminement');
    }

    /**
     * Pickup data for pickup shipping label
     *
     * @return string
     */
    public function getVersionPlanTri()
    {
        return $this->getData('version_plan_tri');
    }

    /**
     * Pickup Holidays
     *
     * @return string[]|null
     */
    public function getListeConges()
    {
        return is_object($this->getData('liste_conges')) ?
            [
                'calendarDeDebut' => $this->getData('liste_conges')->calendarDeDebut,
                'calendarDeFin'   => $this->getData('liste_conges')->calendarDeFin
            ]
            : null;
    }

    /**
     * Format opening day
     *
     * @param string $day
     * @return string|null
     */
    protected function formatOpening($day)
    {
        $date = trim(
            preg_replace(
                ['/00:00-00:00/', '/:/', '/ /', '/-/'],
                ['', 'h', ' / ', ' - '],
                $day
            ),
            ' / '
        );

        return $date ?: null;
    }

    /**
     * Retrieve Customer telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->getData('telephone');
    }

    /**
     * Set Customer telephone
     *
     * @param string $telephone
     * @return $this
     */
    public function setTelephone($telephone)
    {
        return $this->setData('telephone', $telephone);
    }
}
