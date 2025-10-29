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

namespace Ced\MauticIntegration\Block\Authorize;

use Magento\Framework\View\Element\Template;

class Config extends \Magento\Framework\View\Element\Template
{
    public $connectionManager;
    public function __construct(
        \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager,
        Template\Context $context,
        array $data = []
    ) {
        $this->connectionManager = $connectionManager;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function baseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    public function getConnectionStatus()
    {
        return $this->connectionManager->connectionEstablished;
    }

    public function getOauthType()
    {
        return $this->_scopeConfig->getValue('mautic_integration/mauticapi_integration/oauth_type');
    }
}
