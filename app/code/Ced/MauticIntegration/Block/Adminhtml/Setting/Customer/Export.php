<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_MauticIntegration
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\MauticIntegration\Block\Adminhtml\Setting\Customer;

class Export extends \Magento\Backend\Block\Widget\Container
{
    /**
     * Registry
     * @var \Magento\Framework\Registry
     */
    public $registry;

    /**
     * @var mixed Product Ids
     */
    public $ids;

    /**
     * BatchUpload constructor.
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->_getAddButtonOptions();
    }

    /**
     * Add Back button
     */
    public function _getAddButtonOptions()
    {
        $this->ids = $this->registry->registry('customerids');
        $splitButtonOptions = [
            'label' => __('Back'),
            'class' => 'action-secondary',
            'onclick' => "setLocation('" . $this->getCreateUrl() . "')"
        ];
        $this->buttonList->add('add', $splitButtonOptions);
    }

    /**
     * @return string
     */
    public function getCreateUrl()
    {
        return $this->getUrl('*/setting/edit');
    }

    /**
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->getUrl('*/setting/exportBulkCustomers');
    }
}
