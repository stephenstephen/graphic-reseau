<?php

namespace Netreviews\Avisverifies\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Netreviews\Avisverifies\Helper\ReviewsAPI;

class AjaxReviews extends Action
{
    protected $helperReviewsAPI;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * __construct
     *
     * @param $context
     * @param $resultPageFactory
     * @return void
     */
    public function __construct(
        Context $context,
        ReviewsAPI $helperReviewsAPI,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory
    ) {
        $this->helperReviewsAPI = $helperReviewsAPI;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Json|ResultInterface|mixed
     */
    public function execute()
    {
        $request = $this->getRequest();
        if ($request->getPost()) {
            $result = $this->resultJsonFactory->create();
            $resultPage = $this->resultPageFactory->create();
            $function = $request->getPost('function');
            if ($function == 'getMoreReviews') {
                $result = $this->getMoreReviews($request, $resultPage, $result);
            } elseif ($function == 'deleteCacheByTag') {
                $this->deleteCacheByTag($request);
            }
        }
        return $result;
    }

    /**
     * @param $request
     * @param $resultPage
     * @param $result
     * @return mixed
     */
    private function getMoreReviews($request, $resultPage, $result)
    {
        $avisVerifiesPageNumber = $request->getPost('avisVerifiesPageNumber');
        $avisVerifiesFilter = $request->getPost('avisVerifiesFilter');
        $productRef = $request->getPost('avisVerifiesProductRef');
        $productId = $request->getPost('avisVerifiesProductId');
        $productName = $request->getPost('avisVerifiesProductName');
        $avisVerifiesRateFilter = ($request->getPost('avisVerifiesRateFilter') != 'none') ?
            array($request->getPost('avisVerifiesRateFilter')) : array(1, 2, 3, 4, 5);
        $data = [
            'isAjax'=> true,
            'pageNumber' => $avisVerifiesPageNumber,
            'reviewsFilter' => $avisVerifiesFilter,
            'rateFilter' => $avisVerifiesRateFilter,
            'productRef' => $productRef,
            'productId' => $productId,
            'productName' => $productName
        ];
        $block = $resultPage->getLayout()
                            ->createBlock('Netreviews\Avisverifies\Block\AjaxReviews')
                            ->setTemplate('Netreviews_Avisverifies::reviewsList.phtml')
                            ->setData('data', $data)
                            ->toHtml();
        $result->setData(['output' => $block]);
        return $result;
    }

    private function deleteCacheByTag($request)
    {
        $avisVerifiesPageNumber = $request->getPost('avisVerifiesPageNumber');
        $avisVerifiesFilter = $request->getPost('avisVerifiesFilter');
        $productRef = $request->getPost('avisVerifiesProductRef');
        $avisVerifiesRateFilter = $request->getPost('avisVerifiesRateFilter');
        $this->helperReviewsAPI->deleteCacheByTag($productRef, $avisVerifiesPageNumber, $avisVerifiesFilter,$avisVerifiesRateFilter);
    }
}
