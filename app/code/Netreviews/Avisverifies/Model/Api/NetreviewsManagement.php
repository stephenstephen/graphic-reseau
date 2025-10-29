<?php
/**
 * Created by PhpStorm.
 * User: Djaber
 * Date: 04/02/2019
 * Time: 10:49
 */
namespace Netreviews\Avisverifies\Model\Api;

use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Module\ModuleListInterface;
use Netreviews\Avisverifies\Api\NetreviewsManagementInterface;
use Netreviews\Avisverifies\Helper\API;
use Netreviews\Avisverifies\Helper\Data;

class NetreviewsManagement implements NetreviewsManagementInterface
{
    protected $scopeConfig;
    protected $helperApi;
    protected $helperData;
    protected $cacheTypeList;
    protected $productMetadata;
    protected $moduleList;
    protected $resourceConfig;
    protected $resource;
    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection;

    /**
     * display mode
     */
    const XML_PATH_ENVIRONMENT_DISPLAY_MODE = 'av_configuration/system_integration/display_mode';

    const XML_PATH_URL_SANDBOX = 'av_configuration/system_integration/url_sandbox';

    /**
     * NetreviewsManagement constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param API $helperApi
     * @param Data $helperDATA
     * @param TypeListInterface $cacheTypeList
     * @param ProductMetadataInterface $productMetadata
     * @param ModuleListInterface $moduleList
     * @param Config $resourceConfig
     * @param ResourceConnection $resource
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        API $helperApi,
        Data $helperDATA,
        TypeListInterface $cacheTypeList,
        ProductMetadataInterface $productMetadata,
        ModuleListInterface $moduleList,
        Config $resourceConfig,
        ResourceConnection $resource
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->cacheTypeList = $cacheTypeList;
        $this->helperApi = $helperApi;
        $this->helperData = $helperDATA;
        $this->productMetadata = $productMetadata;
        $this->moduleList = $moduleList;
        $this->resourceConfig = $resourceConfig;
        $this->resource = $resource;
        $this->connection = $resource->getConnection();
    }

    /**
     * @param string $query
     * @param string $message
     * @return string
     */
    public function execute($query, $message)
    {
        try {
            $this->helperApi->construct($message);
        } catch (\Exception $e) {
             $toReply['helperApi_construct'] = $e->getMessage();
             $valueToPrint = "#netreviews-start#".$this->helperApi->acEncodeBase64(json_encode($toReply))."#netreviews-end#";
            return $valueToPrint;
        }
        // Check for post
        if ($query && $message) {
            // setup magento helperDATA
            $this->helperData->setup(array(
                'idWebsite' => $this->helperApi->msg('idWebsite'),
                'query' => $this->helperApi->msg('query'),
            ));
            // Check security data
            $errorArray = $this->checkSecurityData();
            if (count($errorArray) > 0) {
                $valueToPrint = "#netreviews-start#".$this->helperApi->acEncodeBase64(json_encode($errorArray))."#netreviews-end#";
                return $valueToPrint;
            }

            /* ############ DEBUT DU TRAITEMENT ############*/
            // Switch case on query type.
            switch ($query) {
                case 'isActiveModule':
                    $toReply = $this->isActiveModule();
                    break;
                case 'setModuleConfiguration' :
                    $toReply = $this->setModuleConfiguration();
                    $this->cleanCache();
                    break;
                case 'getModuleAndSiteConfiguration' :
                    $toReply = $this->getModuleAndSiteConfiguration();
                    $this->cleanCache();
                    break;
                case 'getOrders' :
                    $toReply = $this->getOrders();
                    break;
                case 'truncateTables':
                    $toReply = $this->deleteModuleAndSiteConfiguration();
                    $this->cleanCache();
                    break;
                case 'deleteModuleAndSiteConfiguration':
                    $toReply = $this->deleteModuleAndSiteConfiguration();
                    $this->cleanCache();
                    break;
                case 'cleanCache' :
                    $toReply = $this->cleanCache();
                    break;
                case 'setFlag' :
                    $toReply = $this->setFlag();
                    break;
                default:
                    $toReply['debug'] = "Query inconnu";
                    $toReply['return'] = 2;
                    $toReply['query'] = "";
                    break;
            }
            // Affichage du retour des fonctions pour récupération du résultat par AvisVerifies
            $valueToPrint = "#netreviews-start#".$this->helperApi->acEncodeBase64(json_encode($toReply))."#netreviews-end#";
        } else {
            $reponse['debug'] = "Aucun variable POST";
            $reponse['return'] = 2;
            $reponse['query'] = "";
            $valueToPrint = "#netreviews-start#".$this->helperApi->acEncodeBase64(json_encode($reponse))."#netreviews-end#";
        }
        return $valueToPrint;
    }

