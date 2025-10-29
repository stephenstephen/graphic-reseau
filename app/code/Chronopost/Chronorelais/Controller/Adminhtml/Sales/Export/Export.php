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

namespace Chronopost\Chronorelais\Controller\Adminhtml\Sales\Export;

use Chronopost\Chronorelais\Controller\Adminhtml\Sales\Impression\AbstractImpression;
use Chronopost\Chronorelais\Helper\Data as HelperData;
use Chronopost\Chronorelais\Lib\PDFMerger\PDFMerger;
use DateTime;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Shipping\Model\CarrierFactory;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class Export
 *
 * @package Chronopost\Chronorelais\Controller\Adminhtml\Sales\Export
 */
class Export extends AbstractImpression
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var CarrierFactory
     */
    protected $carrierFactory;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * Export constructor.
     *
     * @param Context           $context
     * @param DirectoryList     $directoryList
     * @param PageFactory       $resultPageFactory
     * @param HelperData        $helperData
     * @param PDFMerger         $PDFMerger
     * @param ManagerInterface  $messageManager
     * @param CollectionFactory $collectionFactory
     * @param CarrierFactory    $carrierFactory
     * @param Filter            $filter
     */
    public function __construct(
        Context $context,
        DirectoryList $directoryList,
        PageFactory $resultPageFactory,
        HelperData $helperData,
        PDFMerger $PDFMerger,
        ManagerInterface $messageManager,
        CollectionFactory $collectionFactory,
        CarrierFactory $carrierFactory,
        Filter $filter
    ) {
        parent::__construct($context, $directoryList, $resultPageFactory, $helperData, $PDFMerger, $messageManager);
        $this->collectionFactory = $collectionFactory;
        $this->carrierFactory = $carrierFactory;
        $this->filter = $filter;
        $this->helperData = $helperData;
    }

    /**
     * Export action
     *
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $format = $this->getRequest()->getParam('format');
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $this->export($collection, $format);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
            $resultRedirect->setPath("chronorelais/sales/export");

            return $resultRedirect;
        }
    }

    /**
     * Export action
     *
     * @param CollectionFactory $collection
     * @param string            $format
     *
     * @return Export
     * @throws \Exception
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function export($collection, $format = 'css')
    {
        $separator = $this->helperData->getConfig("chronorelais/export_" . $format . "/field_separator");
        $delimiter = $this->helperData->getConfig("chronorelais/export_" . $format . "/field_delimiter");

        if ($delimiter == 'simple_quote') {
            $delimiter = "'";
        } else {
            if ($delimiter == 'double_quotes') {
                $delimiter = '"';
            } else {
                $delimiter = '';
            }
        }

        $lineBreak = $this->helperData->getConfig("chronorelais/export_" . $format . "/endofline_character");
        if ($lineBreak == 'lf') {
            $lineBreak = "\n";
        } else {
            if ($lineBreak == 'cr') {
                $lineBreak = "\r";
            } else {
                if ($lineBreak == 'crlf') {
                    $lineBreak = "\r\n";
                }
            }
        }

        $fileExtension = $this->helperData->getConfig("chronorelais/export_" . $format . "/file_extension");
        $fileCharset = $this->helperData->getConfig("chronorelais/export_" . $format . "/file_charset");
        $filename = 'orders_export' . $format . '_' . date('Ymd_His') . $fileExtension;

        // Initialize the content variable
        $content = '';
        $weightUnit = $this->helperData->getConfig("chronorelais/weightunit/unit");
        foreach ($collection->getItems() as $order) {
            $address = $order->getShippingAddress();
            $billingAddress = $order->getBillingAddress();

            $_shippingMethod = explode('_', $order->getShippingMethod());
            $_shippingMethod = $_shippingMethod[1];
            $carrier = $this->carrierFactory->get($_shippingMethod);

            // Customer ID
            $content = $this->_addFieldToCsv(
                $content,
                $delimiter,
                ($order->getCustomerId() ? $order->getCustomerId() : $address->getLastname())
            );
            $content .= $separator;

            // Name of relay point OR company if home delivery
            $content = $this->_addFieldToCsv($content, $delimiter, $address->getCompany());
            $content .= $separator;

            // Customer name
            $content = $this->_addFieldToCsv(
                $content,
                $delimiter,
                ($address->getFirstname() ? $address->getFirstname() : $billingAddress->getFirstname())
            );
            $content .= $separator;
            $content = $this->_addFieldToCsv(
                $content,
                $delimiter,
                ($address->getLastname() ? $address->getLastname() : $billingAddress->getLastname())
            );
            $content .= $separator;

            // Street address
            $content = $this->_addFieldToCsv($content, $delimiter, $this->getValue($address->getStreetLine(1)));
            $content .= $separator;
            $content = $this->_addFieldToCsv($content, $delimiter, $this->getValue($address->getStreetLine(2)));
            $content .= $separator;

            // Digital code
            $content = $this->_addFieldToCsv($content, $delimiter, '');
            $content .= $separator;

            // Postcode
            $content = $this->_addFieldToCsv($content, $delimiter, $this->getValue($address->getPostcode()));
            $content .= $separator;

            // City
            $content = $this->_addFieldToCsv($content, $delimiter, $this->getValue($address->getCity()));
            $content .= $separator;

            // Country code
            $content = $this->_addFieldToCsv($content, $delimiter, $this->getValue($address->getCountryId()));
            $content .= $separator;

            // Telephone
            $telephone = '';
            if ($address->getTelephone()) {
                $telephone = trim(preg_replace('[^0-9.-]', ' ', $address->getTelephone()));
                $telephone = (strlen($telephone) >= 10 ? $telephone : '');
            }

            $content = $this->_addFieldToCsv($content, $delimiter, $telephone);
            $content .= $separator;

            // Email
            $customer_email = ($address->getEmail()) ? $address->getEmail() :
                ($billingAddress->getEmail() ? $billingAddress->getEmail() : $order->getCustomerEmail());
            $content = $this->_addFieldToCsv($content, $delimiter, $this->getValue($customer_email));
            $content .= $separator;

            // Real order ID
            $content = $this->_addFieldToCsv($content, $delimiter, $this->getValue($order->getRealOrderId()));
            $content .= $separator;

            // EAN (empty)
            $content = $this->_addFieldToCsv($content, $delimiter, '');
            $content .= $separator;

            // Product code
            $productCode = $carrier->getChronoProductCodeStr();
            $content = $this->_addFieldToCsv($content, $delimiter, $productCode);
            $content .= $separator;

            // Account (empty)
            $content = $this->_addFieldToCsv($content, $delimiter, '');
            $content .= $separator;

            // Sub Account (empty)
            $content = $this->_addFieldToCsv($content, $delimiter, '');
            $content .= $separator;

            // Empty fields
            $content = $this->_addFieldToCsv($content, $delimiter, 0);
            $content .= $separator;
            $content = $this->_addFieldToCsv($content, $delimiter, 0);
            $content .= $separator;

            // document / marchandise (empty)
            $content = $this->_addFieldToCsv($content, $delimiter, '');
            $content .= $separator;

            // Content description (empty)
            $content = $this->_addFieldToCsv($content, $delimiter, '');
            $content .= $separator;

            // Saturday delivery
            $SaturdayShipping = 'L'; //default value for the saturday shipping
            if ($carrier->canDeliverOnSaturday()) {
                $deliveryOnSaturday = $this->helperData->getShippingSaturdayStatus($order->getId());
                if (!$deliveryOnSaturday) {
                    $deliveryOnSaturday = (bool)$this->helperData->getConfig(
                        'carriers/' . $carrier->getCarrierCode() . '/deliver_on_saturday'
                    );
                } else {
                    if ($deliveryOnSaturday === 'Yes') {
                        $deliveryOnSaturday = true;
                    } else {
                        $deliveryOnSaturday = false;
                    }
                }

                $isSendingDay = $this->helperData->isSendingDay();
                if ($deliveryOnSaturday === true && $isSendingDay == true) {
                    $SaturdayShipping = 'S';
                } elseif ($isSendingDay == true) {
                    $SaturdayShipping = 'L';
                }
            }

            $content = $this->_addFieldToCsv($content, $delimiter, $SaturdayShipping);
            $content .= $separator;

            // Chronorelay point
            $content = $this->_addFieldToCsv($content, $delimiter, $this->getValue($order->getRelaisId()));
            $content .= $separator;

            // Total weight (in kg)
            $order_weight = number_format((float)$order->getWeight(), 2, '.', '');
            if ($weightUnit == 'g') {
                $order_weight = $order_weight / 1000;
            }
            $content = $this->_addFieldToCsv($content, $delimiter, $order_weight);
            $content .= $separator;

            // Width (empty)
            $content = $this->_addFieldToCsv($content, $delimiter, '');
            $content .= $separator;

            // Length (empty)
            $content = $this->_addFieldToCsv($content, $delimiter, '');
            $content .= $separator;

            // Height (empty)
            $content = $this->_addFieldToCsv($content, $delimiter, '');
            $content .= $separator;

            // Notify recipient (empty)
            $content = $this->_addFieldToCsv($content, $delimiter, '');
            $content .= $separator;

            // Parcel number (empty)
            $content = $this->_addFieldToCsv($content, $delimiter, '');
            $content .= $separator;

            // Date
            $content = $this->_addFieldToCsv($content, $delimiter, date('d/m/Y'));
            $content .= $separator;

            // To integrate (empty)
            $content = $this->_addFieldToCsv($content, $delimiter, '');
            $content .= $separator;

            // Notify sender (empty)
            $content = $this->_addFieldToCsv($content, $delimiter, '');
            $content .= $separator;

            /* DLC (empty) */
            $content = $this->_addFieldToCsv($content, $delimiter, '');
            $content .= $separator;

            // Appointment specific fields
            $chronopostsrdv_creneaux_info = $order->getData('chronopostsrdv_creneaux_info');
            if ($chronopostsrdv_creneaux_info) {
                $chronopostsrdv_creneaux_info = json_decode($chronopostsrdv_creneaux_info, true);
                $_dateRdvStart = new DateTime($chronopostsrdv_creneaux_info['deliveryDate']);
                $_dateRdvStart->setTime(
                    (int)$chronopostsrdv_creneaux_info['startHour'],
                    (int)$chronopostsrdv_creneaux_info['startMinutes']
                );

                $_dateRdvEnd = new DateTime($chronopostsrdv_creneaux_info['deliveryDate']);
                $_dateRdvEnd->setTime(
                    (int)$chronopostsrdv_creneaux_info['endHour'],
                    (int)$chronopostsrdv_creneaux_info['endMinutes']
                );

                $content = $this->_addFieldToCsv($content, $delimiter, $_dateRdvStart->format("dmyHi"));
                $content .= $separator;

                $content = $this->_addFieldToCsv($content, $delimiter, $_dateRdvEnd->format("dmyHi"));
                $content .= $separator;

                $content = $this->_addFieldToCsv($content, $delimiter, $chronopostsrdv_creneaux_info['tariffLevel']);
                $content .= $separator;

                $content = $this->_addFieldToCsv($content, $delimiter, $chronopostsrdv_creneaux_info['serviceCode']);
                $content .= $separator;
            } else {
                $content = $this->_addFieldToCsv($content, $delimiter, '');
                $content .= $separator;

                $content = $this->_addFieldToCsv($content, $delimiter, '');
                $content .= $separator;

                $content = $this->_addFieldToCsv($content, $delimiter, '');
                $content .= $separator;

                $content = $this->_addFieldToCsv($content, $delimiter, '');
                $content .= $separator;
            }

            $content .= $lineBreak;
        }

        // Decode the content, depending on the charset
        if ($fileCharset == 'ISO-8859-1') {
            $content = utf8_decode($content);
        }

        // Pick file mime type, depending on the extension
        switch ($fileExtension) {
            case '.csv':
                $fileMimeType = 'application/csv';
                break;
            case '.chr':
                $fileMimeType = 'application/chr';
                break;
            default:
                $fileMimeType = 'text/plain';
                break;
        }

        return $this->prepareDownloadResponse($filename, $content, $fileMimeType . '; charset="' . $fileCharset . '"');
    }

    /**
     * Add a new field to the csv file
     *
     * @param $csvContent
     * @param $fieldDelimiter
     * @param $fieldContent
     *
     * @return string : the concatenation of current content and content to add
     */
    private function _addFieldToCsv($csvContent, $fieldDelimiter, $fieldContent)
    {
        return $csvContent . $fieldDelimiter . $fieldContent . $fieldDelimiter;
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function getValue($value)
    {
        return ($value != '' ? $value : '');
    }

    /**
     * Check is the current user is allowed to access this section
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Chronopost_Chronorelais::sales');
    }
}
