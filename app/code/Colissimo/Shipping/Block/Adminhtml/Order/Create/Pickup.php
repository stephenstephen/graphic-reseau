<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Block\Adminhtml\Order\Create;

use Colissimo\Shipping\Model\Carrier\Colissimo;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Session\Quote;

/**
 * Class Pickup
 */
class Pickup extends Template
{

    /**
     * @var Quote $sessionQuote
     */
    protected $sessionQuote;

    /**
     * @param Context $context
     * @param Quote $sessionQuote
     * @param array $data
     */
    public function __construct(
        Context $context,
        Quote $sessionQuote,
        array $data = []
    ) {
        $this->sessionQuote = $sessionQuote;
        parent::__construct($context, $data);
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

    /**
     * Retrieve if pickup data can be load
     *
     * @return bool
     */
    public function canLoad()
    {
        return $this->sessionQuote->getQuote()->getShippingAddress()->getShippingMethod() == $this->getPickupMethod();
    }
}
