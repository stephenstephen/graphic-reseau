<?php
namespace Netreviews\Avisverifies\Block\Product;

use Magento\Catalog\Block\Product\ReviewRendererInterface;
use Magento\Catalog\Helper\Product;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use Netreviews\Avisverifies\Helper\Data;
use Netreviews\Avisverifies\Helper\ReviewsAPI;

class ReviewRenderer extends Template implements ReviewRendererInterface
{
    public $productRef;
    public $product;
    public $allAverage = array();
    protected $helperReviewsAPI;
    public $nbReviews;
    public $rateFormated;
    protected $storeId = '';
    public $ntavAddStarsByRate = '';
    protected $averageReviews;
    protected $storeManager;
    protected $availableTemplates = [
        self::DEFAULT_VIEW => 'stars.phtml',
        self::FULL_VIEW => 'stars.phtml',
        self::SHORT_VIEW => 'short_stars.phtml',
    ];
    /**
     * @var Product
     */
    public $productHelper;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * ReviewRenderer constructor.
     * @param Context $context
     * @param ReviewsAPI $helperReviewsAPI
     * @param Http $request
     * @param Product $productHelper
     * @throws NoSuchEntityException
     */
    public function __construct(
        Context $context,
        ReviewsAPI $helperReviewsAPI,
        Http $request,
        Product $productHelper
    ) {
        $this->helperReviewsAPI = $helperReviewsAPI;
        $this->storeManager = $context->getStoreManager();
        $this->scopeConfig = $context->getScopeConfig();
        parent::__construct($context);
        $this->setStoreId();
        $this->_request = $request;
        $this->productHelper = $productHelper;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function setStoreId()
    {
        $this->storeId = $this->_storeManager->getStore()->getId();
    }

    /**
     * get average for product
     *
     * @return array
     */
    public function getAverageProduct()
    {
        $productIdentifier = $this->productRef;
        if (!empty($productIdentifier) && isset($this->allAverage->{$productIdentifier})) {
            foreach ($this->allAverage->{$productIdentifier} as $product) {
                $productAverage['nb_reviews'] = $product->count;
                $productAverage['rate'] = $product->rate;
            }
        }
        return isset($productAverage) ? $productAverage : array('nb_reviews' => 0, 'rate' => 0);
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param string $templateType
     * @param bool $displayIfNoReviews
     * @return string
     */
    public function getReviewsSummaryHtml(
        \Magento\Catalog\Model\Product $product,
        $templateType = self::DEFAULT_VIEW,
        $displayIfNoReviews = false
    ) {
        $this->product = $product;
        if (empty($this->availableTemplates[$templateType])) {
            $templateType = self::DEFAULT_VIEW;
        }
        $this->productRef = $this->helperReviewsAPI->getProductRef($this->storeId, $product);
        if ($this->helperReviewsAPI->isShowNetreviews($this->storeId) && $this->helperReviewsAPI->isCacheProductList($this->productRef) == true) {
            if ($templateType === 'default' && $this->_request->getFullActionName() === 'catalog_product_view') {
                $reviewListAndStat = $this->helperReviewsAPI->getCacheReviewsStats($this->productRef);
                if (is_array($reviewListAndStat) && !empty($reviewListAndStat)) {
                    $this->averageReviews = $this->helperReviewsAPI->getRateAndNbReviews($reviewListAndStat[0]->stats);
                }
            } else {
                if (empty($this->allAverage)) {
                    $this->allAverage = $this->helperReviewsAPI->getAllAverage();
                }
                $this->averageReviews = $this->getAverageProduct();
            }
            $this->ntavAddStarsByRate = $this->getNtavAddStarsByRate();
        } else {
            $this->nbReviews = 0;
        }
        $this->setTemplate($this->availableTemplates[$templateType]);
        return $this->toHtml();
    }

    /**
     * Display product stars based on users rating
     * @return string
     */
    public function getNtavAddStarsByRate()
    {
        $this->nbReviews = isset($this->averageReviews['nb_reviews']) ? $this->averageReviews['nb_reviews'] : 0;
        $rate = isset($this->averageReviews['rate']) ? $this->averageReviews['rate'] : 0;
        $this->rateFormated = number_format($rate, 2);
        return $this->helperReviewsAPI->ntavAddStars($rate);
    }


    /**
     * Check rich snippets activate
     * @return string
     */
    public function getRichSnippets()
    {
        return $this->helperReviewsAPI->getRichSnippets($this->storeId);
    }

    /**
     * Rich snippets already exist
     * @return bool
     */
    public function isRichSnippetsAlreadyExist()
    {
        return $this->helperReviewsAPI->isRichSnippetsAlreadyExist($this->storeId);
    }

    public function getCurrentCurrency()
    {
        return $this->storeManager->getStore()->getCurrentCurrency()->getCode();
    }

    /**
     * cache 4 heures
     * @return bool|int|null
     */
    protected function getCacheLifetime()
    {
        return parent::getCacheLifetime() ?: 14400;
    }

    /**
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $keyInfo     =  parent::getCacheKeyInfo();
        $keyInfo[]   =  $this->productRef.'_nrStar';
        return $keyInfo;
    }

    /**
     * get information for google rich-snipped
     * @param $code
     * @return mixed
     */
    public function getAttributProductByPlaConfig($code)
    {

        $scope = ScopeInterface::SCOPE_WEBSITE;
        $configValue = $this->scopeConfig->getValue(
            Data::XML_PATH_AVISVERIFIES_PLA . $code,
            $scope
        );
        return $this->product->getdata($configValue);
    }

}
