<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_MauticIntegration
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license     http://cedcommerce.com/license-agreement.txt
 */
namespace Ced\MauticIntegration\Controller\Config;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class GetCode extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Ced\MauticIntegration\Helper\ConnectionManager
     */
    public $connectionManager;

    /**
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * GetCode constructor.
     * @param Context $context
     * @param \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager,
        PageFactory $resultPageFactory
    ) {
        $this->connectionManager = $connectionManager;
        $this->resultPageFactory  = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        if ($this->connectionManager->oauthType=='Oauth2') {
            if (isset($params['state']) && isset($params['code'])) {
                if ($params['state'] == 'cedcommerce') {
                    $code = $this->getRequest()->getParam('code');
                    $response = $this->connectionManager->getToken($code);
                    if ($response) {
                        if (isset($response['errors'])) {
                            $this->connectionManager->setMauticConfig('connection_established', 0);
                            $this->connectionManager->cleanCache();
                            $this->connectionManager->connectionEstablished = 0;
                        } else {
                            $this->connectionManager->setMauticConfig('connection_established', 1);
                            $this->connectionManager->cleanCache();
                            $this->connectionManager->connectionEstablished = 1;
                        }
                    } else {
                        $this->connectionManager->setMauticConfig('connection_established', 0);
                        $this->connectionManager->cleanCache();
                        $this->connectionManager->connectionEstablished = 0;
                    }
                } else {
                    $this->messageManager->addErrorMessage('State unique string does not match');
                }
            } else {
                $this->connectionManager->setMauticConfig('connection_established', 0);
                $this->connectionManager->cleanCache();
                $this->connectionManager->connectionEstablished = 0;
            }
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Mautic Integration Authentication'));
        return $resultPage;
    }
}
