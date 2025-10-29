<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Controller\Adminhtml\Request;

use Amasty\Rma\Api\RequestRepositoryInterface;
use Amasty\Rma\Controller\Adminhtml\RegistryConstants;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\Model\Session;

class View extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Amasty_Rma::request_view';

    /**
     * @var RequestRepositoryInterface
     */
    private $requestRepository;

    /**
     * @var Session
     */
    private $adminSession;

    public function __construct(
        RequestRepositoryInterface $requestRepository,
        Action\Context $context
    ) {
        parent::__construct($context);
        $this->requestRepository = $requestRepository;
        $this->adminSession = $context->getSession();
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        if ($requestId = (int) $this->getRequest()->getParam(RegistryConstants::REQUEST_ID)) {
            try {
                $this->requestRepository->getById($requestId);
                $backToRmaFlow = $this->adminSession->getBackToRmaFlow() ?? [];
                $backToRmaFlow = array_filter($backToRmaFlow, function ($flowOrder) use ($requestId) {
                    return $flowOrder['request_id'] ?? 0 !== $requestId;
                });
                $this->adminSession->setBackToRmaFlow($backToRmaFlow);
                $resultPage->getConfig()->getTitle()->prepend(__('View Return Request'));
            } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This request is no longer exists.'));

                return $this->_redirect('*/*');
            }
        } else {
            $this->_redirect('*/*');
        }

        return $resultPage;
    }
}