    /**
     * delete config
     * @return mixed
     */
    protected function deleteModuleAndSiteConfiguration()
    {
        try {
            $coreConfigData = $this->resource->getTableName('core_config_data');
            $this->connection->delete($coreConfigData, [$this->connection->quoteInto('path LIKE ?', 'av_configuration%')]);
            $this->connection->delete($coreConfigData, [$this->connection->quoteInto('path LIKE ?', 'av_pla%')]);
            $this->connection->delete($coreConfigData, [$this->connection->quoteInto('path LIKE ?', '%avisverifie%')]);
            $this->connection->delete($coreConfigData, [$this->connection->quoteInto('path LIKE ?', '%netreview%')]);
            $this->connection->delete($coreConfigData, [$this->connection->quoteInto('path LIKE ?', '%verifiedreview%')]);
            $reponse['queryExecuted1'] = 'delete path LIKE av_configuration%';
            $reponse['queryExecuted2'] = 'delete path LIKE av_pla%';
            $reponse['queryExecuted3'] = 'delete path LIKE %avisverifie%';
            $reponse['queryExecuted4'] = 'delete path LIKE %netreview%';
            $reponse['queryExecuted5'] = 'delete path LIKE %verifiedreview%';
            $reponse['message'] = "La configuration du site a été supprimée.";
            $reponse['debug'] = "La configuration du site a été supprimée.";
            $reponse['return'] =  1 ;// 1:success, 2:error.
            $reponse['query'] = $this->helperApi->msg('query');
            return $reponse;
        } catch (\Exception $e) {
            $reponse['return'] =  2 ;
            $reponse['debug'] = $e->getMessage();
            return $reponse;
        }
    }

