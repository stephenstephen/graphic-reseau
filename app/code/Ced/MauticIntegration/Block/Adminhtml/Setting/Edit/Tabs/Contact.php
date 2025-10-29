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

namespace Ced\MauticIntegration\Block\Adminhtml\Setting\Edit\Tabs;

use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class Contact extends \Magento\Backend\Block\Widget implements RendererInterface
{

    /**
     * @var string
     */
    public $_template = 'Ced_MauticIntegration::setting/edit/contact.phtml';

    /**
     * @var \Ced\MauticIntegration\Model\ResourceModel\CedMautic\CollectionFactory
     */
    public $cedMautic;

    /**
     * Contact constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Ced\MauticIntegration\Model\ResourceModel\CedMautic\CollectionFactory $cedMautic
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Ced\MauticIntegration\Model\ResourceModel\CedMautic\CollectionFactory $cedMautic,
        array $data = []
    ) {
        $this->cedMautic = $cedMautic;
        parent::__construct($context, $data);
    }

    /**
     * Render form element as HTML
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

    /**
     * @return mixed
     */
    public function getContactProperty()
    {
        return $this->cedMautic->create()->addFieldToFilter(
            'entity_type',
            ['eq' => \Ced\MauticIntegration\Model\CedMautic::TYPE_PROPERTY]
        );
    }

    /**
     * @return mixed
     */
    public function getAjaxUrl()
    {
        return $this->getUrl('*/setting/createsingleproperty');
    }

    public function getConnectionStatus()
    {
        return $this->_scopeConfig->getValue('mautic_integration/mauticapi_integration/connection_established');
    }
}
