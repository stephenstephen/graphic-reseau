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

use Chronopost\Chronorelais\Model\HistoryLtFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Sales\Model\Convert\Order as ConvertOrder;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Shipment as OrderShipment;
use Magento\Sales\Model\Order\Shipment\TrackFactory;
use Magento\Shipping\Model\ShipmentNotifier;

/**
 * Class Shipment
 *
 * @package Chronopost\Chronorelais\Helper
 */
class Shipment extends AbstractHelper
{
    const HISTORY_TYPE_SHIPMENT = 1;
    const HISTORY_TYPE_RETURN = 2;

    /**
     * @var TrackFactory
     */
    protected $trackFactory;

    /**
     * @var ConvertOrder
     */
    protected $convertOrder;

    /**
     * @var ShipmentNotifier
     */
    protected $shipmentNotifier;

    /**
     * @var Webservice
     */
    protected $helperWebserviceNotifier;

    /**
     * @var OrderShipment
     */
    protected $shipment;

    /**
     * @var HistoryLtFactory
     */
    protected $ltHistoryFactory;

    /**
     * Shipment constructor.
     *
     * @param Context          $context
     * @param TrackFactory     $trackFactory
     * @param ConvertOrder     $convertOrder
     * @param ShipmentNotifier $shipmentNotifier
     * @param Webservice       $webservice
     * @param OrderShipment    $shipment
     * @param HistoryLtFactory $historyLtFactory
     */
    public function __construct(
        Context $context,
        TrackFactory $trackFactory,
        ConvertOrder $convertOrder,
        ShipmentNotifier $shipmentNotifier,
        Webservice $webservice,
        OrderShipment $shipment,
        HistoryLtFactory $historyLtFactory
    ) {
        parent::__construct($context);
        $this->trackFactory = $trackFactory;
        $this->convertOrder = $convertOrder;
        $this->shipmentNotifier = $shipmentNotifier;
        $this->helperWebserviceNotifier = $webservice;
        $this->shipment = $shipment;
        $this->ltHistoryFactory = $historyLtFactory;
    }

    /**
     * Create shipment and labels
     *
     * @param Order $order
     * @param array $savedQtys
     * @param array $trackData
     * @param null  $dimensions
     * @param int   $packageNumber
     * @param bool  $isImport
     * @param null  $contractId
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     *
     * @return bool|mixed|string
     * @throws LocalizedException
     * @throws MailException
     */
    public function createNewShipment(
        Order $order,
        $savedQtys = [],
        $trackData = [],
        $dimensions = [],
        $packageNumber = 1,
        $isImport = false,
        $contractId = null
    ) {
        if (!$order->canShip() && !$isImport) {
            throw new LocalizedException(
                __("You can't create a shipment.")
            );
        }

        $shipment = $this->convertOrder->toShipment($order);
        foreach ($order->getAllItems() as $orderItem) {
            if (!$orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
                continue;
            }

            if (isset($savedQtys[$orderItem->getId()])) {
                $qtyShipped = min($savedQtys[$orderItem->getId()], $orderItem->getQtyToShip());
            } elseif (!count($savedQtys)) {
                $qtyShipped = $orderItem->getQtyToShip();
            } else {
                continue;
            }

            $shipmentItem = $this->convertOrder->itemToShipmentItem($orderItem)->setQty($qtyShipped);
            $shipment->addItem($shipmentItem);
        }

        $shipment->setData('dimensions', $dimensions);
        $shipment->setData('nb_colis', $packageNumber);
        $shipment->setData('contract_id', $contractId);

        // Case of import tracking via the BO
        $shipment->setTrackData($trackData);

        $shipment->register();
        $shipment->getOrder()->setIsInProcess(true);

        if ($shipment->getExtensionAttributes() !== null && !empty($shipment->getExtensionAttributes())) {
            $shipment->getExtensionAttributes()->setSourceCode('default');
        }

        $shipment->setData('create_track_toshipment', true);

        if ((!isset($trackData['send_mail']) || (isset($trackData['send_mail']) && $trackData['send_mail']))) {
            if (isset($trackData['comment'])) {
                $shipment->addComment($trackData['comment'], true, $trackData['include_comment']);
            }

            $this->shipmentNotifier->notify($shipment);
            $shipment->setData('create_track_toshipment', false);
        }

        $shipment->save();
        $shipment->getOrder()->save();

        return $shipment;
    }

