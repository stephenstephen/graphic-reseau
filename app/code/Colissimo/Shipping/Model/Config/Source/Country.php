<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Model\Config\Source;

use Magento\Directory\Model\Config\Source\Country as DirectoryCountry;
use Magento\Directory\Model\ResourceModel\Country\Collection;
use Colissimo\Shipping\Helper\Data as ShippingHelper;

/**
 * Class Country
 */
class Country extends DirectoryCountry
{

    /**
     * @var ShippingHelper $shippingHelper
     */
    protected $shippingHelper;

    /**
     * @var array $countries
     */
    protected $countries;

    /**
     * @param Collection $countryCollection
     * @param ShippingHelper $shippingHelper
     */
    public function __construct(
        Collection $countryCollection,
        ShippingHelper $shippingHelper
    ) {
        $this->shippingHelper = $shippingHelper;
        $this->setCountries();
        parent::__construct($countryCollection);
    }

    /**
     * Options getter
     *
     * @param boolean $isMultiselect
     * @param string|array $foregroundCountries
     * @return array
     */
    public function toOptionArray($isMultiselect = false, $foregroundCountries = '')
    {
        if (is_array($this->countries)) {
            if (!empty($this->countries)) {
                $this->_countryCollection->addCountryIdFilter($this->countries);
            }
        }

        return $this->_countryCollection->loadData()->toOptionArray(false);
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        if (is_array($this->countries)) {
            if (!empty($this->countries)) {
                $this->_countryCollection->addCountryIdFilter($this->countries);
            }
        }

        return $this->_countryCollection->loadData()->toArray(false);
    }

    /**
     * Countries setter
     */
    public function setCountries()
    {
        $this->countries = [];
    }
}