    /**
     * flag commandes
     * @return string
     */
    protected function setFlag()
    {
        try {
            $startDateApi = strtotime($this->helperApi->msg('startDate'));
            $endDateApi = strtotime($this->helperApi->msg('endDate'). '+1 days');
            $setFlag = $this->helperApi->msg('setFlag');
            $typePeriod = $this->helperApi->msg('datePeriod');//We firstly check that any data is missing.
            if ($setFlag == "" || $typePeriod == "") {
                $reponse['querySent'] = $this->helperApi;
                $reponse['return'] = "Erreur, s'il vous plait vérifiez les données introduites";
                return $reponse;
            }//Double check that option fits as it should ( Period and All options) and we send proper data to the pertinent function.
            $startDate = date('Y-m-d H:i:s', $startDateApi);
            $endDate = date('Y-m-d H:i:s', $endDateApi);
            $salesOrder = $this->resource->getTableName('sales_order');
            if ($typePeriod === 'periodOrders' && $startDateApi != '' && $endDateApi != '') {
                if ($this->helperApi->msg('startDate') > $this->helperApi->msg('endDate')) {
                    $reponse['querySent'] = $this->helperApi;
                    $reponse['return'] = 'Erreur, startDate est plus grand que endDate';
                    return $reponse;
                }
                if ($setFlag == 1) {
                    $resutlSelect = $this->connection->fetchRow("SELECT count(entity_id) as totalOrders FROM $salesOrder WHERE (created_at BETWEEN '$startDate' AND '$endDate') AND av_flag = 0 AND av_horodate_get IS NULL");
                    $message['totalOrders'] = $resutlSelect['totalOrders'];
                    $timestamp = date('Y-m-d H:i:s');
                    $sqlOrderPriodeTo1 = "Update $salesOrder Set av_flag = null, av_horodate_get = '$timestamp' WHERE (created_at BETWEEN '$startDate' AND '$endDate') AND av_flag = 0 AND av_horodate_get IS NULL";
                    $this->connection->query($sqlOrderPriodeTo1);
                    $reponse['message'] = $message;
                    $reponse['querySent'] = $this->helperApi;
                    $reponse['return'] = 'Commandes setFlag 1 avec periodOrders';
                    return $reponse;
                }
                if ($setFlag == 0) {
                    $resutlSelect = $this->connection->fetchRow("SELECT count(entity_id) as totalOrders FROM $salesOrder WHERE (created_at BETWEEN '$startDate' AND '$endDate') AND av_flag IS NULL AND av_horodate_get IS NOT NULL");
                    $message['totalOrders'] = $resutlSelect['totalOrders'];
                    $sqlOrderPriodeTo0 = "Update $salesOrder Set av_flag = 0, av_horodate_get = NULL WHERE (created_at BETWEEN '$startDate' AND '$endDate') AND av_flag IS NULL AND av_horodate_get IS NOT NULL";
                    $this->connection->query($sqlOrderPriodeTo0);
                    $reponse['message'] = $message;
                    $reponse['querySent'] = $this->helperApi;
                    $reponse['return'] = 'Commandes setFlag 0 avec periodOrders';
                    return $reponse;
                }
            }elseif ($typePeriod == "allOrders") {
                if ($setFlag == 1) {

                    $resutlSelect = $this->connection->fetchRow("SELECT count(entity_id) as totalOrders FROM $salesOrder WHERE av_flag = 0 AND av_horodate_get IS NULL");
                    $message['totalOrders'] = $resutlSelect['totalOrders'];
                    $timestamp = date('Y-m-d H:i:s');
                    $sqlOrderPriodeTo1 = "Update $salesOrder Set av_flag = null, av_horodate_get = '$timestamp' WHERE av_flag = 0 AND av_horodate_get IS NULL";
                    $this->connection->query($sqlOrderPriodeTo1);
                    $reponse['message'] = $message;
                    $reponse['querySent'] = $this->helperApi;
                    $reponse['return'] = "Toutes les commandes setFlag à 1";
                    return $reponse;
                }
                if ($setFlag == 0) {
                    $resutlSelect = $this->connection->fetchRow("SELECT count(entity_id) as totalOrders FROM $salesOrder WHERE av_flag IS NULL AND av_horodate_get IS NOT NULL");
                    $message['totalOrders'] = $resutlSelect['totalOrders'];
                    $sqlOrderPriodeTo0 = "Update $salesOrder Set av_flag = 0, av_horodate_get = NULL WHERE av_flag IS NULL AND av_horodate_get IS NOT NULL";
                    $this->connection->query($sqlOrderPriodeTo0);
                    $reponse['message'] = $message;
                    $reponse['querySent'] = $this->helperApi;
                    $reponse['return'] = 'Toutes les commandes setFlag à 0';
                    return $reponse;
                }
            } else {
                $reponse['querySent'] = $this->helperApi;
                $reponse['return'] = 'Erreur, dans les données : vérifiez Periode et date';
                $reponse['message'] = $message;
                return $reponse;
            }
        } catch (\Exception $e) {
            return $reponse['debug'] = $e->getMessage();
        }
    }

    /**
     * @return string
     */
    protected function cleanCache()
    {
        try {
            $type = 'config';
            $this->cacheTypeList->cleanType($type);
            $reponse['message'] = 'Cache cleaned successfully!!';
            $reponse['query'] = 'cleanCache';
            $reponse['return'] = (empty($reponse['message'])) ? 2 : 1;// 2:error, 1:success.
            return $reponse;
        } catch (\Exception $e) {
            return $reponse['debug'] = $e->getMessage();
        }
    }