    /**
     * Create track to shipment
     *
     * @param OrderShipment $shipment
     * @param array         $trackData
     * @param array         $dimensions
     * @param int           $packageNumber
     * @param null          $contractId
     * @param null          $customeAdValorem
     *
     * @return array
     * @throws \Exception
     */
    public function createTrackToShipment(
        OrderShipment $shipment,
        $trackData = [],
        $dimensions = [],
        $packageNumber = 1,
        $contractId = null,
        $customeAdValorem = null
    ) {
        $trackDatas = [];
        $resultParcelValues = [];

        $order = $shipment->getOrder();
        $shippingMethod = explode('_', $order->getShippingMethod());

        if (count($trackData) > 0) {
            $trackData = array_merge($trackData, [
                'parent_id' => $shipment->getId(),
                'order_id'  => $order->getId()
            ]);

            $trackDatas[] = $trackData;
        } else {
            if ($contractId === null) {
                $contractId = $shipment->getData('contract_id');
            }

            $expedition = $this->helperWebserviceNotifier->createEtiquette(
                $shipment,
                'shipping',
                'returninformation',
                $dimensions,
                $packageNumber,
                $contractId,
                $customeAdValorem
            );

            if (is_object($expedition->return->resultParcelValue)) {
                array_push($resultParcelValues, $expedition->return->resultParcelValue);
            } else {
                $resultParcelValues = $expedition->return->resultParcelValue;
            }

            for ($ite = 0; $ite < count($resultParcelValues); $ite++) {
                $trackData = [
                    'track_number'              => $resultParcelValues[$ite]->skybillNumber,
                    'parent_id'                 => $shipment->getId(),
                    'order_id'                  => $order->getId(),
                    'chrono_reservation_number' => $expedition->return->reservationNumber,
                    'carrier'                   => ucwords($shippingMethod[1]),
                    'carrier_code'              => $shippingMethod[1],
                    'title'                     => ucwords($shippingMethod[1]),
                    'popup'                     => '1'
                ];

                if (!isset($dimensions[$ite])) {
                    $dimensions[$ite] = $dimensions['weight'];
                }

                $this->saveLtHistory(
                    $shipment->getId(),
                    $resultParcelValues[$ite]->skybillNumber,
                    $dimensions[$ite]['weight'],
                    $expedition->return->reservationNumber
                );

                $trackDatas[] = $trackData;
            }
        }

        try {
            foreach ($trackDatas as $trackData) {
                $track = $this->trackFactory->create();
                $track->addData($trackData);
                $shipment->addTrack($track)->setData('create_track_toshipment', false)->save();
            }
        } catch (\Exception $exception) {
            $this->_logger->error($exception->getMessage());
        }

        return $trackDatas;
    }

    /**
     * Save history
     *
     * @param string $shipmentId
     * @param string $ltNumber
     * @param float  $weight
     * @param null   $reservation
     * @param null   $type HISTORY_TYPE_SHIPMENT or HISTORY_TYPE_RETURN
     *
     * @throws \Exception
     */
    public function saveLtHistory($shipmentId, $ltNumber, $weight, $reservation = null, $type = null)
    {
        if (!$type) {
            $type = static::HISTORY_TYPE_SHIPMENT;
        }

        $ltHistory = $this->ltHistoryFactory->create();
        $ltHistory->setData('shipment_id', $shipmentId);
        $ltHistory->setData('lt_number', $ltNumber);
        $ltHistory->setData('weight', $weight);
        $ltHistory->setData('reservation', $reservation);
        $ltHistory->setData('type', $type);
        $ltHistory->save();
    }

