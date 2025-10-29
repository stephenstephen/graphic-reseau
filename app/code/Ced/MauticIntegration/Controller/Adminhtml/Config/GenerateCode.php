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

namespace Ced\MauticIntegration\Controller\Adminhtml\Config;

use Magento\Framework\App\Action\Context;

class GenerateCode extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;

    /**
     * @var \Ced\MauticIntegration\Helper\ConnectionManager
     */
    public $connectionManager;

    /**
     * GenerateCode constructor.
     * @param Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->connectionManager = $connectionManager;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $response = $this->resultJsonFactory->create();
        if ($this->connectionManager->getClientId() == null || $this->connectionManager->getRedirectUrl() == null ||
            $this->connectionManager->getMauticUrl() == null) {
            $response->setData(['message' =>'Please Save The Mautic Credentials', 'status' => false]);
            return $response;
        }
        $state = 'cedcommerce';
        $url = $this->connectionManager->getMauticUrl() . '/oauth/v2/authorize?client_id=' .
                $this->connectionManager->getClientId() . '&grant_type=authorization_code&redirect_uri=' .
                $this->connectionManager->getRedirectUrl() . '&response_type=code&state=' . $state . '';
        $status = true;
        $response->setData(['auth_url' => $url, 'status' => $status]);
        return $response;
    }
}