    /**
     * @return string
     */
    protected function getOrders()
    {
        try {
            $reponse['return'] = 1;
            $reponse['query'] = $this->helperApi->msg('query');
            $idStore = $this->helperData->idStore;

            // Get stores actives for a specific IdWebsite
            $activeStores = $this->helperData->allStoresId;

            // Check if $activeStores have websites and get its stores id (because if we have a website it is active)
            foreach ($activeStores as $val) {
                if ($val['scope'] == 'websites') {
                    $storesInWebsite = $this->helperData->getAllStoresIdsForWebsite($val['scope_id']);
                    $activeStores = array_unique(array_merge($activeStores, $storesInWebsite), SORT_REGULAR); // SORT_REGULAR - compare les éléments normalement (ne modifie pas les types).
                }
            }
            $i = 0;
            $is_cron = $this->helperApi->msg('no_flag');
            $listOrders = array();
            // Get orders for each Store
            foreach ($activeStores as $currentStore) {
                // Jump this iteration if it is default configuration or if it is a website in order to avoid duplicate values because we'll process each store for this website.
                if ($currentStore['scope'] == 'websites' || $currentStore['scope'] == 'default') {
                    continue;
                }

                $current_idStore = $currentStore['scope_id'];

                // Platform configuration
                $processinit = $this->helperData->getSpecificPlatformConfig("processinit", $current_idStore, $this->helperData->defaultOrStoreOrWebsite);
                $status = null;

                if ($processinit == null) {
                    $reponse['return'] = 0;
                    $reponse['message'] = "Aucun évènement processinit n'a été renseigné pour la récupération des commandes."; //test con $a_row
                    return $reponse;
                } elseif ($processinit == 'onorderstatuschange') {
                    $reponse['debug']['mode'] = $processinit;
                    $status = $this->helperData->getSpecificPlatformConfig("orderstateschoosen", $current_idStore, $this->helperData->defaultOrStoreOrWebsite);
                    if ($status == null) {
                        $reponse['return'] = 0;
                        $reponse['message'] = "Aucun évènement onorderstatuschange n'a été renseigné.";
                        return $reponse;
                    } else {
                        $reponse['debug']['status'] = $status;
                    }
                } elseif ($processinit == 'onorder') {
                    $reponse['debug']['mode'] = $processinit;
                }

                $getProductReviews = $this->helperData->getSpecificPlatformConfig("getprodreviews", $current_idStore, $this->helperData->defaultOrStoreOrWebsite);
                $delay = $this->helperData->getSpecificPlatformConfig("delay", $current_idStore, $this->helperData->defaultOrStoreOrWebsite);
                $reponse['getProductReviews'] = $getProductReviews;
                $forbiddenEmails = $this->helperData->getSpecificPlatformConfig("forbidden_email", $current_idStore, $this->helperData->defaultOrStoreOrWebsite);

                $o_orders = $this->helperData->getOrdersAddingStatusFilter($currentStore['scope_id'], $status);
                // Si hay e-mails prohibidos agregar el filtro para eliminar los pedidos con estos e-mails.
                if ($forbiddenEmails) {
                    $reponse['debug']['Forbidden_Emails'] = $forbiddenEmails;
                    $o_orders = $this->helperData->addFilterForbiddenEmails($o_orders, $forbiddenEmails);
                }

                $is_offline = $this->helperData->getAdvancedConfig("offline_store", $currentStore['scope_id']);
                $canal = $is_offline == 1 ? "offline" : "online";
                foreach ($o_orders as $_order) {
                    $listOrders[$i]['canal'] = $canal;
                    $listOrders[$i]['id_pointshop'] = $_order->getStoreId();
                    $listOrders[$i]['name_shop'] = $_order->getStoreName();
                    $listOrders[$i]['id_order'] = $_order->getIncrementId();
                    $timestamp = strtotime($_order->getCreatedAt());
                    $listOrders[$i]['amount_order'] = $_order->getBaseGrandTotal(). ' ' . $_order->getBaseCurrencyCode();
                    $listOrders[$i]['date_order'] = $timestamp;
                    $listOrders[$i]['date_order_formatted'] = date('Y-m-d H:i:s', $timestamp);
                    $listOrders[$i]['is_flag'] = $_order->getAvFlag();
                    $listOrders[$i]['date_av_getted_order'] = $_order->getAvHorodateGet();
                    $listOrders[$i]['state_order'] = $_order->getStatus();
                    if (empty($_order->getCustomerFirstname()) || empty($_order->getCustomerLastname())) {
                        $billingAddress = $_order->getBillingAddress();
                        $listOrders[$i]['firstname_customer'] = $billingAddress->getFirstname();
                        $listOrders[$i]['lastname_customer'] = $billingAddress->getLastname();
                    } else {
                        $listOrders[$i]['firstname_customer'] = $_order->getCustomerFirstname();
                        $listOrders[$i]['lastname_customer'] = $_order->getCustomerLastname();
                    }
                    $listOrders[$i]['email_customer'] = $_order->getCustomerEmail();
                    // =============== WITH PRODUCTS ===============
                    if ($getProductReviews == 'yes')
                    {
                        $listOrders[$i]['products'] = array();
                        $listProducts = $this->helperData->getProducts($_order);
                        foreach ($listProducts as $key => $product) {
                            $listOrders[$i]['products'][$key] = $product;
                        }
                    }
                    // =============== WITH PRODUCTS END ===============
                    // =============== FLAG ORDER TO 1 IF CALL COME FROM CRON ===============
                    if ($is_cron == 0) {
                        if ($this->helperData->flagOrderTo1($_order) != 1) {
                            unset($listOrders[$i]); // Delete current order because is not flag to 1.
                            continue;
                        }
                    }
                    // =============== FLAG ORDER TO 1 IF CALL COME FROM CRON END ===============

                    $i++;
                } // endForeach $o_orders
            } // foreach store_id
            $arrayOrders['nb_orders'] = $i;
            $arrayOrders['delay'] = $delay;
            // Platform configuration
            $reponse['debug']['force'] = $this->helperApi->msg('force');
            $reponse['debug']['produit'] = $getProductReviews;
            $reponse['debug']['no_flag'] = $is_cron;
            $reponse['debug']['get_orders_from_stores'] = $activeStores;
            // Advanced configuration
            $reponse['debug']['get_id_or_sku'] = $this->helperData->getPlatformConfig("get_id_or_sku", $idStore);
            $reponse['debug']['Use_parent_sku'] = $this->helperData->getAdvancedConfig('use_parent_sku', $idStore);
            $arrayOrders['list_orders'] = $listOrders;
            $reponse['message'] = $arrayOrders;
            return $reponse;
        } catch (\Exception $e) {
            return $reponse['debug'] = $e->getMessage();
        }
    }

