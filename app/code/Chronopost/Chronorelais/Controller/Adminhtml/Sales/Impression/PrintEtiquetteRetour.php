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
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Model\OrderFactory as OrderFactory;
use Zend_Mail;
use Zend_Mime;

/**
 * Class PrintEtiquetteRetour
 *
 * @package Chronopost\Chronorelais\Controller\Adminhtml\Sales\Impression
 */
class PrintEtiquetteRetour extends AbstractImpression
{
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
     * @var HelperWebservice
     */
    protected $helperWebservice;

    /**
     * PrintEtiquetteRetour constructor.
     *
     * @param Context          $context
     * @param DirectoryList    $directoryList
     * @param PageFactory      $resultPageFactory
     * @param HelperData       $helperData
     * @param PDFMerger        $PDFMerger
     * @param ManagerInterface $messageManager
     * @param HelperShipment   $helperShipment
     * @param OrderFactory     $orderFactory
     * @param HelperWebservice $helperWebservice
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
        HelperWebservice $helperWebservice
    ) {
        parent::__construct(
            $context,
            $directoryList,
            $resultPageFactory,
            $helperData,
            $PDFMerger,
            $messageManager
        );
        $this->directoryList = $directoryList;
        $this->helperData = $helperData;
        $this->helperShipment = $helperShipment;
        $this->orderFactory = $orderFactory;
        $this->helperWebservice = $helperWebservice;
    }

    /**
     * Print return label
     *
     * @return ResponseInterface|Redirect|ResultInterface
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $shipmentIncrementId = $this->getRequest()->getParam('shipment_increment_id');

        $shipment = $this->helperShipment->getShipmentByIncrementId($shipmentIncrementId);
        $order = $shipment->getOrder();

        $shippingAddress = $shipment->getShippingAddress();
        $billingAddress = $shipment->getBillingAddress();
        $shippingCountryId = $shippingAddress->getCountryId();
        $shippingMethod = explode('_', $order->getShippingMethod());
        $shippingMethod = isset($shippingMethod[1]) ? $shippingMethod[1] : $shippingMethod[0];

        // Check if method is allowed
        $shippingMethodsAllowed = HelperData::SHIPPING_METHODS_RETURN_ALLOWED;
        if (!in_array($shippingMethod, $shippingMethodsAllowed)) {
            $this->messageManager->addErrorMessage(
                __('Returns are not available for the delivery option %1', $shippingMethod)
            );
            $resultRedirect->setPath('chronorelais/sales/impression');

            return $resultRedirect;
        }

        // Check if return is authorized
        if (strpos($shippingMethod, 'chronorelais') !== false) {
            if (!$this->helperData->returnAuthorized($shippingCountryId, $shippingMethod)) {
                $this->messageManager->addErrorMessage(
                    __('Return labels are not available for this country: %1', $shippingCountryId)
                );
                $resultRedirect->setPath('chronorelais/sales/impression');

                return $resultRedirect;
            }
        }

        // Check shipping method if country is different of "FR"
        if ($shippingCountryId !== 'FR' &&
            !in_array($shippingMethod, HelperData::SHIPPING_METHODS_RETURN_INTERNATIONAL)) {
            $this->messageManager->addErrorMessage(__('Returns are only available for France'));
            $resultRedirect->setPath('chronorelais/sales/impression');

            return $resultRedirect;
        }

        try {
            $etiquette = $this->helperWebservice->createEtiquette(
                $shipment,
                'return',
                $this->getRequest()->getParam('recipient_address_type')
            );

            if ($etiquette) {
                $this->helperShipment->saveLtHistory(
                    $shipment->getId(),
                    $etiquette->return->resultParcelValue->skybillNumber,
                    $shipment->getTotalWeight(),
                    $etiquette->return->reservationNumber,
                    HelperShipment::HISTORY_TYPE_RETURN
                );

                $productCode = $this->helperWebservice->getReturnProductCode($shippingAddress, $shippingMethod);
                if ($productCode !== HelperData::CHRONOPOST_REVERSE_T) {
                    $reservationNumber = $etiquette->return->reservationNumber;
                    $pdf = $this->helperWebservice->getEtiquetteByReservationNumber(
                        $reservationNumber,
                        $shippingMethod,
                        'return',
                        $shippingAddress
                    );
                    $path = $this->savePdfWithContent(base64_decode($pdf), $shipment->getId());

                    // send mail with pdf
                    $messageEmail = __('Hello, <br />You will soon be using Chronopost to send an item. ' .
                        'The person who sent you this email has already prepared the waybill that you will use. ' .
                        'Once it has been printed, put the waybill into an adhesive pouch and affix it to your ' .
                        'shipment. Make sure the barcode is clearly visible.<br />Kind regards,');

                    $billingEmail = $billingAddress->getEmail();
                    $shippingEmail = $shippingAddress->getEmail();
                    $orderEmail = $order->getCustomerEmail();
                    $customerEmail = ($shippingEmail) ? $shippingEmail : ($billingEmail ? $billingEmail : $orderEmail);
                    $recipientEmail = $this->helperData->getConfig('contact/email/recipient_email');

                    $mail = new Zend_Mail('utf-8');
                    $mail->setType('multipart/alternative');
                    $mail->setBodyHtml($messageEmail);
                    $mail->setFrom($recipientEmail);
                    $mail->setSubject(__('%1: Chronopost return label', $order->getStoreName()));
                    $mail->createAttachment(
                        file_get_contents($path),
                        Zend_Mime::TYPE_OCTETSTREAM,
                        Zend_Mime::DISPOSITION_ATTACHMENT,
                        Zend_Mime::ENCODING_BASE64,
                        'etiquette_retour.pdf'
                    );
                    $mail->addTo($customerEmail);
                    $mail->send();

                    $mail->clearRecipients();
                    $mail->addTo($recipientEmail);
                    $mail->send();
                }

                $this->messageManager->addSuccessMessage(__('The return label has been sent to the customer.'));
            }
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__($exception->getMessage()));
        }

        $resultRedirect->setPath('chronorelais/sales/impression');

        return $resultRedirect;
    }

    /**
     * Save PDF with content
     *
     * @param string $content
     * @param string $shipmentId
     *
     * @return string
     * @throws FileSystemException
     */
    protected function savePdfWithContent(string $content, string $shipmentId)
    {
        $this->createMediaChronopostFolder();

        $path = $this->directoryList->getPath('media') . '/chronopost';
        $path = $path . '/etiquetteRetour-' . $shipmentId . '.pdf';
        file_put_contents($path, $content);

        return $path;
    }
}
