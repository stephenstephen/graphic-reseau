<?php

namespace Netreviews\Avisverifies\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\View\Result\PageFactory;
use Netreviews\Avisverifies\Helper\Data;

class Index extends Action
{
    protected $resultPageFactory;
    protected $date;
    protected $helperData;
    protected $resource;
    protected $fileFactory;
    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection;
    protected $directory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param DateTime $date
     * @param Data $helperData
     * @param ResourceConnection $resource
     * @param FileFactory $fileFactory
     * @param Filesystem $filesystem
     * @throws FileSystemException
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        DateTime $date,
        Data $helperData,
        ResourceConnection $resource,
        FileFactory $fileFactory,
        Filesystem $filesystem
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->date = $date;
        $this->helperData = $helperData;
        $this->resource = $resource;
        $this->connection = $resource->getConnection();
        $this->fileFactory = $fileFactory;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws FileSystemException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        // export csv
        if (!$this->exportCSV()) {
            $page = $this->resultPageFactory->create();

            // Menu highlight
            $page->setActiveMenu('Netreviews_Avisverifies::top_level');

            // Change menu title
            $page->getConfig()->getTitle()->prepend(__('VerifiedReviews'));

            return $page;
        }
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Netreviews_Avisverifies::menu_items');
    }

    /**
     * @return bool
     * @throws FileSystemException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function exportCSV()
    {
        // start export csv
        $o_postExport = $this->getRequest()->getPost();
        if ($o_postExport['selectReviews']==1) {
            $i_storeId = $o_postExport['store_ids'][0];
            $review = $this->resource->getTableName('review');
            $reviewDetail = $this->resource->getTableName('review_detail');
            $ratingOptionVote = $this->resource->getTableName('rating_option_vote');
            $customerEntity = $this->resource->getTableName('customer_entity');
            $catalogProductEntityVarchar = $this->resource->getTableName('catalog_product_entity_varchar');
            $catalogProductEntity = $this->resource->getTableName('catalog_product_entity');
            $eavAttribute = $this->resource->getTableName('eav_attribute');
            $select = $this->connection->select()->from(
                ['review' => $review],
                []
            )
                ->joinLeft(
                    ['reviewDetail'=>$reviewDetail],
                    'review.review_id = reviewDetail.review_id',
                    []
                )
                ->joinLeft(
                    ['ratingOptionVote'=>$ratingOptionVote],
                    'review.review_id = ratingOptionVote.review_id',
                    []
                )
                ->joinLeft(
                    ['customerEntity'=>$customerEntity],
                    'customerEntity.entity_id = reviewDetail.customer_id',
                    []
                )
                ->joinLeft(
                    ['catalogProductEntityVarchar'=>$catalogProductEntityVarchar],
                    'review.entity_pk_value = catalogProductEntityVarchar.value_id',
                    []
                )
                ->joinLeft(
                    ['catalogProductEntity'=>$catalogProductEntity],
                    'catalogProductEntity.entity_id = catalogProductEntityVarchar.value_id',
                    []
                )
                ->joinLeft(
                    ['eavAttribute'=>$eavAttribute],
                    'catalogProductEntityVarchar.attribute_id = eavAttribute.attribute_id',
                    []
                )
                ->where('reviewDetail.store_id =?', $i_storeId)
                ->where('eavAttribute.attribute_code=\'name\'')
                ->columns(
                    [
                        'customer_Id' => $this->connection->getIfNullSql('customerEntity.entity_id', '\'IDGUEST\''),
                        'idProduct' => 'review.entity_pk_value',
                        'note' => 'ratingOptionVote.value',
                        'avis' => 'reviewDetail.detail',
                        'dateavis' => 'review.created_at',
                        'customer_Email' => $this->connection->getIfNullSql('customerEntity.email', '\'anonymous@anonymous.com\''),
                        'customer_Firstname' => new \Zend_Db_Expr($this->connection->getIfNullSql('customerEntity.firstname','reviewDetail.nickname')),
                        'customer_Lastname' => new \Zend_Db_Expr($this->connection->getIfNullSql('customerEntity.lastname','\'A\'')),
                        'product_Name' => 'catalogProductEntityVarchar.value',
                        'SKU' => 'catalogProductEntity.sku',
                        'order_Date' => 'catalogProductEntity.created_at',
                    ]
                )
            ;
            $result = $this->connection->fetchAll($select);
            $columnHeader =  ['Customer_id', 'ProductID', 'Rating', 'Review', 'Review_Date','Email','Firstname','Lastname','Product_name','SKU','Order_reviews'];
            $this->downloadFileCSV('Reviews', $result, $columnHeader);
        }

        if (isset($o_postExport['fromDate'])) {
            // ===== RECUPERAR DATOS DEL FORM =====
            $o_postExport = $this->getExportFormData();
            $i_storeId = $o_postExport['store_ids'][0];
            $a_status = $o_postExport['checkboxStatus'];
            $i_products = $o_postExport['selectProducts'];
            $fromDate = $o_postExport['fromDate'] . ' 00:00:00'; // yy-mm-dd
            $toDate = $o_postExport['toDate'] . ' 23:59:59'; // yy-mm-dd

            // Asignar valores a las variables del helper Data para poder recuperar la información de los productos para PLA.
            $this->helperData->setIdStore($i_storeId);
            if ($i_storeId == 0) {
                $this->helperData->setDefaultOrStoreOrWebsite('default');
            } else {
                $this->helperData->setDefaultOrStoreOrWebsite('stores');
            }

            // Recuperar delay de la base de datos del cliente si existe.
            $delay = $this->helperData->getSpecificPlatformConfig('delay', $i_storeId, 'stores');
            $delay = ($delay == '' || $delay == null || empty($delay) || !$delay) ? 0 : $delay;

            $csvContent = array();


            // ===== RECUPERAR DATOS DE LOS PEDIDOS SIN PRODUCTOS POR TIENDA =====

            if ($this->isDate($fromDate) === true && $this->isDate($toDate) === true) {
                $o_orders = $this->helperData->getOrdersAddingDateFilter($i_storeId, $fromDate, $toDate);
            } elseif ($this->isDate($fromDate) === true && $this->isDate($toDate) === false) {
                $o_orders = $this->helperData->getOrdersAddingDateFilter($i_storeId, $fromDate);
            } elseif ($this->isDate($fromDate) === false && $this->isDate($toDate) === true) {
                $o_orders = $this->helperData->getOrdersAddingDateFilter($i_storeId, null, $toDate);
            } elseif ($this->isDate($fromDate) === false && $this->isDate($toDate) === false) {
                $o_orders = $this->helperData->getOrdersAddingDateFilter($i_storeId);
            }

            // Filtra los pedidos por status.
            $o_orders = $this->helperData->addFilterStatus($o_orders, $a_status);
            $o_orders->setPageSize(100);
            $pages = $o_orders->getLastPageNumber();
            $currentPage = 1;
            $columnHeader = array('store_id',
                'order_status',
                'id_order',
                'email',
                'lastname',
                'firstname',
                'delayBeforeSendWebsiteReview',
                'date_order',
                'amount'
            );
            $i = 0;
            do {
                $o_orders->setCurPage($currentPage);
                $o_orders->load();
                foreach ($o_orders as $_order) {
                    $csvContent[$i] = array(
                        $_order->getStoreId(),
                        $_order->getStatus(),
                        $_order->getIncrementId(), // use entity_id to get teh DB id.
                        $_order->getCustomerEmail(),
                        $_order->getCustomerLastname(),
                        $_order->getCustomerFirstname(),
                        $delay, // delayBeforeSendWebsiteReview
                        $_order->getCreatedAt(),
                        $_order->getBaseGrandTotal() . ' ' . $_order->getBaseCurrencyCode()
                    );
                    //WITH PRODUCTS RECUPERAR DATOS CON PRODUCTOS POR TIENDA
                    if ($i_products == 1) {
                        $listProducts = $this->helperData->getProducts($_order);
                        $nbProducts = count($listProducts);
                        $onlyOrderInfo = ($nbProducts > 1) ? $csvContent[$i] : null;
                        foreach ($listProducts as $k => $product) {
                            // If there are more of 1 product we take the base data from the current order.
                            if ($k > 0) {
                                $csvContent[$i] = $onlyOrderInfo;
                            }
                            array_push($csvContent[$i], $product['id_product_in_db'], $product['name_product']);
                            ($i == 0 && $k == 0) ? array_push($columnHeader, 'id_product', 'product_name') : null;
                            // If product have SKU then add it.
                            if (array_key_exists('sku', $product)) {
                                array_push($csvContent[$i], $product['sku']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'sku') : null;
                            }
                            // If product have MPN then add it.
                            if (array_key_exists('MPN', $product)) {
                                array_push($csvContent[$i], $product['MPN']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'MPN') : null;
                            }
                            // If product have GTIN/UPC then add it.
                            if (array_key_exists('GTIN_UPC', $product)) {
                                array_push($csvContent[$i], $product['GTIN_UPC']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'GTIN/UPC') : null;
                            }
                            // If product have GTIN/EAN then add it.
                            if (array_key_exists('GTIN_EAN', $product)) {
                                array_push($csvContent[$i], $product['GTIN_EAN']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'GTIN/EAN') : null;
                            }
                            // If product have GTIN/JAN then add it.
                            if (array_key_exists('GTIN_JAN', $product)) {
                                array_push($csvContent[$i], $product['GTIN_JAN']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'GTIN/JAN') : null;
                            }
                            // If product have GTIN/ISBN then add it.
                            if (array_key_exists('GTIN_ISBN', $product)) {
                                array_push($csvContent[$i], $product['GTIN_ISBN']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'GTIN/ISBN') : null;
                            }
                            // If product have brand_name then add it.
                            if (array_key_exists('brand_name', $product)) {
                                array_push($csvContent[$i], $product['brand_name']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'brand') : null;
                            }
                            // If product have category then add it.
                            if (array_key_exists('category', $product)) {
                                array_push($csvContent[$i], $product['category']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'category') : null;
                            }
                            // The rest of product data.
                            array_push($csvContent[$i], $product['url'], $product['url_image']);
                            ($i == 0 && $k == 0) ? array_push($columnHeader, 'url_product', 'url_image') : null;

                            // If product have info1 then add it.
                            if (array_key_exists('info1', $product)) {
                                array_push($csvContent[$i], $product['info1']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'info1') : null;
                            }
                            // If product have info2 then add it.
                            if (array_key_exists('info2', $product)) {
                                array_push($csvContent[$i], $product['info2']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'info2') : null;
                            }

                            // If product have info3 then add it.
                            if (array_key_exists('info3', $product)) {
                                array_push($csvContent[$i], $product['info3']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'info3') : null;
                            }
                            // If product have info4 then add it.
                            if (array_key_exists('info4', $product)) {
                                array_push($csvContent[$i], $product['info4']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'info4') : null;
                            }
                            // If product have info5 then add it.
                            if (array_key_exists('info5', $product)) {
                                array_push($csvContent[$i], $product['info5']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'info5') : null;
                            }
                            // If product have info6 then add it.
                            if (array_key_exists('info6', $product)) {
                                array_push($csvContent[$i], $product['info6']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'info6') : null;
                            }
                            // If product have info7 then add it.
                            if (array_key_exists('info7', $product)) {
                                array_push($csvContent[$i], $product['info7']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'info7') : null;
                            }
                            // If product have info8 then add it.
                            if (array_key_exists('info8', $product)) {
                                array_push($csvContent[$i], $product['info8']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'info8') : null;
                            }
                            // If product have info9 then add it.
                            if (array_key_exists('info9', $product)) {
                                array_push($csvContent[$i], $product['info9']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'info9') : null;
                            }
                            // If product have info10 then add it.
                            if (array_key_exists('info10', $product)) {
                                array_push($csvContent[$i], $product['info10']);
                                ($i == 0 && $k == 0) ? array_push($columnHeader, 'info10') : null;
                            }
                            // delayBeforeSendProductReview
                            array_push($csvContent[$i], '0');
                            ($i == 0 && $k == 0) ? array_push($columnHeader, 'delayBeforeSendProductReview') : null;
                            $i++;
                        }
                        // =============== WITH PRODUCTS END ===============
                    } else {
                        $i++;
                    }
                }

                $currentPage++;
                //clear collection and free memory
                $o_orders->clear();
            } while ($currentPage <= $pages);
            // =============== CREACION DEL CSV ===============
            $fromOnlyDate = str_replace(' 00:00:00', '', $fromDate);
            $toOnlyDate = str_replace(' 23:59:59', '', $toDate);
            $fileName = 'NetReviews_LastOrders_StoreId_' . $i_storeId . '_from(' . $fromOnlyDate . ')_to(' . $toOnlyDate . ')';
            $this->downloadFileCSV($fileName, $csvContent, $columnHeader);
            return true;
        }

        return false;
    }

    // EJECUTA LA DERCARGA DEL ARCHIVO
    /**
     * @param $name
     * @param $data
     * @param $columnHeader
     * @return mixed
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function downloadFileCSV($name, $datas, $columnHeader)
    {
        try {
            $name .= date('d_m_Y_H_i_s');
            $filepath = 'tmp' . $name . '.csv';
            $this->directory->create('tmp');
            $stream = $this->directory->openFile($filepath, 'w+');
            $stream->lock();
            $stream->writeCsv($columnHeader);
            foreach ($datas as $data) {
                $data = array_values($data);
                $stream->writeCsv($data);
            }
            $content = [];
            $content['type'] = 'filename';// must keep filename
            $content['value'] = $filepath;
            $content['rm'] = '1';
            $csvfilename = $name . '.csv';
            return $this->fileFactory->create($csvfilename, $content, DirectoryList::VAR_DIR, 'Content-type: text/csv');
        } catch (FileSystemException $e) {
            throw new FileSystemException(
                new \Magento\Framework\Phrase('Cannot write csv netreviews. %1', [$e->getMessage()])
            );
        }
    }

    /**
     * RECUPERAR DATOS DEL FORMULARIO EXPORT
     *
     * @return object
     */
    protected function getExportFormData()
    {
        return $this->getRequest()->getPost();
    }

    /**
     * OBTENER LA FECHA DE HOY CON EL FORMATO DE MAGENTO
     *
     * @return string $magentoDateNow
     */
    protected function getMagentoCurrentDate()
    {
        return $this->date->gmtDate();
    }

    /**
     * VERIFICAR SI EL VALOR DADO ES UNA FECHA EN FORMATO ALGUN FORMATO CON GUION
     *
     * @return boolean
     */
    protected function isDate($_date)
    {
        if (strpos($_date, '-') !== false) {
            return true;
        }
        return false;
    }
}
