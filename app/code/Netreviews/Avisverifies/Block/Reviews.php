<?php
namespace Netreviews\Avisverifies\Block;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use Netreviews\Avisverifies\Helper\ReviewsAPI;

class Reviews extends Template
{
    public $productRef;
    public $helperReviewsAPI;
    public $nbReviews = 0;
    public $rate;
    public $reviewsPerPage = 10;
    public $ajaxUrl;
    public $nbReviewsByRate = array();
    public $logoNetreviews;
    public $urlcertificat;
    public $idWebsite;
    public $secretKey;
    public $isEnableHelpfulReviews;
    public $reviewsList = array();
    public $urlHelpfulReviews;
    public $storeUrl;
    protected $storeId = '';
    protected $storeManager;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Reviews constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ReviewsAPI $helperReviewsAPI
     */
    public function __construct(
        Context $context,
        ReviewsAPI $helperReviewsAPI,
        Registry $registry
    ) {
        $this->helperReviewsAPI = $helperReviewsAPI;
        $this->coreRegistry = $registry;
        $this->storeManager = $context->getStoreManager();
        $this->scopeConfig = $context->getScopeConfig();
        parent::__construct($context);
        $this->setStoreId();
        $this->productRef = $this->helperReviewsAPI->getProductRef($this->storeId);
        if ($this->productRef != null && $this->helperReviewsAPI->isShowNetreviews($this->storeId) && $this->helperReviewsAPI->isCacheProductList($this->productRef)) {
            $this->setReviewListAndStat();
            if (!empty($this->nbReviewsByRate) && !empty($this->reviewsList)) {
                $this->setAverageReviews();
                $this->setLogoNetreviews();
                $this->setUrlcertificat();
                $this->setUrlHelpfulReviews();
                $this->setStoreUrl();
                $this->setEnableHelpfulReviews();
            $this->ajaxUrl = $this->getUrl('AvisVerifies/Index/') . "AjaxReviews";
            $this->idWebsite = $this->scopeConfig->getValue(
                'av_configuration/system_integration/idwebsite',
                ScopeInterface::SCOPE_STORE,
                $this->storeId
            );
            $this->secretKey = $this->scopeConfig->getValue(
                'av_configuration/system_integration/secretkey',
                ScopeInterface::SCOPE_STORE,
                $this->storeId
            );
            }
        } else {
            $this->nbReviews = 0;
        }
    }

    private function setStoreId()
    {
        $this->storeId = $this->storeManager->getStore()->getId();
    }

    public function setReviewListAndStat()
    {
        $reviewListAndStat = $this->helperReviewsAPI->getCacheReviewsStats($this->productRef, 0, $this->reviewsPerPage, 'newest', $this->idWebsite);
        if (is_array($reviewListAndStat) && !empty($reviewListAndStat)) {
            $this->nbReviewsByRate = $reviewListAndStat[0]->stats;
            $this->reviewsList = $reviewListAndStat[0]->reviews;
        }
    }

    private function setAverageReviews()
    {
        $averageReviews = $this->helperReviewsAPI->getRateAndNbReviews($this->nbReviewsByRate);
        $this->nbReviews = $averageReviews['nb_reviews'];
        $this->rate = isset($averageReviews['rate']) ? $averageReviews['rate'] : 0;
    }

    private function setLogoNetreviews()
    {
        try {
            $logoNetreviews = utf8_encode(__("logo_full_en"));
            $logoNetreviews = preg_replace('/[\x80-\xFF]/', '', $logoNetreviews);
            $this->logoNetreviews = $this->getViewFileUrl('Netreviews_Avisverifies::images/' . $logoNetreviews . '.png');
        } catch (\Exception $ex) {
            $this->logoNetreviews = "logo_full_en";
        }
    }

    private function setUrlcertificat()
    {
        $this->urlcertificat = $this->scopeConfig->getValue(
            'av_configuration/plateforme/urlcertificat',
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }

    private function setUrlHelpfulReviews()
    {
        $this->urlHelpfulReviews = $this->scopeConfig->getValue(
            'av_configuration/plateforme/url_helpful_reviews',
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }

    private function setEnableHelpfulReviews()
    {
        $this->isEnableHelpfulReviews = $this->scopeConfig->getValue(
            'av_configuration/plateforme/enable_media',
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }

    private function setStoreUrl()
    {
        $a_store_url = explode("/", $this->storeManager->getStore()->getBaseUrl());
        $this->storeUrl = str_replace("www.", "", $a_store_url[2]);
    }

    public function getReduceProductReviews()
    {
        return $this->scopeConfig->getValue(
            'av_configuration/advanced_configuration/reduce_product_reviews',
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }
}
