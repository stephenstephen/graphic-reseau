<?php
namespace Netreviews\Avisverifies\Helper;

use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\OrderRepository;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use \Magento\Store\Model\StoreRepository;

class Data extends AbstractHelper
{
    const XML_PATH_AVISVERIFIES = 'av_configuration/';
    const XML_PATH_AVISVERIFIES_MAIN = 'system_integration/';
    const XML_PATH_AVISVERIFIES_ADVANCED = 'advanced_configuration/';
    const XML_PATH_AVISVERIFIES_PLATFORM = 'plateforme/';
    const XML_PATH_AVISVERIFIES_PLA = 'av_pla/fields_mapping/';
    public $idwebsite;
    public $secretkey;
    public $SHA1;
    public $idStore;
    public $defaultOrStoreOrWebsite;
    public $allStoresId;
    protected $orderRepository;
    protected $storeManager;
    protected $helperReviewsAPI;
    protected $orderStatusCollection;
    protected $orderFactory;
    protected $productLoad;
    protected $storeRepository;
    protected $ordersCollection;
    protected $logger;

    /**
     * Data constructor.
     * @param Context $context
     * @param CollectionFactory $orderStatusCollection
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderFactory
     * @param ProductFactory $productLoad
     * @param StoreManagerInterface $storeManager
     * @param OrderRepository $orderRepository
     * @param StoreRepository $storeRepository
     * @param ReviewsAPI $helperReviewsAPI
     */
    public function __construct(
        Context $context,
        CollectionFactory $orderStatusCollection,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderFactory,
        ProductFactory $productLoad,
        StoreManagerInterface $storeManager,
        OrderRepository $orderRepository,
        StoreRepository $storeRepository,
        ReviewsAPI $helperReviewsAPI
    ) {
        $this->orderRepository = $orderRepository;
        $this->storeManager = $storeManager;
        $this->orderStatusCollection = $orderStatusCollection;
        $this->orderFactory = $orderFactory;
        $this->productLoad = $productLoad;
        $this->logger = $context->getLogger();
        $this->storeRepository = $storeRepository;
        $this->helperReviewsAPI = $helperReviewsAPI;
        parent::__construct($context);
    }

    /**
     * setup
     *
     * @param $_msg
     * @return void
     */
    public function setup(array $_msg)
    {
        // On recoit dans l'appel API un idWebsite
        $this->idwebsite = $_msg['idWebsite'];
        // On verifie donc si on a la secretKey correspondante
        $this->secretkey = $this->getModuleSecretKey($_msg['idWebsite']);
        // Get all store ids enabled with the idWebsite received
        $this->allStoresId = $this->getModuleActiveStoresIds($_msg['idWebsite']);
        /*
        * SHA1, secret Hashing.
        * The SHA1 signature is required to be sure that we can't use the Dialog controller
        * to perform operations without secret key provided.
        */
        $this->SHA1 = SHA1((isset($_msg['query']) ? $_msg['query'] : '') . $this->idwebsite . $this->secretkey);
    }

