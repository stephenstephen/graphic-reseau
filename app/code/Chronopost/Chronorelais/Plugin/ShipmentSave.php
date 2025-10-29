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

namespace Chronopost\Chronorelais\Plugin;

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
    public function beforeSave(Shipment $subject)
    {
        if ($subject->getData('create_track_to_shipment') === null) {
            $subject->setData('create_track_to_shipment', false);
            if ($subject->getData('entity_id') === null) {
                $subject->setData('create_track_to_shipment', true);
            }
        }

        if (!$subject->getData('create_track_to_shipment')) {
            return $subject;
        }

        $order = $subject->getOrder();
        $subject = $subject->loadByIncrementId($subject->getIncrementId());
        $shippingMethodCode = explode('_', $order->getData('shipping_method'));
        $shippingMethodCode = isset($shippingMethodCode[1]) ? $shippingMethodCode[1] : $shippingMethodCode[0];
        $weightCoef = $this->helperData->getWeightCoef();

        // Set param to subject if param exist (shipment admin interface)
        $dimensions = $subject->getData('dimensions') ?: [];
        if ($this->request->getParam('dimensions')) {
            $dimensions = json_decode($this->request->getParam('dimensions'), true);
            $subject->setData('dimensions', $dimensions);
        }

        if ($this->request->getParam('nb_colis')) {
            $subject->setData('nb_colis', (int)$this->request->getParam('nb_colis'));
        }

        $contractId = $subject->getData('contract_id');
        if ($contractId === null) {
            $contractId = $this->request->getParam('contract');
            $subject->setData('contract_id', $contractId);
        }

        // Build dimensions data
        for ($iterator = 0; $iterator < count($dimensions); $iterator++) {
            $msg = '';
            $error = false;

            $dimensionsLimit = $dimensions[$iterator];
            $weightLimit = $this->helperData->getWeightLimit($order->getData('shipping_method'));
            $dimLimit = $this->helperData->getInputDimensionsLimit($order->getData('shipping_method'));
            $globalLimit = $this->helperData->getGlobalDimensionsLimit($order->getData('shipping_method'));

            if (isset($dimensionsLimit['weight']) && $dimensionsLimit['weight'] > $weightLimit && !$error) {
                $msg = __('One or several packages are above the weight limit (%1 kg)', $weightLimit / $weightCoef);
                $error = true;
            }

            if (isset($dimensionsLimit['width']) && $dimensionsLimit['width'] > $dimLimit && !$error) {
                $msg = __('One or several packages are above the size limit (%1 cm)', $dimLimit);
                $error = true;
            }

            if (isset($dimensionsLimit['height']) && $dimensionsLimit['height'] > $dimLimit && !$error) {
                $msg = __('One or several packages are above the size limit (%1 cm)', $dimLimit);
                $error = true;
            }

            if (isset($dimensionsLimit['length']) && $dimensionsLimit['length'] > $dimLimit && !$error) {
                $msg = __('One or several packages are above the size limit (%1 cm)', $dimLimit);
                $error = true;
            }

            if (isset($dimensionsLimit['height']) && isset($dimensionsLimit['width']) &&
                isset($dimensionsLimit['length']) && !$error) {
                $global = 2 * $dimensionsLimit['height'] + $dimensionsLimit['width'] + 2 * $dimensionsLimit['length'];
                if ($global > $globalLimit) {
                    $msg = __('One or several packages are above the total (L+2H+2l) size limit (%1 cm)', $globalLimit);
                    $error = true;
                }
            }

            if ($error) {
                throw new \Exception((string)$msg);
            }
        }

        if ($this->helperData->isChronoMethod($shippingMethodCode)) {
            $contract = $this->helperData->getSpecificContract($contractId);
            $result = $this->helperWS->checkContract($contract);
            if (!$result->return->errorCode) {
                return $subject;
            } else {
                switch ($result->return->errorCode) {
                    case 3:
                        $message = __('An error occured during the label creation.' .
                            ' Please check if this contract can edit labels for this carrier.');
                        break;
                    default:
                        $message = __($result->return->errorMessage);
                        break;
                }

                throw new \Exception((string)$message);
            }
        }
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
    public function afterSave(Shipment $subject)
    {
        $contractId = $subject->getData('contract_id');
        $packageNumber = $subject->getData('nb_colis') ?: 1;
        $dimensions = $subject->getData('dimensions') ?: [];
        $adValorem = $this->request->getParam('ad_valorem') ?: null;

        // Insurance not available for multiple packages
        if ($packageNumber > 1) {
            $adValorem = null;
        }

        // To avoid multiple label creation
        if (!$subject->getData('create_track_to_shipment')) {
            return $subject;
        }

        $order = $subject->getOrder();
        $subject = $subject->loadByIncrementId($subject->getIncrementId());
        $shippingMethodCode = explode('_', $order->getData('shipping_method'));
        $shippingMethodCode = isset($shippingMethodCode[1]) ? $shippingMethodCode[1] : $shippingMethodCode[0];

        if ($this->helperData->isChronoMethod($shippingMethodCode)) {
            $trackExist = false;
            $tracks = $subject->getAllTracks();
            if (count($tracks)) {
                foreach ($tracks as $track) {
                    if ($track->getData('chrono_reservation_number')) {
                        $trackExist = true;
                        break;
                    }
                }
            }

            // Chronopost order without tracking
            if (!$trackExist) {
                $this->helperShipment->createTrackToShipment(
                    $subject,
                    $subject->getTrackData() ?: [],
                    $dimensions,
                    $packageNumber,
                    $contractId,
                    (float)$adValorem
                );
            }

            // Add contract to order if not exist
            $contractOrder = $this->contractsOrdersFactory->create()->getCollection()
                ->addFieldToFilter('order_id', $subject->getOrder()->getId());
            if (count($contractOrder) === 0) {
                $this->addContractToOrder($subject, $contractId);
            }
        }

        return $subject;
    }

    /**
     * Link contract to order
     *
     * @param Shipment $subject
     * @param string   $contractId
     *
     * @return void
     * @throws \Exception
     */
    private function addContractToOrder(Shipment $subject, string $contractId)
    {
        $contract = $this->helperData->getSpecificContract($contractId);
        if ($contract) {
            $contractOrder = $this->contractsOrdersFactory->create();
            $contractOrder->setData('order_id', $subject->getOrder()->getId());
            $contractOrder->setData('contract_name', $contract['name']);
            $contractOrder->setData('contract_account_number', $contract['number']);
            $contractOrder->setData('contract_sub_account_number', $contract['subAccount']);
            $contractOrder->setData('contract_account_password', $contract['pass']);
            $contractOrder->save();
        }
    }
}
