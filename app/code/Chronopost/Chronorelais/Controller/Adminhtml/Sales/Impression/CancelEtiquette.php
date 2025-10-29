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
use Chronopost\Chronorelais\Helper\Webservice;
use Chronopost\Chronorelais\Lib\PDFMerger\PDFMerger;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Model\Order\ShipmentRepository;
use Magento\Sales\Model\OrderFactory as OrderFactory;

/**
 * Class CancelEtiquette
 *
 * @package Chronopost\Chronorelais\Controller\Adminhtml\Sales\Impression
 */
class CancelEtiquette extends AbstractImpression
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
     * @var Webservice
     */
    private $helperWebservice;

    /**
     * CancelEtiquette constructor.
     *
     * @param Context                      $context
     * @param DirectoryList                $directoryList
     * @param PageFactory                  $resultPageFactory
     * @param HelperData                   $helperData
     * @param PDFMerger                    $PDFMerger
     * @param ManagerInterface             $messageManager
     * @param OrderFactory                 $orderFactory
     * @param HelperShipment               $helperShipment
     * @param Webservice                   $helperWebservice
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
        OrderFactory $orderFactory,
        HelperShipment $helperShipment,
        Webservice $helperWebservice,
        ShipmentRepository $shipmentRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilder
    ) {
        parent::__construct($context, $directoryList, $resultPageFactory, $helperData, $PDFMerger, $messageManager);
        $this->helperData = $helperData;
        $this->orderFactory = $orderFactory;
        $this->helperShipment = $helperShipment;
        $this->helperWebservice = $helperWebservice;
        $this->shipmentRepository = $shipmentRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Label cancellation
     *
     * @return ResponseInterface|Redirect|ResultInterface
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        $error = [];
        $nbDeleted = 0;

        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $resultRedirect = $this->resultRedirectFactory->create();
            $trackNumber = $this->getRequest()->getParam('track_number');
            $orderId = $this->getRequest()->getParam('order_id');
            $shipmentId = $this->getRequest()->getParam('shipment_id');
            $contract = $this->helperData->getContractByOrderId($orderId);
            $webservbt = $this->helperWebservice->cancelEtiquette($trackNumber, $contract);

            if ($webservbt) {
                if ($webservbt->return->errorCode == 0) {
                    $returns = $this->helperShipment->getReturnsForShipment($shipmentId);
                    foreach ($returns as $return) {
                        if ($return->getLtNumber() === $trackNumber) {
                            $return->delete();
                            $nbDeleted++;
                            continue;
                        }
                    }
                } else {
                    switch ($webservbt->return->errorCode) {
                        case "1":
                            $errorMessage = __("A system error has occurred");
                            break;
                        case "2":
                            $errorMessage = __("The parcel’s parameters do not fall within the scope of the the contract passed or it has not yet been registered in the Chronopost tracking system");
                            break;
                        case "3":
                            $errorMessage = __("The parcel cannot be cancelled because it has been dispatched by Chronopost");
                            break;
                        default:
                            $errorMessage = '';
                            break;
                    }
                    $error[] = __("An error occurred while deleting labels %1: %2.", $trackNumber, $errorMessage);
                }
            } else {
                $error[] = __(
                    "Sorry, an error occurred while deleting label %1. Please contact Chronopost or try again later",
                    $trackNumber
                );
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
            $resultRedirect->setPath("chronorelais/sales/impression");

            return $resultRedirect;
        }

        if ($nbDeleted > 0) {
            if ($nbDeleted > 1) {
                $this->messageManager->addSuccessMessage(
                    __('%1 return shipping labels have been cancelled.', $nbDeleted)
                );
            } else {
                $this->messageManager->addSuccessMessage(
                    __('%1 return shipping label has been cancelled.', $nbDeleted)
                );
            }
        }

        if (count($error)) {
            foreach ($error as $err) {
                $this->messageManager->addErrorMessage(__($err));
            }
        }

        $resultRedirect->setPath("chronorelais/sales/impression");

        return $resultRedirect;
    }
}