    /**
     * @param $idWebsite
     * @return mixed|null
     */
    public function getModuleSecretKey($idWebsite)
    {
        try {
            $stores = $this->storeRepository->getList();
            $secretKey = null;
            $isActiveStore = 0;
            $isActiveWebsite = 0;
            foreach ($stores as $store) {
                $storeId = $store['store_id'];
                $websiteId = $store['website_id'];
                $idwebsiteConfig = $this->scopeConfig->getValue('av_configuration/system_integration/idwebsite',
                    ScopeInterface::SCOPE_STORE, $storeId);
                if ($idwebsiteConfig == $idWebsite) {
                    $secretKey = $this->scopeConfig->getValue('av_configuration/system_integration/secretkey',
                        ScopeInterface::SCOPE_STORE, $storeId);
                    $isActiveStore = $this->scopeConfig->getValue('av_configuration/system_integration/enabledwebsite',
                        ScopeInterface::SCOPE_STORE, $storeId);
                    if ($isActiveStore == 1){
                        $this->setIdStore($storeId);
                        $this->setDefaultOrStoreOrWebsite(ScopeInterface::SCOPE_STORES);
                    }
                }
                $idwebsiteConfig = $this->scopeConfig->getValue('av_configuration/system_integration/idwebsite',
                    ScopeInterface::SCOPE_WEBSITE, $websiteId);
                if ($idwebsiteConfig == $idWebsite) {
                    $secretKey = $this->scopeConfig->getValue('av_configuration/system_integration/secretkey',
                        ScopeInterface::SCOPE_WEBSITE, $websiteId);
                    $isActiveWebsite = $this->scopeConfig->getValue('av_configuration/system_integration/enabledwebsite',
                        ScopeInterface::SCOPE_WEBSITE, $websiteId);
                    if ($isActiveWebsite == 1){
                        $this->setIdStore($websiteId);
                        $this->setDefaultOrStoreOrWebsite(ScopeInterface::SCOPE_WEBSITE);
                    }
                }
                if ( $isActiveStore == 1 || $isActiveWebsite == 1){
                    break;
                }
            } // foreach
            return $secretKey;
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
        return null;
    }

    /**
     * setIdStore
     *
     * @param $val
     * @return void
     */
    public function setIdStore($val)
    {
        $this->idStore = $val;
    }

    /**
     * setDefaultOrStoreOrWebsite
     *
     * @param $val
     * @return void
     */
    public function setDefaultOrStoreOrWebsite($val)
    {
        $this->defaultOrStoreOrWebsite = $val;
    }

    /**
     * getModuleActiveStoresIds
     * WE ARE GOING TO FILTER BY IDWEBSITE AND IS ACTIVE.
     *
     * @param $idWebsite
     * @return array
     */
    public function getModuleActiveStoresIds($idWebsite)
    {
        $stores = $this->storeRepository->getList();
        $secretKey = null;
        $activeStores = array();
        $i = 0;
        foreach ($stores as $store) {
            $storeId = $store['store_id'];
            $websiteId = $store['website_id'];
            $enabledwebsite = $this->scopeConfig->getValue('av_configuration/system_integration/enabledwebsite',
                    ScopeInterface::SCOPE_STORE, $storeId);
            $idwebsiteActive = $this->scopeConfig->getValue('av_configuration/system_integration/idwebsite',
                    ScopeInterface::SCOPE_STORE, $storeId);
            if ($idwebsiteActive == $idWebsite && $enabledwebsite == 1) {
                $activeStores[$i]['scope'] = ScopeInterface::SCOPE_STORES;
                $activeStores[$i]['scope_id'] = $storeId;
                $i++;
            }
            $enabledwebsite = $this->scopeConfig->getValue('av_configuration/system_integration/enabledwebsite', ScopeInterface::SCOPE_WEBSITE, $websiteId);
            $idwebsiteActive = $this->scopeConfig->getValue('av_configuration/system_integration/idwebsite', ScopeInterface::SCOPE_WEBSITE, $websiteId);
            if ($idwebsiteActive == $idWebsite && $enabledwebsite == 1) {
                $activeStores[$i]['scope'] = ScopeInterface::SCOPE_WEBSITE;
                $activeStores[$i]['scope_id'] = $websiteId;
                $i++;
            }
        }
        return $activeStores;
    }

    /**
     * getAllStatus
     * RECUPERA TODOS LOS STATUS Y SON STATE ASOCIADO EXISTENTES EN EL SITIO.
     *
     * @return array
     */
    public function getAllStatus()
    {
        $arrayStatusCollection = $this->orderStatusCollection->create()->load()->toOptionHash();
        return $arrayStatusCollection;
    }

    /**
     * getAllWebsites
     * RECUPERA TODOS LOS WEBSITES DE LA TABLA 'STORE_WEBSITE'.
     *
     * @return array
     */
    public function getAllWebsites()
    {
        $allWebsites = $this->storeManager->getWebsites();
        foreach ($allWebsites as $val) {
            $websites_mapping[] = $val->getData();
        }
        return ($websites_mapping) ? $websites_mapping : null;
    }

    /**
     * getAllStores
     * RECUPERA TODAS LAS TIENDAS DE LA TABLA 'STORE'.
     *
     * @return array
     */
    public function getAllStores()
    {
        $allStores = $this->storeManager->getStores();
        foreach ($allStores as $val) {
            $stores_mapping[] = $val->getData();
        }
        return ($stores_mapping) ? $stores_mapping : null;
    }

    /**
     * getStoresWithIdwebsite
     * CREA UN ARRAY QUE PUEDE SER LEIDO POR LA PLATAFARMA DE NETREVIEWS CON LOS WEBSITES Y TIENDAS
     *
     * @param $_websites_mapping
     * @param $_stores_mapping
     * @return array
     */
    public function getStoresWithIdwebsite($_websites_mapping, $_stores_mapping)
    {
        // Simplify Stores array
        $i = 0;
        foreach ($_stores_mapping as $store) {
            $stores_mapping[$i]['name'] = $store['name'] . ' {' . $store['store_id'] . '}';
            $stores_mapping[$i]['website'] = $store['website_id'];
            $enabledwebsite = $this->scopeConfig->getValue('av_configuration/system_integration/enabledwebsite',
                    ScopeInterface::SCOPE_STORE, $store['store_id']);
            $stores_mapping[$i]['is_active'] = $enabledwebsite;
            $idwebsite = $this->scopeConfig->getValue('av_configuration/system_integration/idwebsite',
                    ScopeInterface::SCOPE_STORE, $store['store_id']);
            $stores_mapping[$i]['website_id'] = $idwebsite;
            $i++;
        }
        // Simplify Websites array and add Stores in Websites array
        $i = 0;
        foreach ($_websites_mapping as $website) {
            // Simplify Websites array
            $websites_stores_mapping[$i]['name'] = $website['name'] . ' {' . $website['website_id'] . '}';
            $idwebsiteConfig = $this->scopeConfig->getValue('av_configuration/system_integration/enabledwebsite',
                    ScopeInterface::SCOPE_WEBSITE, $website['website_id']);
            $websites_stores_mapping[$i]['is_active'] = $idwebsiteConfig;
            $idwebsite = $this->scopeConfig->getValue('av_configuration/system_integration/idwebsite',
                    ScopeInterface::SCOPE_WEBSITE, $website['website_id']);
            // NetReviews idwebsite
            $websites_stores_mapping[$i]['AVwebsiteId'] = $idwebsite;
            // Add Stores in Websites array
            $k = 0;
            foreach ($stores_mapping as $store) {
                if ($store['website'] == $website['website_id']) {
                    if (!array_key_exists('is_active', $store) && array_key_exists('is_active',
                            $websites_stores_mapping[$i])) {
                        $store['is_active'] = $websites_stores_mapping[$i]['is_active'];
                    }
                    if (!array_key_exists('website_id', $store) && array_key_exists('AVwebsiteId',
                            $websites_stores_mapping[$i])) {
                        $store['website_id'] = $websites_stores_mapping[$i]['AVwebsiteId'];
                    }
                    $websites_stores_mapping[$i]['stores'][$k] = $store;
                }
                $k++;
            }
            $i++;
        }
        $site_mapping = array(
            'all' => 1,
            'webistes' => $websites_stores_mapping
        );
        return $site_mapping;
    }

    /**
     * getOrdersAddingFilters
     * AGREGA UN FILTRO PARA RECUPERAR LOS PEDIDOS DE UNA SOLA TIENDA.
     *
     * @param $_storeId
     * @param $_status - Status seleccionados para recuperar los pedidos.
     * @return object
     */
    public function getOrdersAddingStatusFilter($_storeId, $_status = null)
    {
        $this->setOrdersCollection();
        $this->addLimitOrderCollection($_storeId);
        $this->ordersCollection->addAttributeToFilter('store_id', $_storeId);
        $this->ordersCollection->addAttributeToFilter('av_flag', 0);
        if ($_status != null) {
            $status = explode(';', $_status);
            $this->ordersCollection->addFieldToFilter('status', array('in' => $status));
        }
        return $this->ordersCollection;
    }

    /**
     * setIdStore
     *
     * @return void
     */
    protected function setOrdersCollection()
    {
        $fieldToSelect = [
            'store_name',
            'store_id',
            'av_flag',
            'status',
            'customer_email',
            'increment_id',
            'created_at',
            'base_grand_total',
            'customer_firstname',
            'customer_lastname',
            'base_currency_code'
        ];
        $this->ordersCollection = $this->orderFactory->create()->addFieldToSelect($fieldToSelect);
    }

    /**
     * @param $currentStoreId
     */
    protected function addLimitOrderCollection($currentStoreId)
    {
        $limit = $this->getAdvancedConfig('limit_orders', $currentStoreId);
        if(isset($limit)){
            $this->ordersCollection->setPageSize($limit);  // means limit(0 , $limit)
        }
    }

    /**
     * @param int $_storeId
     * @param string $_fromDate
     * @param string $_toDate
     * @return mixed
     */
    public function getOrdersAddingDateFilter(
        $_storeId = 0,
        $_fromDate = '1990-01-30 00:00:00',
        $_toDate = '2032-12-31 23:59:59'
    ) {
        $this->setOrdersCollection();
        $o_getAllOrders = $this->ordersCollection;
        if ($_storeId != 0 && !empty($_storeId)) {
            $o_getAllOrders->addFieldToFilter('store_id', $_storeId);
        }
        $fromDate = date('Y-m-d H:i:s', strtotime($_fromDate));
        $toDate = date('Y-m-d H:i:s', strtotime($_toDate));
        $o_getAllOrders->addFieldToFilter('created_at', array('from' => $fromDate, 'to' => $toDate));
        return $o_getAllOrders;
    }

    /**
     * @param $o_getAllOrders
     * @param $_emails
     * @return mixed
     */
    public function addFilterForbiddenEmails($o_getAllOrders, $_emails)
    {
        if ($_emails != null) {
            $emails = explode(';', $_emails);
            foreach ($emails as $key => $email) {
                if (!empty($email)) {
                    $o_getAllOrders->addFieldToFilter('customer_email', array('nlike' => '%@' . $email . '%'));
                }
            }
        }
        return $o_getAllOrders;
    }

    /**
     * @param $_orders
     * @param $_status
     * @return mixed
     */
    public function addFilterStatus($_orders, $_status)
    {
        if (is_array($_status)) {
            $_orders->addFieldToFilter('status', array('in' => $_status));
        }
        return $_orders;
    }

    /**
     * retourne la liste des produits avec les produit fils et parents
     * @param $_order
     * @return array
     * @throws NoSuchEntityException
     */
    public function getProducts($_order)
    {
        $currentStoreId = $_order->getStoreId();
        $this->idStore = ($this->idStore == null) ? $currentStoreId : $this->idStore;
        $store = $this->storeManager->getStore();
        $useParentSku = $this->getAdvancedConfig('use_parent_sku', $currentStoreId);
        $o_items = ($useParentSku == 1) ? $_order->getAllVisibleItems() : $_order->getAllItems(); // Sólo Padres : Hijos y su(s) Padre(s) correspondiente(s)
        $listProducts = array();
        $i = 0;
        $idOrSku = $this->helperReviewsAPI->getIdOrSku($currentStoreId);
        foreach ($o_items as $_item) {
            $listProducts[$i]['_itemType'] = $_item->getProductType();
            $listProducts[$i]['get_id_or_sku'] = $idOrSku;
            $listProducts[$i]['useParentSkuhere'] = $useParentSku; // Get real Id Product in data base for export file.
            // Quita los productos Padre si se elige recuperar la información de los Hijos.
            if ($useParentSku != 1) {
                if ($listProducts[$i]['_itemType'] == 'configurable' || $listProducts[$i]['_itemType'] == 'bundle') {
                    continue;
                }
            }
            $o_product = $this->productLoad->create()->load($_item->getProductId());
            // Image URL
            $listProducts[$i]['sku'] = $o_product->getSku();
            $listProducts[$i]['id_product'] = $o_product->getData($idOrSku); //reference produit pour la plateforme
            $listProducts[$i]['id_product_in_db'] = $o_product->getData('entity_id'); // Get real Id Product in data base.
            $listProducts[$i]['name_product'] = $o_product->getData('name'); // We want to take children name.
            $listProducts[$i]['url'] = $o_product->getProductUrl();
            $listProducts[$i]['url_image'] = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $o_product->getImage(); // We want to take children image.
            // Get extra data PLA - Google Shopping
            $listProducts[$i] = $this->getExtraProductData($o_product, $listProducts[$i], $currentStoreId);
            $i++;
        }
        return $listProducts;
    }

    /**
     * getAdvancedConfig
     *
     * @param $code
     * @param $storeId
     * @return string
     */
    public function getAdvancedConfig($code, $storeId = null)
    {
        $storeId = $this->getCurrentIdStoreOrIdWebsiteConfigured($storeId);
        return $this->getConfigValue(self::XML_PATH_AVISVERIFIES . self::XML_PATH_AVISVERIFIES_ADVANCED . $code,
            $storeId);
    }

    /**
     * getCurrentIdStoreOrIdWebsiteConfigured
     * SI NO DAMOS EL ID DE LA TIENDA/WEBSITE TOMA EN CUENTA EL ID DE LA TIENDA/WEBSITE
     * CON LA CONFIGURACIÓN RECUPERADA CONFORME A LA CLAVE SECRETA Y EL IDWEBSITE.
     * @param $storeId
     * @return string
     */
    public function getCurrentIdStoreOrIdWebsiteConfigured($storeId)
    {
        if ($storeId == null) {
            $storeId = $this->idStore;
        }
        return $storeId;
    }

    /**
     * getConfigValue
     *
     * @param $_field
     * @param $_storeId
     * @return object
     */
    public function getConfigValue($_field, $_storeId = null)
    {
        $storeId = $this->getCurrentIdStoreOrIdWebsiteConfigured($_storeId);
        return $this->scopeConfig->getValue($_field, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * retourne la mappping  entre les attributs magento et les attributs plateforme
     * @param $o_product
     * @param $listProducts
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getExtraProductData($o_product, $listProducts, $currentStoreId)
    {
        $att_id = $this->getSpecificPlaConfig('id', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_id && $att_id != '---') {
            $listProducts['id_product'] = $o_product->getData($att_id);
            $listProducts['PLA_att_id'] = $att_id;
        }
        $att_sku = $this->getSpecificPlaConfig('sku', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_sku && $att_sku != '---') {
            $listProducts['sku'] = $o_product->getData($att_sku);
            $listProducts['PLA_att_sku'] = $att_sku;
        }
        $att_name = $this->getSpecificPlaConfig('description', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_name && $att_name != '---') {
            $listProducts['name_product'] = $o_product->getData($att_name);
            $listProducts['PLA_att_name'] = $att_name;
        }
        $att_url = $this->getSpecificPlaConfig('link', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_url && $att_url != '---') {
            $listProducts['url'] = $o_product->getData($att_url);
            $listProducts['PLA_att_url'] = $att_url;
        }
        $att_image = $this->getSpecificPlaConfig('image_link', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_image && $att_image != '---') {
            $listProducts['url_image'] = $o_product->getData($att_image);
            $listProducts['PLA_att_image'] = $att_image;
        }
        $att_brand = $this->getSpecificPlaConfig('brand', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_brand && $att_brand != '---') {
            $listProducts['brand_name'] = $o_product->getData($att_brand);
            // If value is a number then rather get Text.
            $listProducts['brand_name'] = $this->getAttributeAsText($o_product, $listProducts['brand_name'],
                $att_brand);
            $listProducts['PLA_att_brand'] = $att_brand;
        }
        $att_category = $this->getSpecificPlaConfig('category', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_category && $att_category != '---') {
            $listProducts['category'] = $o_product->getData($att_category);
            // If value is a number then rather get Text.
            $listProducts['category'] = $this->getAttributeAsText($o_product, $listProducts['category'], $att_category);
            $listProducts['PLA_att_category'] = $att_category;
        }
        $att_mpn = $this->getSpecificPlaConfig('mpn', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_mpn && $att_mpn != '---') {
            $listProducts['MPN'] = $o_product->getData($att_mpn);
            // If value is a number then rather get Text.
            $listProducts['MPN'] = $this->getAttributeAsText($o_product, $listProducts['MPN'], $att_mpn);
            $listProducts['PLA_att_mpn'] = $att_mpn;
        }
        $att_gtinUpc = $this->getSpecificPlaConfig('gtin_upc', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_gtinUpc && $att_gtinUpc != '---') {
            $listProducts['GTIN_UPC'] = $o_product->getData($att_gtinUpc);
            // If value is a number then rather get Text.
            $listProducts['GTIN_UPC'] = $this->getAttributeAsText($o_product, $listProducts['GTIN_UPC'], $att_gtinUpc);
            $listProducts['PLA_att_gtinUpc'] = $att_gtinUpc;
        }
        $att_gtinEan = $this->getSpecificPlaConfig('gtin_ean', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_gtinEan && $att_gtinEan != '---') {
            $listProducts['GTIN_EAN'] = $o_product->getData($att_gtinEan);
            // If value is a number then rather get Text.
            $listProducts['GTIN_EAN'] = $this->getAttributeAsText($o_product, $listProducts['GTIN_EAN'], $att_gtinEan);
            $listProducts['PLA_att_gtinEan'] = $att_gtinEan;
        }
        $att_gtinJan = $this->getSpecificPlaConfig('gtin_jan', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_gtinJan && $att_gtinJan != '---') {
            $listProducts['GTIN_JAN'] = $o_product->getData($att_gtinJan);
            // If value is a number then rather get Text.
            $listProducts['GTIN_JAN'] = $this->getAttributeAsText($o_product, $listProducts['GTIN_JAN'], $att_gtinJan);
            $listProducts['PLA_att_gtinJan'] = $att_gtinJan;
        }
        $att_gtinIsbn = $this->getSpecificPlaConfig('gtin_isbn', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_gtinIsbn && $att_gtinIsbn != '---') {
            $listProducts['GTIN_ISBN'] = $o_product->getData($att_gtinIsbn);
            // If value is a number then rather get Text.
            $listProducts['GTIN_ISBN'] = $this->getAttributeAsText($o_product, $listProducts['GTIN_ISBN'],
                $att_gtinIsbn);
            $listProducts['PLA_att_gtinIsbn'] = $att_gtinIsbn;
        }
        $att_info1 = $this->getSpecificPlaConfig('info1', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_info1 && $att_info1 != '---') {
            $listProducts['info1'] = $o_product->getData($att_info1);
            // If value is a number then rather get Text.
            $listProducts['info1'] = $this->getAttributeAsText($o_product, $listProducts['info1'], $att_info1);
            $listProducts['PLA_att_info1'] = $att_info1;
        }
        $att_info2 = $this->getSpecificPlaConfig('info2', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_info2 && $att_info2 != '---') {
            $listProducts['info2'] = $o_product->getData($att_info2);
            // If value is a number then rather get Text.
            $listProducts['info2'] = $this->getAttributeAsText($o_product, $listProducts['info2'], $att_info2);
            $listProducts['PLA_att_info2'] = $att_info2;
        }
        $att_info3 = $this->getSpecificPlaConfig('info3', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_info3 && $att_info3 != '---') {
            $listProducts['info3'] = $o_product->getData($att_info3);
            // If value is a number then rather get Text.
            $listProducts['info3'] = $this->getAttributeAsText($o_product, $listProducts['info3'], $att_info3);
            $listProducts['PLA_att_info3'] = $att_info3;
        }
        $att_info4 = $this->getSpecificPlaConfig('info4', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_info4 && $att_info4 != '---') {
            $listProducts['info4'] = $o_product->getData($att_info4);
            // If value is a number then rather get Text.
            $listProducts['info4'] = $this->getAttributeAsText($o_product, $listProducts['info4'], $att_info4);
            $listProducts['PLA_att_info4'] = $att_info4;
        }
        $att_info5 = $this->getSpecificPlaConfig('info5', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_info5 && $att_info5 != '---') {
            $listProducts['info5'] = $o_product->getData($att_info5);
            // If value is a number then rather get Text.
            $listProducts['info5'] = $this->getAttributeAsText($o_product, $listProducts['info5'], $att_info5);
            $listProducts['PLA_att_info5'] = $att_info5;
        }
        $att_info6 = $this->getSpecificPlaConfig('info6', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_info6 && $att_info6 != '---') {
            $listProducts['info6'] = $o_product->getData($att_info6);
            // If value is a number then rather get Text.
            $listProducts['info6'] = $this->getAttributeAsText($o_product, $listProducts['info6'], $att_info6);
            $listProducts['PLA_att_info6'] = $att_info6;
        }
        $att_info7 = $this->getSpecificPlaConfig('info7', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_info7 && $att_info7 != '---') {
            $listProducts['info7'] = $o_product->getData($att_info7);
            // If value is a number then rather get Text.
            $listProducts['info7'] = $this->getAttributeAsText($o_product, $listProducts['info7'], $att_info7);
            $listProducts['PLA_att_info7'] = $att_info7;
        }
        $att_info8 = $this->getSpecificPlaConfig('info8', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_info8 && $att_info8 != '---') {
            $listProducts['info8'] = $o_product->getData($att_info8);
            // If value is a number then rather get Text.
            $listProducts['info8'] = $this->getAttributeAsText($o_product, $listProducts['info8'], $att_info8);
            $listProducts['PLA_att_info8'] = $att_info8;
        }
        $att_info9 = $this->getSpecificPlaConfig('info9', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_info9 && $att_info9 != '---') {
            $listProducts['info9'] = $o_product->getData($att_info9);
            // If value is a number then rather get Text.
            $listProducts['info9'] = $this->getAttributeAsText($o_product, $listProducts['info9'], $att_info9);
            $listProducts['PLA_att_info9'] = $att_info9;
        }
        $att_info10 = $this->getSpecificPlaConfig('info10', $currentStoreId, $this->defaultOrStoreOrWebsite);
        if ($att_info10 && $att_info10 != '---') {
            $listProducts['info10'] = $o_product->getData($att_info10);
            // If value is a number then rather get Text.
            $listProducts['info10'] = $this->getAttributeAsText($o_product, $listProducts['info10'], $att_info10);
            $listProducts['PLA_att_info10'] = $att_info10;
        }
        return $listProducts;
    }

    /**
     * RECUPERA LA CONFIGURACIÓN PLA PARA UN WEBSITE NO PARA UNA TIENDA.
     * @param $code
     * @param $_id
     * @param $_storeOrWebsite
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getSpecificPlaConfig($code, $_id, $_storeOrWebsite)
    {
        $scope = ScopeInterface::SCOPE_STORE;
        if ($_storeOrWebsite == 'websites') {
            $scope = ScopeInterface::SCOPE_WEBSITE;
        }
        $configValue = $this->scopeConfig->getValue(self::XML_PATH_AVISVERIFIES_PLA . $code, $scope, $_id);
        return $configValue;
    }

    /**objectManager
     * getAttributeAsText
     * IF SELECTBOX VALUE IS A NUMBER THEN RATHER GET TEXT.
     *
     * @param $o_product
     * @param $_val
     * @param $_attibute
     * @return string
     */
    public function getAttributeAsText($o_product, $_val, $_attibute)
    {
        if (is_numeric($_val)) {
            $val_text = $o_product->getAttributeText($_attibute);
            if ($val_text) {
                return $val_text;
            }
        }
        return $_val;
    }

    /**
     * commande est flaguée récupérée pu pas
     * @param $orderId
     * @return array
     * @throws \Magento\Framework\Exception\InputException
     * @throws NoSuchEntityException
     */
    public function isFlag($orderId)
    {
        $order = $this->orderRepository->get($orderId);
        $orderFlag = array();
        $orderFlag['av_flag'] = $order->getAvFlag();
        $orderFlag['av_horodate_get'] = $order->getAvHorodateGet();
        return $orderFlag;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $setFlag
     * @param $typePeriod
     * @return mixed
     * @throws \Magento\Framework\Exception\InputException
     * @throws NoSuchEntityException
     */
    public function flagOrders($startDate, $endDate, $setFlag, $typePeriod)
    {
        // Get stores actives for a specific IdWebsite
        $activeStores = $this->allStoresId;
        // Check if $activeStores have websites and get its stores id (because if we have a website it is active)
        if (is_array($activeStores)) {
            foreach ($activeStores as $val) {
                if ($val['scope'] == 'websites') {
                    $storesInWebsite = $this->getAllStoresIdsForWebsite($val['scope_id']);
                    $activeStores = array_unique(array_merge($activeStores, $storesInWebsite), SORT_REGULAR);
                }
            }
        }
        $i = 0;
        $reponse[0] = 'Set orders flag successful!';
        // Get orders for each Store
        if (is_array($activeStores)) {
            foreach ($activeStores as $currentStore) {
                if ($currentStore['scope'] == 'websites' || $currentStore['scope'] == 'default') {
                    continue;
                }
                $o_orders = $this->getOrdersAddingDateFilterForFlag($currentStore['scope_id'], $startDate, $endDate,
                    $setFlag, $typePeriod);
                if ($setFlag==1) {
                    foreach ($o_orders as $_order) {
                        // =============== FLAG ALL ORDERS TO 1 ===============
                        if ($this->flagOrderTo1($_order) != 1) {
                            $reponse[0] = 'WARNING! Error to flag some orders!';
                            $reponse['NO_FLAG'][$i] = $_order->getIncrementId();
                        }
                        // =============== FLAG ALL ORDERS TO 1 END ===============
                        $i++;
                    } // endForeach $o_orders
                } elseif ($setFlag == 0) {
                    foreach ($o_orders as $_order) {
                        // =============== FLAG ALL ORDERS TO 0 ===============
                        if ($this->flagOrderTo0($_order) != 1) {
                            $reponse[0] = 'WARNING! Error to flag some orders!';
                            $reponse['NO_FLAG'][$i] = $_order->getIncrementId();
                        }
                        // =============== FLAG ALL ORDERS TO 0 END ===============
                        $i++;
                    }
                }
            }
        }
        $reponse['totalOrders'] =$i;
        return $reponse;
    }

    /**
     * getAllStoresIdsForWebsite
     * get all store by website_id.
     *
     * @param $idWebsiteMagento
     * @return array
     */
    public function getAllStoresIdsForWebsite($idWebsiteMagento)
    {
        $stores = $this->storeRepository->getList();
        $i = 0;
        foreach ($stores as $store) {
            if ($store['website_id'] == $idWebsiteMagento) {
                $activeStores[$i]['scope'] = 'stores';
                $activeStores[$i]['scope_id'] = $store['store_id'];
            }
        }
        return $activeStores;
    }

    /**
     * @param $_storeId
     * @param $_fromDate
     * @param $_toDate
     * @param $setFlag
     * @param $typePeriod
     * @return mixed
     */
    public function getOrdersAddingDateFilterForFlag(
        $_storeId,
        $_fromDate,
        $_toDate,
        $setFlag,
        $typePeriod
    ) {
        $this->setOrdersCollection();
        $o_getAllOrders = $this->ordersCollection;
        if ($_storeId != 0 && !empty($_storeId)) {
            $o_getAllOrders->addFieldToFilter('store_id', $_storeId);
        }
        if ($typePeriod == 'allOrders') {
            $fromDate = '1990-01-30';
            $toDate = '2032-12-31';
            } else {
            $fromDate = date('Y-m-d', $_fromDate);
            $toDate = date('Y-m-d', $_toDate);
            }
        $o_getAllOrders->addFieldToFilter('created_at', array('from' => $fromDate, 'to' => $toDate));
        if ($setFlag == 1) {
            $o_getAllOrders->addFieldToFilter('av_flag', 0);
            $o_getAllOrders->addFieldToFilter('av_horodate_get', array('null' => true));
        } elseif ($setFlag == 0) {
            $o_getAllOrders->addFieldToFilter('av_flag', array('null' => true));
            $o_getAllOrders->addFieldToFilter('av_horodate_get', array('notnull' => true));
        }
        return $o_getAllOrders;
    }

    /**
     * @param $order
     * @return int
     */
    public function flagOrderTo1($order)
    {
        try {
            $order = $this->orderRepository->get($order->getId());
            $timestamp = strtotime(date('Y-m-d H:i:s'));
            $order->setAvFlag(null);
            $order->setAvHorodateGet($timestamp);
            $order->save();
            return 1;
        } catch (InputException $e) {
            return 0;
        } catch (NoSuchEntityException $e) {
            return 0;
        }
    }

    /**
     * @param $order
     * @return int
     */
    public function flagOrderTo0($order)
    {
        try {
            $order = $this->orderRepository->get($order->getId());
            $order->setAvFlag(0);
            $order->setAvHorodateGet(null);
            $order->save();
            return 1;
        } catch (InputException $e) {
            return 0;
        } catch (NoSuchEntityException $e) {
            return 0;
        }
    }

    /**
     * getMainConfig
     *
     * @param $code
     * @param $storeId
     * @return string
     */
    public function getMainConfig($code, $storeId = null)
    {
        $storeId = $this->getCurrentIdStoreOrIdWebsiteConfigured($storeId);
        return $this->getConfigValue(self::XML_PATH_AVISVERIFIES . self::XML_PATH_AVISVERIFIES_MAIN . $code, $storeId);
    }

    /**
     * getPlatformConfig
     *
     * @param $code
     * @param $storeId
     * @return string
     */
    public function getPlatformConfig($code, $storeId = null)
    {
        $storeId = $this->getCurrentIdStoreOrIdWebsiteConfigured($storeId);
        return $this->getConfigValue(self::XML_PATH_AVISVERIFIES . self::XML_PATH_AVISVERIFIES_PLATFORM . $code,
            $storeId);
    }

    /**
     * getSpecificPlatformConfig
     * RECUPERA LA CONFIGURACIÓN DE LA PLATAFORMA PARA UN UN WEBSITE O UNA TIENDA ESPECÍFICA.
     * @param $code
     * @param $_id
     * @param $_storeOrWebsite
     * @return string
     */
    public function getSpecificPlatformConfig($code, $_id, $_storeOrWebsite)
    {
        $scope = ScopeInterface::SCOPE_STORE;
        if ($_storeOrWebsite == 'websites') {
            $scope = ScopeInterface::SCOPE_WEBSITE;
        }
        $configValue = $this->scopeConfig->getValue(self::XML_PATH_AVISVERIFIES . self::XML_PATH_AVISVERIFIES_PLATFORM . $code,
            $scope, $_id);
        return $configValue;
    }
}
