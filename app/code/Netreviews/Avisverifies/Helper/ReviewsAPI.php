<?php
namespace Netreviews\Avisverifies\Helper;

use Exception;
use Magento\Framework\App\Cache;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\StoreManagerInterface;
use Netreviews\Avisverifies\Model\Api\NetreviewsManagement;
use Netreviews\Avisverifies\Helper\Data as NRDATA;

class ReviewsAPI extends AbstractHelper
{
    const CACHE_TAG_NRREVIEWSTATS = 'NRREVIEWSTATS';
    const CACHE_ID_NR_REVIEW_STATS = 'nrreviewstats';
    const CACHE_TAG_NR_RATE_NBREVIEWS = 'NRRATENBREVIEWS';
    const CACHE_LIFETIME = 21600; // 6 hours
    const URL_API = 'https://awsapis3.netreviews.eu/product';
    public $amazonUrl = '';
    protected $plateforme;
    protected $idWebsite;
    protected $logger;
    protected $storeManager;
    protected $cache;
    protected $coreRegistry;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ReviewsAPI constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param Cache $cache
     * @param Registry $registry
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Cache $cache,
        Registry $registry,
        Json $serializer
    ) {
        $this->serializer = $serializer;
        $this->logger = $context->getLogger();
        $this->storeManager = $storeManager;
        $this->scopeConfig = $context->getScopeConfig();
        $this->coreRegistry = $registry;
        $this->amazonUrl = $this->scopeConfig->getValue('av_configuration/plateforme/reviews_amazon_url',
            ScopeInterface::SCOPE_STORE, $this->getStoreId());
        $this->idWebsite = $this->scopeConfig->getValue('av_configuration/system_integration/idwebsite',
            ScopeInterface::SCOPE_STORE, $this->getStoreId());
        $this->plateforme = $this->getPlateforme();
        $this->cache = $cache;
        parent::__construct($context);
    }

    public function getStoreId()
    {
        try {
            return $this->storeManager->getStore()->getId();
        } catch (NoSuchEntityException $e) {
            return 0;
        }
    }

    /**
     * @return bool|string
     */
    public function getPlateforme()
    {
        $debutString='fr';
        $explodeUrl = explode('/cache/', $this->amazonUrl);
        if (count($explodeUrl) > 0) {
            $debutString = $explodeUrl[0];
        }
        return substr($debutString, -2);
    }

    /**
     * Return review of list product
     * @return array|mixed
     */
    public function getAllAverage()
    {
        $loadCacheNr = $this->loadCache(self::CACHE_ID_NR_REVIEW_STATS);
        if (!$loadCacheNr) {
            $dataApi = [
                'query' => 'average',
                'idWebsite' => $this->idWebsite,
                'product' => 'all_reviews',
                'plateforme' => $this->plateforme
            ];
            $dataProductsAverage = $this->callApi($dataApi);
            $this->saveCache(json_decode(json_encode($dataProductsAverage)), self::CACHE_ID_NR_REVIEW_STATS,
                self::CACHE_TAG_NR_RATE_NBREVIEWS);
        } else {
            $dataProductsAverage = json_decode(json_encode($loadCacheNr));
        }
        return $dataProductsAverage;
    }

    /**
     * @param $cacheId
     * @return mixed
     */
    public function loadCache($cacheId)
    {
        $data = $this->cache->load($this->idWebsite . "_" . $cacheId);
        if (false !== $data) {
            $data = $this->serializer->unserialize($data);
        }
        return $data;
    }

    /**
     * appel api
     * @param $data
     * @return array|mixed
     */
    public function callApi($data)
    {
        $url = self::URL_API;
        $isErrorr = false;
        try {
            //add url sandbox if environment sandbox
            if ($this->isEnvironmentSandbox()) {
                $data['sandbox_url'] = $this->scopeConfig->getValue(NetreviewsManagement::XML_PATH_URL_SANDBOX,
                    ScopeInterface::SCOPE_STORE, $this->getStoreId());
            }
            $curl = curl_init($url);
            $data_string = json_encode($data);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            ));
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT, 5);//timeout in seconds
            $response = curl_exec($curl);
            $curl_errno = curl_errno($curl);
            $curl_error = curl_error($curl);
            if ($curl_errno > 0) {
                $this->_logger->critical('api netreviews unavailable');
                $this->_logger->critical('cURL code error: ' . $curl_error);
                $isErrorr = true;
            }
            $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($responseCode != 200) {
                $this->_logger->critical('api netreviews unavailable');
                $this->_logger->critical('cURL responseCode: ' . $responseCode);
                $isErrorr = true;
            }
            if (!$isErrorr) {
                $data = json_decode($response);
            } else {
                $data = array();
            }
            // Gérer les erreurs du retour api
            if (isset($data->errorMessage)) {
                $this->logger->critical($data->errorMessage);
                $data = array();
            }
            curl_close($curl);
            return $data;
        } catch (Exception $e) {
            $this->logger->error('api netreviews unavailable exception');
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @return bool
     */
    protected function isEnvironmentSandbox()
    {
        $displayMode = $this->scopeConfig->getValue(NetreviewsManagement::XML_PATH_ENVIRONMENT_DISPLAY_MODE,
            ScopeInterface::SCOPE_STORE, $this->getStoreId());
        return $displayMode === 'sandbox';
    }

    /**
     * @param $data
     * @param $cacheId
     * @param $cacheTag
     * @param int $lifeTime
     * @return mixed
     */
    public function saveCache($data, $cacheId, $cacheTag, $lifeTime = self::CACHE_LIFETIME)
    {
        return $this->cache->save($this->serializer->serialize($data), $this->idWebsite . "_" . $cacheId, array($cacheTag), $lifeTime);
    }

    /**
     * @param array $stats
     * @return array
     */
    public function getRateAndNbReviews($stats)
    {
        $rate = 0;
        $nbReviews = 0;
        for ($i = 0; $i < 5; $i++) {
            $note = $i + 1;
            $rate += $note * $stats[$i];
            $nbReviews += $stats[$i];
        }
        $rate /= $nbReviews;
        $productAverage = array();
        $productAverage['nb_reviews'] = $nbReviews;
        $productAverage['rate'] = round($rate, 1);
        return $productAverage;
    }

    /**
     * @param $productRef
     * @param int $pageNumber
     * @param int $reviewsPerPage
     * @param string $reviewsFilter
     * @param string $idWebsite
     * @param string $avisVerifiesRateFilter
     * @return array|mixed
     */
    public function getCacheReviewsStats(
        $productRef,
        $pageNumber = 0,
        $reviewsPerPage = 10,
        $reviewsFilter = 'newest',
        $idWebsite = '',
        $avisVerifiesRateFilter = ''
    ) {
        $avisVerifiesRateFilter = is_array($avisVerifiesRateFilter) ? $avisVerifiesRateFilter[0] : $avisVerifiesRateFilter;
        $loadCacheNr = $this->loadCache(self::CACHE_ID_NR_REVIEW_STATS . $productRef . '_' . $pageNumber . '_' . $reviewsFilter . '_' . $avisVerifiesRateFilter);
        if (!$loadCacheNr) {
            $reviewsStats = $this->getProductReviews($productRef, $pageNumber, $reviewsPerPage, $reviewsFilter,
                $idWebsite, $avisVerifiesRateFilter);
            $this->saveCache(json_decode(json_encode($reviewsStats)),
                self::CACHE_ID_NR_REVIEW_STATS . $productRef . '_' . $pageNumber . '_' . $reviewsFilter . '_' . $avisVerifiesRateFilter,
                self::CACHE_TAG_NRREVIEWSTATS);
        } else {
            $reviewsStats = json_decode(json_encode($loadCacheNr));
        }
        return $reviewsStats;
    }

    /**
     * Return the reviews of a specific product
     * @param $productRef
     * @param int $pageNumber
     * @param int $reviewsPerPage
     * @param string $reviewsFilter
     * @param string $idWebsite
     * @param string $avisVerifiesRateFilter
     * @return array|mixed
     */
    public function getProductReviews(
        $productRef,
        $pageNumber = 0,
        $reviewsPerPage = 10,
        $reviewsFilter = 'newest',
        $idWebsite = '',
        $avisVerifiesRateFilter = ''
    ) {
        $productRef = urlencode(urlencode($productRef));
        switch ($reviewsFilter) {
            case 'oldest':
                $file_prefix = 'date_asc';
                break;
            case 'highest':
                $file_prefix = 'rate_desc';
                break;
            case 'lowest':
                $file_prefix = 'rate_asc';
                break;
            case 'most_useful':
                $file_prefix = 'most_helpful';
                break;
            default:
                $file_prefix = 'date_desc';
                break;
        }
        $dataProductAverage = array();
        if (!empty($productRef)) {
            $offset = ($pageNumber * $reviewsPerPage);
            $dataApi = [
                'query' => 'reviews',
                'idWebsite' => (!empty($idWebsite)) ? $idWebsite : $this->idWebsite,
                'order' => $file_prefix,
                'product' => $productRef,
                'offset' => $offset,
                'limit' => $reviewsPerPage,
                'filter' => $avisVerifiesRateFilter,
                'plateforme' => $this->plateforme
            ];
            $dataProductAverage = $this->callApi($dataApi);
        }
        return $dataProductAverage;
    }

    /**
     * @param $productRef
     * @return boolean
     */
    public function isCacheProductList($productRef)
    {
        $loadCachePr = $this->loadCache(self::CACHE_ID_NR_REVIEW_STATS . 'PL');
        if (!$loadCachePr) {
            $productList = $this->getProductList();
            $productList = json_decode(json_encode($productList),true);
            $this->saveCache($productList, self::CACHE_ID_NR_REVIEW_STATS . 'PL',
                self::CACHE_TAG_NRREVIEWSTATS);
        } else {
            $productList = json_decode(json_encode($loadCachePr), true);
        }
        return $this->isRefInCacheProducts($productRef, $productList);
    }

    /**
     * Return the reviews of a specific product
     * @param string $idWebsite
     * @return object
     */
    public function getProductList($idWebsite = '')
    {
        $dataApi = [
            'idWebsite' => (!empty($idWebsite)) ? $idWebsite : $this->idWebsite,
            'query' => 'average',
            'plateforme' => $this->plateforme,
            'products' => 'all'
        ];
        return $this->callApi($dataApi);
    }

    /**
     * verifie si le produit est en cache de notre coté
     * @param $productRef
     * @param $cacheProduct
     * @return bool
     */
    protected function isRefInCacheProducts($productRef, $cacheProduct)
    {
        if (array_key_exists($productRef, $cacheProduct)) {
            return true;
        }
        return false;
    }

    /**
     * @param $productId
     * @param int $pageNumber
     * @param string $reviewsFilter
     * @param string $avisVerifiesRateFilter
     */
    public function deleteCacheByTag(
        $productId,
        $pageNumber = 0,
        $reviewsFilter = 'newest',
        $avisVerifiesRateFilter = ''
    ) {
        $avisVerifiesRateFilter = is_array($avisVerifiesRateFilter) ? $avisVerifiesRateFilter[0] : $avisVerifiesRateFilter;
        $this->deleteCacheById(self::CACHE_ID_NR_REVIEW_STATS . $productId . '_' . $pageNumber . '_' . $reviewsFilter . '_' . $avisVerifiesRateFilter);
    }

    /**
     * @param $cacheId
     * @return mixed
     */
    public function deleteCacheById($cacheId)
    {
        return $this->cache->remove($this->idWebsite . "_" . $cacheId);
    }

    /**
     * Display product stars based on users rating
     * @param $note
     * @return string
     */
    public function ntavAddStars($note)
    {
        $addStars = '<div>';
        for ($i = 1; $i <= 5; $i++) {
            $addStars .= '<span class="nr-icon nr-star grey"></span>';
        }
        $addStars .= '</div>';
        $addStars .= '<div>';
        for ($i = 1; $i <= 5; $i++) {
            if (round($note, 1) > $i) {
                $starWidth = 'width:20%;';
            } else {
                $tempWidth = ((round($note, 1) - ($i - 1)) * 20 < 0 ? '0' : (round($note, 1) - ($i - 1)) * 20);
                $starWidth = 'width:' . $tempWidth . '%;';
            }
            $addStars .= '<span class="nr-icon nr-star gold" style="' . $starWidth . '"></span>';
        }
        $addStars .= '</div>';
        return $addStars;
    }

    /**
     * Decodes a string and returns one (or more) media
     * @param {string} $medias An encoded string containing Json media
     * @return mixed|null
     */
    public function ntavMedias($medias)
    {
        if (empty($medias)) {
            return null;
        } else {
            $json = base64_decode($medias);
            $media = urldecode($json);
            return json_decode($media, true);
        }
    }

    public function getProductRef($storeId, $product = null)
    {
        $productRef = null;
        $idOrSku = $this->getIdOrSku($storeId);
        if ($product === null) {
            $product = $this->coreRegistry->registry('product');
        }
        if (isset($product)) {
            $productRef = $product->getData($idOrSku);
        }
        return $productRef;
    }

    /**
     * définit la reférence produit
     * @param $idStore
     * @return string
     */
    public function getIdOrSku($idStore)
    {
        if ($this->coreRegistry->registry('idOrSku') == null) {
            $idOrSku = $this->scopeConfig->getValue(NRDATA::XML_PATH_AVISVERIFIES . NRDATA::XML_PATH_AVISVERIFIES_PLATFORM . 'get_id_or_sku',
                ScopeInterface::SCOPE_STORE, $idStore);
            if ($idOrSku == null || !in_array($idOrSku, ['sku', 'entity_id'])) {
                $idOrSku = 'sku';
            }
            $this->coreRegistry->register('idOrSku', $idOrSku);
        } else {
            $idOrSku = $this->coreRegistry->registry('idOrSku');
        }
        return $idOrSku;
    }

    /**
     * @return bool
     */
    public function isShowNetreviews($storeId)
    {
        if ($this->coreRegistry->registry('isShowNetreviews') === null) {
            $enabledwebsite = $this->scopeConfig->getValue('av_configuration/system_integration/enabledwebsite',
                ScopeInterface::SCOPE_STORE, $storeId);
            $displayprodreviews = $this->scopeConfig->getValue('av_configuration/plateforme/displayprodreviews',
                ScopeInterface::SCOPE_STORE, $storeId);
            $addReviewsProduct = $this->scopeConfig->getValue('av_configuration/advanced_configuration/add_reviews_to_product_page',
                ScopeInterface::SCOPE_STORE, $storeId);
            if ($enabledwebsite == '1' && $displayprodreviews == 'yes' && $addReviewsProduct == '1') {
                $isShowNetreviews = true;
                $this->coreRegistry->register('isShowNetreviews', true, true);
            } else {
                $this->coreRegistry->register('isShowNetreviews', false, true);
                $isShowNetreviews = false;
            }
        } else {
            $isShowNetreviews = $this->coreRegistry->registry('isShowNetreviews');
        }
        return $isShowNetreviews;
    }

    /**
     * Check rich snippets activate
     * @return string
     */
    public function getRichSnippets($storeId)
    {
        $activateRichSnippets = $this->scopeConfig->getValue(
            'av_configuration/advanced_configuration/activate_rich_snippets',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        return $activateRichSnippets;
    }

    /**
     * Rich snippets already exist
     * @return bool
     */
    public function isRichSnippetsAlreadyExist($storeId)
    {
        $richSnippetsAlreadyExist = $this->scopeConfig->getValue(
            'av_configuration/advanced_configuration/rich_snippets_already_exist',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        return $richSnippetsAlreadyExist;
    }
}
