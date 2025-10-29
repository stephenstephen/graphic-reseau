<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Model;

use Colissimo\Shipping\Model\Carrier\Colissimo;
use Colissimo\Shipping\Helper\Data as ShippingHelper;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Checkout\Model\Session;
use Magento\Quote\Api\Data\AddressExtensionFactory;
use Magento\Store\Model\ScopeInterface;

/**
 * Class ColissimoConfigProvider
 */
class ColissimoConfigProvider implements ConfigProviderInterface
{

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * @var Session $checkoutSession
     */
    protected $checkoutSession;

    /**
     * @var Address $address
     */
    protected $address;

    /**
     * @var AddressExtensionFactory $addressExtensionFactory
     */
    protected $addressExtensionFactory;

    /**
     * @param Address $address
     * @param AddressExtensionFactory $addressExtensionFactory
     * @param Session $checkoutSession
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Address $address,
        AddressExtensionFactory $addressExtensionFactory,
        Session $checkoutSession,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->address          = $address;
        $this->checkoutSession  = $checkoutSession;
        $this->scopeConfig      = $scopeConfig;
        $this->storeManager     = $storeManager;
        $this->addressExtensionFactory = $addressExtensionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->storeManager->getStore();

        $output = [
            'colissimoUrl'    => $store->getUrl('colissimo'),
            'colissimoPickup' => Colissimo::SHIPPING_CARRIER_PICKUP_METHOD,
            'colissimoMapType' => $this->scopeConfig->getValue(
                ShippingHelper::COLISSIMO_CONFIG_PREFIX . 'pickup/map_type',
                ScopeInterface::SCOPE_STORE
            ),
            'colissimoOpen'   => $this->scopeConfig->getValue(
                ShippingHelper::COLISSIMO_CONFIG_PREFIX . 'pickup/open',
                ScopeInterface::SCOPE_STORE
            ),
            'colissimoRemoveOnShippingSelection' => $this->scopeConfig->isSetFlag(
                ShippingHelper::COLISSIMO_CONFIG_PREFIX . 'pickup/remove_selection',
                ScopeInterface::SCOPE_STORE
            ),
        ];

        return $output;
    }
}
