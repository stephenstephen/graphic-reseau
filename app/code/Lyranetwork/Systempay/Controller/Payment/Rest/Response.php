<?php
/**
 * Copyright © Lyra Network.
 * This file is part of Systempay plugin for Magento 2. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
namespace Lyranetwork\Systempay\Controller\Payment\Rest;

use Lyranetwork\Systempay\Model\ResponseException;
use Magento\Framework\DataObject;

class Response extends \Lyranetwork\Systempay\Controller\Payment\Response
{
    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var \Lyranetwork\Systempay\Model\Api\SystempayResponseFactory
     */
    protected $systempayResponseFactory;

    /**
     * @var \Lyranetwork\Systempay\Helper\Rest
     */
    protected $restHelper;

    /**
     * @var \Magento\Quote\Api\CartManagementInterface
     */
    protected $quoteManagement;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \Lyranetwork\Systempay\Controller\Processor\ResponseProcessor $responseProcessor
     * @param \Lyranetwork\Systempay\Controller\Result\RedirectFactory $systempayRedirectFactory
     * @param \Lyranetwork\Systempay\Helper\Rest $restHelper
     * @param \Magento\Quote\Api\CartManagementInterface $quoteManagement
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Lyranetwork\Systempay\Controller\Processor\ResponseProcessor $responseProcessor,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Lyranetwork\Systempay\Helper\Rest $restHelper,
        \Magento\Quote\Api\CartManagementInterface $quoteManagement
    ) {
        $this->restHelper = $restHelper;
        $this->quoteManagement = $quoteManagement;
        $this->orderFactory = $responseProcessor->getOrderFactory();
        $this->systempayResponseFactory = $responseProcessor->getSystempayResponseFactory();

        parent::__construct($context, $quoteRepository, $responseProcessor, $resultPageFactory);
    }

    public function execute()
    {
        // Clear quote data.
        $this->dataHelper->getCheckout()->unsLastQuoteId()
            ->unsLastSuccessQuoteId()
            ->clearHelperData();

        return parent::execute();
    }

    protected function prepareResponse($params)
    {
        // Check the validity of the request.
        if (! $this->restHelper->checkResponseFormat($params)) {
            throw new ResponseException('Invalid response received. Content: ' . json_encode($params));
        }

        $answer = json_decode($params['kr-answer'], true);
        if (! is_array($answer)) {
            throw new ResponseException('Invalid response received. Content: ' . json_encode($params));
        }

        // Wrap payment result to use traditional order creation tunnel.
        $data = $this->restHelper->convertRestResult($answer);

        // Convert REST result to standard form response.
        $response = $this->systempayResponseFactory->create(
            [
                'params' => $data,
                'ctx_mode' => null,
                'key_test' => null,
                'key_prod' => null,
                'algo' => null
            ]
        );

        $quoteId = (int) $response->getExtInfo('quote_id');
        $quote = $this->quoteRepository->get($quoteId);
        if (! $quote->getId()) {
            throw new ResponseException("Quote #{$quoteId} not found in database.");
        }

        // Disable quote.
        if ($quote->getIsActive()) {
            $quote->setIsActive(false);
            $this->quoteRepository->save($quote);
            $this->dataHelper->log("Cleared quote, reserved order ID: #{$quote->getReservedOrderId()}.");
        }

        // Token is created before order creation, search order by quote.
        $order = $this->orderFactory->create();
        $order->loadByIncrementId($quote->getReservedOrderId());

        if (! $order->getId()) {
            $this->getOnepageForQuote($quote)->saveOrder();

            // Dispatch save order event.
            $result = new DataObject();
            $result->setData('success', true);
            $result->setData('error', false);

            $this->_eventManager->dispatch(
                'checkout_controller_onepage_saveOrder',
                [
                    'result' => $result,
                    'action' => $this
                ]
            );

            // Load newly created order.
            $order->loadByIncrementId($quote->getReservedOrderId());
            if (! $order->getId()) {
                throw new ResponseException("Order cannot be created for quote #{$quoteId}.");
            }

            $this->dataHelper->log("Order #{$order->getIncrementId()} has been created for quote #{$quoteId}.");
        } else {
            $this->dataHelper->log("Found order #{$order->getIncrementId()} for quote #{$quoteId}.");
        }

        $storeId = $order->getStore()->getId();

        // Check the authenticity of the request.
        if (! $this->restHelper->checkResponseHash($params, $this->restHelper->getReturnKey($storeId))) {
            // Authentication failed.
            throw new ResponseException(
                "{$this->dataHelper->getIpAddress()} tries to access systempay/payment_rest/response page without valid signature with parameters: " . json_encode($params)
            );
        }

        return [
            'response' => $response,
            'order' => $order
        ];
    }

    private function getOnepageForQuote($quote)
    {
        $onepage = $this->_objectManager->get(\Magento\Checkout\Model\Type\Onepage::class);
        $onepage->setQuote($quote);

        return $onepage;
    }
}