    /**
     * récupère la config
     * @return string
     */
    protected function getModuleAndSiteConfiguration()
    {
        try {
            $reponse['message'] = $this->_getModuleAndSiteInfos();
            $reponse['query'] = $this->helperApi->msg('query');
            $reponse['return'] = (empty($reponse['message'])) ? 2 : 1;// 2:error, 1:success.
            return $reponse;
        } catch (\Exception $e) {
            return $repons0e['debug'] = $e->getMessage();
        }
    }

    /**
     * get info module
     * @return array|string
     */
    protected function _getModuleAndSiteInfos()
    {
        try {
            $magentoVersion = $this->productMetadata->getVersion();// Will return the magento version
            $moduleVersion = $this->moduleList->getOne('Netreviews_Avisverifies')['setup_version'];
            $websites_mapping = $this->helperData->getAllWebsites();
            $stores_mapping = $this->helperData->getAllStores();
            $arrayStatusCollectionSimplified = $this->helperData->getAllStatus();
            $siteMapping = $this->helperData->getStoresWithIdwebsite($websites_mapping, $stores_mapping);
            $idStore = $this->helperData->idStore;
            $configurationData = array(
                // Magento configuration
                'Version_PS' => $magentoVersion,
                'Version_Module' => $moduleVersion,
                'idWebsite' => $this->helperData->idwebsite,
                'Id_Website_encours' => $idStore . ' [' . $this->helperData->defaultOrStoreOrWebsite . ']',
                'Enabled_Website' => $this->helperData->getMainConfig("enabledwebsite", $idStore),

                // Platform configuration
                'Initialisation_du_Processus' => $this->helperData->getPlatformConfig("processinit", $idStore),
                'Statut_choisi' => $this->helperData->getPlatformConfig("orderstateschoosen", $idStore),
                'Requirejs' => $this->helperData->getPlatformConfig("requirejs", $idStore),
                'Reviews_amazon_url' => $this->helperData->getPlatformConfig("reviews_amazon_url", $idStore),
                'Certificat_url' => $this->helperData->getPlatformConfig("url_certificat", $idStore),
                'Helpful_reviews_url' => $this->helperData->getPlatformConfig("url_helpful_reviews"),
                'Collect_consent' => $this->helperData->getPlatformConfig("collect_consent", $idStore),
                'Delay' => $this->helperData->getPlatformConfig("delay", $idStore),
                'Emails_Interdits' => $this->helperData->getPlatformConfig("forbidden_email", $idStore),
                'Affichage_Widget_Flottant' => $this->helperData->getPlatformConfig("scriptfloat_allowed", $idStore),
                'Script_Widget_Flottant' => $this->helperData->getPlatformConfig("scriptfloat", $idStore),
                'enable_media' => $this->helperData->getPlatformConfig("enable_media", $idStore),
                'enable_helpful_reviews' => $this->helperData->getPlatformConfig("enable_helpful_reviews", $idStore),
                'Recuperation_Avis_Produits' => $this->helperData->getPlatformConfig("getprodreviews", $idStore),
                'Affiche_Avis_Produits' => $this->helperData->getPlatformConfig("displayprodreviews", $idStore),
                'get_id_or_sku' => $this->helperData->getPlatformConfig("get_id_or_sku", $idStore),

                // Advanced configuration
                'BO_Affiche_Avis_Produits' => $this->helperData->getAdvancedConfig("add_reviews_to_product_page", $idStore),
                'BO_reduce_product_reviews' => $this->helperData->getAdvancedConfig("reduce_product_reviews", $idStore),
                'BO_activate_rich_snippets' => $this->helperData->getAdvancedConfig("activate_rich_snippets", $idStore),
                'BO_rich_snippets_already_exist' => $this->helperData->getAdvancedConfig("rich_snippets_already_exist", $idStore),
                'BO_offline_store' => $this->helperData->getAdvancedConfig("offline_store", $idStore),
                'BO_use_Parent_sku&PLA' => $this->helperData->getAdvancedConfig("use_parent_sku", $idStore),
                'Map_Configuration' => $siteMapping, // use $websites_mapping/$stores_mapping to get all mapping even if websites/stores are not configured for the plugin.
                'Liste_des_statuts' => $arrayStatusCollectionSimplified,
                'Date_Recuperation_Config' => date('Y-m-d H:i:s')
            );
            return $configurationData;
        } catch (\Exception $e) {
            return $reponse['debug'] = $e->getMessage();
        }
    }

