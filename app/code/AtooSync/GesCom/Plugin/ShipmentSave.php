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

namespace AtooSync\GesCom\Plugin;

use Chronopost\Chronorelais\Helper\Data as HelperData;
use Chronopost\Chronorelais\Helper\Shipment as HelperShipment;
use Chronopost\Chronorelais\Helper\Webservice;
use Chronopost\Chronorelais\Model\ContractsOrdersFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Sales\Model\Order\Shipment;
use Magento\Sales\Model\OrderFactory;

/**
 * Class ShipmentSave
 *
 * @package Chronopost\Chronorelais\Plugin
 */
class ShipmentSave
{
    /**
     * @var HelperShipment
     */
    protected $helperShipment;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var ContractsOrdersFactory
     */
    protected $contractsOrdersFactory;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var Webservice
     */
    private $helperWS;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * ShipmentSave constructor.
     *
     * @param HelperShipment         $helperShipment
     * @param Webservice             $helperWS
     * @param ContractsOrdersFactory $contractsOrdersFactory
     * @param ScopeConfigInterface   $scopeConfig
     * @param OrderFactory           $orderFactory
     * @param RequestInterface       $request
     * @param HelperData             $helperData
     */
    public function __construct(
        HelperShipment $helperShipment,
        Webservice $helperWS,
        ContractsOrdersFactory $contractsOrdersFactory,
        ScopeConfigInterface $scopeConfig,
        OrderFactory $orderFactory,
        RequestInterface $request,
        HelperData $helperData
    ) {
        $this->helperShipment = $helperShipment;
        $this->contractsOrdersFactory = $contractsOrdersFactory;
        $this->scopeConfig = $scopeConfig;
        $this->orderFactory = $orderFactory;
        $this->helperData = $helperData;
        $this->helperWS = $helperWS;
        $this->request = $request;
    }

    /**
     * Before save shipment
     *
     * @param Shipment $subject
     *
     * @return Shipment
     * @throws \Exception
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function beforeSave(Shipment $subject) {
        return $subject;
    }

    /**
     * After save shipment
     *
     * @param Shipment $subject
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     *
     * @return Shipment
     * @throws \Exception
     */
    public function afterSave(Shipment $subject) {
        return $subject;
    }
}
