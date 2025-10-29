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

namespace Ced\MauticIntegration\Block\Adminhtml\System\Config;

class RedirectUri extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var \Magento\Framework\Url $urlHelper
     */
    public $urlHelper;

    /** @var \Magento\Config\Model\ResourceModel\Config  */
    public $resourceConfig;

    /**
     * RedirectUri constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Url $urlHelper
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Url $urlHelper,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig
    ) {
        $this->urlHelper = $urlHelper;
        $this->resourceConfig = $resourceConfig;
        parent::__construct($context);
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function _getElementHtml(
        \Magento\Framework\Data\Form\Element\AbstractElement $element
    ) {
        if ($this->_storeManager->getStore()->isFrontUrlSecure()) {
            $baseUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB, true)
                . 'mauticintegration/config/getcode';
        } else {
            $baseUrl = $this->_storeManager->getStore()->getBaseUrl().'mauticintegration/config/getcode';
        }
         $this->resourceConfig->saveConfig(
             'mautic_integration/mauticapi_integration/redirect_url',
             $baseUrl,
             'default',
             0
         );
        return $baseUrl;
    }
}