    /**
     * Verifica si la firma es la misma que en nuestro sitio OV.
     * @return array
     */
    protected function checkSecurityData()
    {
        try {
            $sign = $this->helperApi->msg('sign');
            $errorArray = array();// Si il n'y a pas de sign dans l'appel POST

            if (empty($sign)) {
                $errorArray['debug'] = "Prameter missing in the request.";
                $errorArray['message'] = "There is not a signature.";
                $errorArray['return'] = 2;
                $errorArray['query'] = 'checkSecurityData';
            } elseif (empty($this->helperData->idwebsite) || empty($this->helperData->secretkey)) { // Si l'idWebsite ou la secretKey est vide
                $errorArray['debug'] = "The keys field are empties.";
                $errorArray['message'] = "The keys field are empties.";
                $errorArray['return'] = 3;
                $errorArray['query'] = 'checkSecurityData';
            } elseif ($this->helperData->SHA1 !== $sign) { // Si le parametre sign ne correpsond pas à ce qu'on a calculé
                $errorArray['message'] = "The signatures do not match.";
                $errorArray['debug'] = "The signatures do not match.";
                $errorArray['return'] = 5;
                $errorArray['query'] = 'checkSecurityData';
            }

            // Si il y a une erreur
            return $errorArray;
        } catch (\Exception $e) {
            $reponse['debug'] = $e->getMessage();
            return $reponse;
        }
    }

