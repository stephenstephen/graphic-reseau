<?php

namespace Netreviews\Avisverifies\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use Netreviews\Avisverifies\Helper\ReviewsAPI;

class AjaxReviews extends Template
{
    public $helperReviewsAPI;
    protected $coreRegistry;
    protected $storeManager;
    public $productRef;
    public $idWebsite;
    protected $storeId;
    public $secretKey;
    public $productId;
    public $productName;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * AjaxReviews constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ReviewsAPI $helperReviewsAPI
     * @throws NoSuchEntityException
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ReviewsAPI $helperReviewsAPI
    ) {
        $this->helperReviewsAPI = $helperReviewsAPI;
        $this->coreRegistry = $registry;
        $this->storeManager = $context->getStoreManager();
        $this->scopeConfig = $context->getScopeConfig();
        $this->storeId = $this->storeManager->getStore()->getId();
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
        $this->productRef = $this->helperReviewsAPI->getProductRef($this->storeId);
        parent::__construct($context);
    }

    /**
     * get list reviexs
     * @param $arg
     * @return array
     */
    public function getReviewList($arg)
    {
        $reviewsList = array();
        $reviewsPerPage = 10;
        if (is_array($arg) && array_key_exists('data', $arg) && array_key_exists('isAjax', $arg['data'])) {
            $pageNumber = $arg['data']['pageNumber'];
            $reviewsFilter = $arg['data']['reviewsFilter'];
            $avisVerifiesRateFilter = $arg['data']['rateFilter'];
            $this->productRef = $arg['data']['productRef'];
            $this->productId = $arg['data']['productId'];
            $this->productName = $arg['data']['productName'];
        } else {
            $pageNumber = 0;
            $reviewsFilter = 'newest';
            $avisVerifiesRateFilter = '';
            $this->productId = $this->coreRegistry->registry('current_product')->getId();
            $this->productName = $this->coreRegistry->registry('current_product')->getName();
        }
        $reviewListAndStat = $this->helperReviewsAPI->getCacheReviewsStats(
            $this->productRef,
            $pageNumber,
            $reviewsPerPage,
            $reviewsFilter,
            $this->idWebsite,
            $avisVerifiesRateFilter
        );
        if (is_array($reviewListAndStat) && !empty($reviewListAndStat)) {
            $reviewsList = $reviewListAndStat[0]->reviews;
        }
        return $reviewsList;
    }

    /**
     * @return mixed
     */
    public function isEnableMedia()
    {
        return $this->scopeConfig->getValue(
            'av_configuration/plateforme/enable_media',
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }

    /**
     * @return mixed
     */
    public function isEnableHelpfulReviews()
    {
        return $this->scopeConfig->getValue(
            'av_configuration/plateforme/enable_helpful_reviews',
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getStoreUrl()
    {
        $aStoreUrl = explode('/', $this->storeManager->getStore()->getBaseUrl());
        return str_replace('www.', '', $aStoreUrl[2]);
    }

    /**
     * check rich snippets activate
     * @return bool
     */
    public function getRichSnippets()
    {
        return $this->helperReviewsAPI->getRichSnippets($this->storeId);
    }


    public function getCurrentProduct()
    {
        return $this->coreRegistry->registry('current_product');
    }
}
