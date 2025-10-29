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
use Chronopost\Chronorelais\Helper\Webservice as HelperWebservice;
use Chronopost\Chronorelais\Lib\PDFMerger\PDFMerger;
use Chronopost\Chronorelais\Model\ContractsOrdersFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class CancelEtiquetteMass
 *
 * @package Chronopost\Chronorelais\Controller\Adminhtml\Sales\Impression
 */
class CancelEtiquetteMass extends AbstractImpression
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var HelperShipment
     */
    protected $helperShipment;

    /**
     * @var HelperWebservice
     */
    protected $helperWebservice;

    /**
     * @var ContractsOrders
     */
    private $contractsOrders;

    /**
     * CancelEtiquetteMass constructor.
     *
     * @param Context                $context
     * @param DirectoryList          $directoryList
     * @param PageFactory            $resultPageFactory
     * @param HelperData             $helperData
     * @param PDFMerger              $PDFMerger
     * @param ManagerInterface       $messageManager
     * @param Filter                 $filter
     * @param CollectionFactory      $collectionFactory
     * @param HelperShipment         $helperShipment
     * @param HelperWebservice       $helperWebservice
     * @param ContractsOrdersFactory $contractsOrders
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        DirectoryList $directoryList,
        PageFactory $resultPageFactory,
        HelperData $helperData,
        PDFMerger $PDFMerger,
        ManagerInterface $messageManager,
        Filter $filter,
        CollectionFactory $collectionFactory,
        HelperShipment $helperShipment,
        HelperWebservice $helperWebservice,
        ContractsOrdersFactory $contractsOrders
    ) {
        parent::__construct($context, $directoryList, $resultPageFactory, $helperData, $PDFMerger, $messageManager);
        $this->helperData = $helperData;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->helperShipment = $helperShipment;
        $this->helperWebservice = $helperWebservice;
        $this->contractsOrders = $contractsOrders;
    }

    /**
     * Mass cancellation of labels
     *
     * @return ResponseInterface|Redirect|ResultInterface
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $deleteCount = 0;
            $error = [];
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            foreach ($collection->getItems() as $order) {
                $shipments = $order->getShipmentsCollection();
                if ($shipments->count()) {
                    foreach ($shipments as $shipment) {
                        $tracks = $shipment->getAllTracks();
                        $contract = $this->helperData->getContractByOrderId($order->getId());
                        foreach ($tracks as $track) {
                            if ($track->getChronoReservationNumber()) {
                                $webservbt = $this->helperWebservice->cancelEtiquette($track->getNumber(), $contract);
                                if ($webservbt) {
                                    if ($webservbt->return->errorCode == 0) {
                                        $deleteCount++;
                                        $track->delete();
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

                                        $error[] = __(
                                            "An error occurred while deleting labels %1: %2.",
                                            $track->getNumber(),
                                            $errorMessage
                                        );
                                    }
                                } else {
                                    $error[] = __(
                                        "Sorry, an error occurred while deleting label %1. Please contact Chronopost or try again later",
                                        $track->getNumber()
                                    );
                                }
                            }
                        }
                    }
                }

                if (empty($error)) {
                    $contractOrder = $this->contractsOrders->create()
                        ->getCollection()
                        ->addFieldToFilter('order_id', $order->getId())
                        ->getFirstItem();

                    $contractOrder->delete();
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
            $resultRedirect->setPath("chronorelais/sales/impression");

            return $resultRedirect;
        }

        if ($deleteCount > 0) {
            if ($deleteCount > 1) {
                $this->messageManager->addSuccessMessage(
                    __('%1 shipping labels have been cancelled.', $deleteCount)
                );
            } else {
                $this->messageManager->addSuccessMessage(
                    __('%1 shipping label has been cancelled.', $deleteCount)
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