    /**
     * @return mixed
     */
    protected function isActiveModule()
    {
        try{
            if($this->isModuleEnabled()){
                $returnApi['debug'] = __('Module installed and activated');
                $returnApi['return'] = 1;
                $returnApi['query'] = 'isActiveModule';
            } else {
                $returnApi['debug'] = __('Module not installed or not activated');
                $returnApi['return'] = 2;
                $returnApi['query'] = 'isActiveModule';
            }
            return $returnApi;
        } catch (\Exception $e) {
            $returnApi['debug'] = __($e->getMessage());
            $returnApi['return'] = 2;
            $returnApi['query'] = 'isActiveModule';
            return $returnApi;
        }
    }

    /**
     * enregistre configuration par défaut
     * @return string
     */
    protected function setModuleConfiguration()
    {
        try {
            $activeStores = $this->helperData->allStoresId;
            $platformFields = array(
                'processinit',
                'orderstateschoosen',
                'delay',
                'forbidden_email',
                'getprodreviews',
                'displayprodreviews',
                'scriptfloat_allowed',
                'scriptfloat',
                'urlcertificat',
                'requirejs',
                'reviews_amazon_url',
                'url_helpful_reviews',
                'enable_helpful_reviews',
                'enable_media',
                'get_id_or_sku',
                'collect_consent'
            );
            $postFields = array(
                'init_reviews_process',
                'id_order_status_choosen',
                'delay',
                'forbidden_mail_extension',
                'get_product_reviews',
                'display_product_reviews',
                'display_float_widget',
                'script_float_widget',
                'url_certificat',
                'requirejs',
                'reviews_amazon_url',
                'url_helpful_reviews',
                'enable_helpful_reviews',
                'enable_media',
                'get_id_or_sku',
                'collect_consent'
            );
            $basePath = 'av_configuration/plateforme/';
            foreach ($activeStores as $k => $store) {
                foreach ($platformFields as $i => $platformField) {
                    if ($postFields[$i] == 'delay') {
                        $delay = $this->helperApi->msg($postFields[$i]);
                        $delay = ($delay == '' || $delay == null || empty($delay) || !$delay) ? 0 : $delay;
                        //value core_config_data
                        $path = $basePath . $platformField;
                        $value = $delay;
                        $scope = $store['scope'];
                        $scope_id = $store['scope_id'];
                        $this->resourceConfig->saveConfig(
                            $path,
                            $value,
                            $scope,
                            $scope_id
                        );
                    } else if ($postFields[$i] == 'id_order_status_choosen') {
                        $id_order_status_choosen = (is_array($this->helperApi->msg($postFields[$i]))) ? implode(';', $this->helperApi->msg($postFields[$i])) : $this->helperApi->msg($postFields[$i]);
                        //value core_config_data
                        $path = $basePath . $platformField;
                        $value = $id_order_status_choosen;
                        $scope = $store['scope'];
                        $scope_id = $store['scope_id'];
                        $this->resourceConfig->saveConfig(
                            $path,
                            $value,
                            $scope,
                            $scope_id
                        );
                    } else {
                        $path = $basePath . $platformField;
                        $value = $this->helperApi->msg($postFields[$i]);
                        $scope = $store['scope'];
                        $scope_id = $store['scope_id'];
                        $this->resourceConfig->saveConfig(
                            $path,
                            $value,
                            $scope,
                            $scope_id
                        );
                    }
                }
            }
            $reponse['configuredStores'] = $activeStores;
            $reponse['message'] = $this->_getModuleAndSiteInfos();
            $reponse['debug'] = "La configuration du site a été mise à jour";
            $reponse['return'] = 1;//A definir
            $reponse['query'] = $this->helperApi->msg('query');
            return $reponse;
        } catch (\Exception $e) {
            return $reponse['debug'] = $e->getMessage();
        }
    }

    /**
     * @return mixed
     */
    private function isModuleEnabled()
    {
        return $this->scopeConfig->getValue('av_configuration/system_integration/enabledwebsite', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

}