    /**
     * Load shipment by increment id
     *
     * @param string $incrementId
     *
     * @return OrderShipment
     */
    public function getShipmentByIncrementId($incrementId)
    {
        return $this->shipment->setId(null)->loadByIncrementId($incrementId);
    }

    /**
     * Get label url
     *
     * @param OrderShipment|string $shipment
     * @param string|null          $trackNumber
     * @param string               $type
     * @param array                $dimensions
     *
     * @return array
     * @throws \SoapFault
     * @throws \Exception
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getEtiquetteUrl($shipment, $trackNumber, string $type, array $dimensions = [])
    {
        $etiquetteUrl = [];

        if ($trackNumber !== null) {
            $track = $this->trackFactory->create()->getCollection()
                ->addFieldToFilter('track_number', $trackNumber)
                ->getFirstItem();

            $order = $track->getShipment()->getOrder();
            $shippingMethod = explode('_', $order->getShippingMethod());
            $shippingMethod = isset($shippingMethod[1]) ? $shippingMethod[1] : $shippingMethod[0];

            $etiquetteUrl[] = base64_decode(
                $this->helperWebserviceNotifier->getEtiquetteByReservationNumber(
                    $trackNumber,
                    $shippingMethod,
                    $type,
                    $order->getShippingAddress()
                )
            );

            $chronoReservationNumber = $track->getData('chrono_reservation_number');
            if ($chronoReservationNumber && strlen($chronoReservationNumber) > 50) {
                $etiquetteUrl[] = base64_decode($chronoReservationNumber);
            }

            return $etiquetteUrl;
        }

        // Load shipment
        if (!$shipment instanceof OrderShipment && $shipment !== null) {
            $shipment = $this->getShipmentByIncrementId($shipment);
        }

        $order = $shipment->getOrder();
        $shippingMethod = explode('_', $order->getShippingMethod());
        $shippingMethod = isset($shippingMethod[1]) ? $shippingMethod[1] : $shippingMethod[0];

        // Get all tracks
        if ($shipTracks = $shipment->getAllTracks()) {
            $revisionNumbers = [];
            foreach ($shipTracks as $shipTrack) {
                if ($shipTrack->getNumber() && $shipTrack->getChronoReservationNumber()) {
                    $chronoReservationNumber = $shipTrack->getData('chrono_reservation_number');
                    if (strlen($chronoReservationNumber) > 50) {
                        $etiquetteUrl[] = base64_decode($chronoReservationNumber);
                    } elseif (!in_array($chronoReservationNumber, $revisionNumbers)) {
                        $revisionNumbers[] = $chronoReservationNumber;

                        $etiquetteUrl[] = base64_decode(
                            $this->helperWebserviceNotifier->getEtiquetteByReservationNumber(
                                $chronoReservationNumber,
                                $shippingMethod,
                                'shipping',
                                $shipment->getOrder()->getShippingAddress()
                            )
                        );
                    }
                }
            }

            return $etiquetteUrl;
        }

        $trackDatas = $this->createTrackToShipment($shipment, [], $dimensions);
        foreach ($trackDatas as $trackData) {
            $etiquetteUrl[] = base64_decode(
                $this->helperWebserviceNotifier->getEtiquetteByReservationNumber(
                    $trackData['track_number'],
                    $shippingMethod,
                    $type,
                    $order->getShippingAddress()
                )
            );
        }

        return $etiquetteUrl;
    }

    /**
     * Get return for shipment
     *
     * @param string $shipmentId
     *
     * @return mixed
     */
    public function getReturnsForShipment($shipmentId)
    {
        return $this->ltHistoryFactory->create()->getCollection()
            ->addFieldToFilter('shipment_id', $shipmentId)
            ->addFieldToFilter('type', static::HISTORY_TYPE_RETURN);
    }
}
