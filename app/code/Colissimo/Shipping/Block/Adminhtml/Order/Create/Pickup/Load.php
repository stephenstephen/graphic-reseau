<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Block\Adminhtml\Order\Create\Pickup;

use Colissimo\Shipping\Model\Carrier\Colissimo;
use Colissimo\Shipping\Model\Pickup as PickupManager;
use Colissimo\Shipping\Helper\Data as ShippingHelper;
use Colissimo\Shipping\Model\Pickup\Collection;
use Magento\Sales\Block\Adminhtml\Order\Create\AbstractCreate;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Session\Quote;
use Magento\Sales\Model\AdminOrder\Create;
use Magento\Quote\Model\Quote\Address;

/**
 * Class Load
 */
class Load extends AbstractCreate
{
    /**
     * @var Collection $list
     */
    protected $list = null;

    /**
     * @var string $current
     */
    protected $current = null;

    /**
     * @var PickupManager $pickupManager
     */
    protected $pickupManager;

    /**
     * @var ShippingHelper $shippingHelper
     */
    protected $shippingHelper;

    /**
     * @param Context $context
     * @param Quote $sessionQuote
     * @param Create $orderCreate
     * @param PriceCurrencyInterface $priceCurrency
     * @param PickupManager $pickupManager
     * @param ShippingHelper $shippingHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Quote $sessionQuote,
        Create $orderCreate,
        PriceCurrencyInterface $priceCurrency,
        PickupManager $pickupManager,
        ShippingHelper $shippingHelper,
        array $data = []
    ) {
        $this->pickupManager  = $pickupManager;
        $this->shippingHelper = $shippingHelper;
        parent::__construct($context, $sessionQuote, $orderCreate, $priceCurrency, $data);
    }

    /**
     * Retrieve if pickup location can be shown
     *
     * @return bool
     */
    public function canShow()
    {
        return $this->getAddress()->getShippingMethod() == $this->getPickupMethod();
    }

    /**
     * Retrieve current selected pickup
     *
     * @return string
     */
    public function getCurrent()
    {
        if (is_null($this->current)) {
            $current = $this->pickupManager->current($this->getQuote()->getId());
            $this->current = $current->getIdentifiant();
        }

        return $this->current;
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
     * Retrieve street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->getAddress()->getStreetLine(1);
    }

    /**
     * Retrieve postcode
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->getAddress()->getPostcode();
    }

    /**
     * Retrieve city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->getAddress()->getCity();
    }

    /**
     * Retrieve country id
     *
     * @return string
     */
    public function getCountryId()
    {
        return $this->shippingHelper->getCountry(
            $this->getAddress()->getCountryId(),
            $this->getAddress()->getPostcode()
        );
    }

    /**
     * Retrieve Shipping Address data
     *
     * @return Address
     */
    public function getAddress()
    {
        return $this->getQuote()->getShippingAddress();
    }

    /**
     * Retrieve Pickup Method
     *
     * @return string
     */
    public function getPickupMethod()
    {
        return Colissimo::SHIPPING_CARRIER_PICKUP_METHOD;
    }
}
