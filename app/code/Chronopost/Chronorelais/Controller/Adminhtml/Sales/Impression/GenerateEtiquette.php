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

namespace Chronopost\Chronorelais\Controller\Adminhtml\Sales\Impression;

use Chronopost\Chronorelais\Helper\Data as HelperData;
use Chronopost\Chronorelais\Helper\Shipment as HelperShipment;
use Chronopost\Chronorelais\Lib\PDFMerger\PDFMerger;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Model\Order\Shipment;
use Magento\Sales\Model\Order\ShipmentRepository;
use Magento\Sales\Model\OrderFactory as OrderFactory;

/**
 * Class generateEtiquette
 *
 * @package Chronopost\Chronorelais\Controller\Adminhtml\Sales\Impression
 * @SuppressWarnings("CouplingBetweenObjects")
 */
class GenerateEtiquette extends AbstractImpression
{
    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var HelperShipment
     */
    protected $helperShipment;

    /**
     * @var ShipmentRepository
     */
    private $shipmentRepository;

    /**
     * GenerateEtiquette constructor.
     *
     * @param Context                      $context
     * @param DirectoryList                $directoryList
     * @param PageFactory                  $resultPageFactory
     * @param HelperData                   $helperData
     * @param PDFMerger                    $PDFMerger
     * @param ManagerInterface             $messageManager
     * @param HelperShipment               $helperShipment
     * @param OrderFactory                 $orderFactory
     * @param ShipmentRepository           $shipmentRepository
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilder
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        DirectoryList $directoryList,
        PageFactory $resultPageFactory,
        HelperData $helperData,
        PDFMerger $PDFMerger,
        ManagerInterface $messageManager,
        HelperShipment $helperShipment,
        OrderFactory $orderFactory,
        ShipmentRepository $shipmentRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilder
    ) {
        parent::__construct($context, $directoryList, $resultPageFactory, $helperData, $PDFMerger, $messageManager);
        $this->helperData = $helperData;
        $this->helperShipment = $helperShipment;
        $this->orderFactory = $orderFactory;
        $this->shipmentRepository = $shipmentRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Generate label
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     *
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $orderId = $this->getRequest()->getParam('order_id');
        $packageNumber = (int)$this->getRequest()->getParam('nb_colis');
        $contractId = $this->getRequest()->getParam('contract');
        $dimensions = json_decode($this->getRequest()->getParam('dimensions'), true);
        $weightCoef = $this->helperData->getWeightCoef();

        try {
            if ($orderId) {
                $order = $this->orderFactory->create()->load($orderId);
                if ($order && $order->getId()) {
                    $shippingMethodCode = explode('_', $order->getData('shipping_method'));
                    $shippingMethodCode = isset($shippingMethodCode[1]) ? $shippingMethodCode[1] : $shippingMethodCode[0];

                    if (!$this->helperData->isChronoMethod($shippingMethodCode)) {
                        throw new \Exception(
                            (string)__('Delivery option not Chronopost for order %1', $order->getIncrementId())
                        );
                    }

                    for ($i = 0; $i < count($dimensions); $i++) {
                        $msg = [];
                        $error = false;

                        $dimensionsLimit = $dimensions[$i];
                        $weightLimit = $this->helperData->getWeightLimit($order->getData('shipping_method'));
                        $dimLimit = $this->helperData->getInputDimensionsLimit($order->getData('shipping_method'));
                        $globalLimit = $this->helperData->getGlobalDimensionsLimit($order->getData('shipping_method'));

                        if (isset($dimensionsLimit['weight']) && $dimensionsLimit['weight'] > $weightLimit) {
                            $msg[] = __(
                                'One or several packages are above the weight limit (%1 kg)',
                                $weightLimit / $weightCoef
                            );
                            $error = true;
                        }

                        if (isset($dimensionsLimit['width']) && $dimensionsLimit['width'] > $dimLimit) {
                            $msg[] = __('One or several packages are above the size limit (%1 cm)', $dimLimit);
                            $error = true;
                        }

                        if (isset($dimensionsLimit['height']) && $dimensionsLimit['height'] > $dimLimit) {
                            $msg[] = __('One or several packages are above the size limit (%1 cm)', $dimLimit);
                            $error = true;
                        }

                        if (isset($dimensionsLimit['length']) && $dimensionsLimit['length'] > $dimLimit) {
                            $msg[] = __('One or several packages are above the size limit (%1 cm)', $dimLimit);
                            $error = true;
                        }

                        if (isset($dimensionsLimit['height']) && isset($dimensionsLimit['width']) &&
                            isset($dimensionsLimit['length'])) {
                            $global = 2 * $dimensionsLimit['height'] + $dimensionsLimit['width']
                                + 2 * $dimensionsLimit['length'];
                            if ($global > $globalLimit) {
                                $msg[] = __(
                                    'One or several packages are above the total (L+2H+2l) size limit (%1 cm)',
                                    $globalLimit
                                );
                                $error = true;
                            }
                        }

                        if ($error) {
                            $this->messageManager->addErrorMessage(__(implode('\n', $msg)));
                            $resultRedirect->setPath('chronorelais/sales/impression');

                            return $resultRedirect;
                        }
                    }

                    $shipments = $order->getShipmentsCollection();
                    if ($shipments->count()) {
                        $shipmentId = $this->_request->getParam('shipment_id');
                        if ($shipmentId) {
                            $searchCriteriaBuilder = $this->searchCriteriaBuilder->create();
                            $searchCriteriaBuilder->addFilter(
                                'increment_id',
                                $shipmentId,
                                'eq'
                            );

                            $searchCriteria = $searchCriteriaBuilder->create();
                            $shipment = $this->shipmentRepository->getList($searchCriteria)->getFirstItem();
                        } else {
                            $shipment = $shipments->getFirstItem();
                        }

                        $this->createTracksWithNumber(
                            $shipment,
                            $packageNumber,
                            $dimensions
                        );
                    } else {
                        $this->helperShipment->createNewShipment(
                            $order,
                            [],
                            [],
                            $dimensions,
                            $packageNumber,
                            false,
                            $contractId
                        );
                    }
                }
            }

            $this->messageManager->addSuccessMessage(__('Labels are correctly generated'));
            $resultRedirect->setPath('chronorelais/sales/impression');

            return $resultRedirect;
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__($exception->getMessage()));
            $resultRedirect->setPath('chronorelais/sales/impression');

            return $resultRedirect;
        }
    }

    /**
     * Create tracks with number
     *
     * @param Shipment $shipment
     * @param int      $nbColis
     * @param null     $dimensions
     *
     * @throws \Exception
     */
    private function createTracksWithNumber($shipment, $nbColis, $dimensions = [])
    {
        $order = $shipment->getOrder();
        $trackData = $shipment->getTrackData() ? $shipment->getTrackData() : [];
        $shipment = $shipment->loadByIncrementId($shipment->getIncrementId());

        $shippingMethod = explode("_", $order->getData('shipping_method'));
        $shippingMethodCode = isset($shippingMethod[1]) ? $shippingMethod[1] : $shippingMethod[0];
        if ($this->helperData->isChronoMethod($shippingMethodCode)) {
            $this->helperShipment->createTrackToShipment($shipment, $trackData, $dimensions, $nbColis);
        }
    }
}
