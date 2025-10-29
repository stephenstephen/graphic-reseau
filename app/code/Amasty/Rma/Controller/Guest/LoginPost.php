<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Controller\Guest;

use Amasty\Rma\Api\CustomerRequestRepositoryInterface;
use Amasty\Rma\Api\GuestCreateRequestProcessInterface;
use Amasty\Rma\Model\ConfigProvider;
use Amasty\Rma\Model\Cookie\HashChecker;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class LoginPost extends \Magento\Framework\App\Action\Action
{
    /**
     * @var GuestCreateRequestProcessInterface
     */
    private $guestCreateRequestProcess;

    /**
     * @var HashChecker
     */
    private $hashChecker;

    /**
     * @var CustomerRequestRepositoryInterface
     */
    private $customerRequestRepository;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    public function __construct(
        GuestCreateRequestProcessInterface $guestCreateRequestProcess,
        HashChecker $hashChecker,
        ConfigProvider $configProvider,
        CustomerRequestRepositoryInterface $customerRequestRepository,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Context $context
    ) {
        parent::__construct($context);
        $this->guestCreateRequestProcess = $guestCreateRequestProcess;
        $this->hashChecker = $hashChecker;
        $this->customerRequestRepository = $customerRequestRepository;
        $this->orderRepository = $orderRepository;
        $this->configProvider = $configProvider;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $createRequest = $this->guestCreateRequestProcess->getEmptyCreateRequest();
        if ($orderIncrementId = $this->getRequest()->getParam('oar_order_id')) {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter(
                    \Magento\Sales\Api\Data\OrderInterface::INCREMENT_ID,
                    $orderIncrementId
                )->create();
            $order = $this->orderRepository->getList($searchCriteria)->getFirstItem();
            $createRequest->setOrderId(
                $order->getEntityId() ?: ''
            )->setBillingLastName(
                $this->getRequest()->getParam('oar_billing_lastname', '')
            )->setEmail(
                $this->getRequest()->getParam('oar_email', '')
            )->setZip(
                $this->getRequest()->getParam('oar_zip', '')
            );
        } elseif (($orderId = $this->getRequest()->getParam('order')) && ($hash = $this->hashChecker->getHash())) {
            try {
                $request = $this->customerRequestRepository->getByHash($hash);
                $guestOrder = $this->orderRepository->get($request->getOrderId());
                $guestNewOrder = $this->orderRepository->get($orderId);
                if ($guestNewOrder->getCustomerEmail() === $guestOrder->getCustomerEmail()
                    && $guestNewOrder->getBillingAddress()->getLastname()
                        === $guestOrder->getBillingAddress()->getLastname()
                ) {
                    $createRequest->setOrderId(
                        $guestNewOrder->getEntityId()
                    )->setBillingLastName(
                        $guestNewOrder->getBillingAddress()->getLastname()
                    )->setEmail(
                        $guestNewOrder->getCustomerEmail()
                    );
                }
            } catch (\Exception $e) {
                $this->messageManager->addWarningMessage(__('Order not found'));

                return $this->_redirect($this->configProvider->getUrlPrefix() . '/guest/login');
            }
        }

        if ($secretKey = $this->guestCreateRequestProcess->process($createRequest)) {
            return $this->_redirect(
                $this->configProvider->getUrlPrefix() . '/guest/newreturn',
                ['secret' => $secretKey]
            );
        } else {
            $this->messageManager->addWarningMessage(__('Order not found'));

            return $this->_redirect($this->configProvider->getUrlPrefix() . '/guest/login');
        }
    }
}
