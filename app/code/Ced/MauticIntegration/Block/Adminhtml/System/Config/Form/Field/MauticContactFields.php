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

namespace Ced\MauticIntegration\Block\Adminhtml\System\Config\Form\Field;

use Magento\Framework\View\Element\Template;

class MauticContactFields extends \Magento\Framework\View\Element\Html\Select
{
    /**
     * @var \Ced\MauticIntegration\Helper\ConnectionManager
     */
    public $connectionManager;

    /**
     * MauticContactFields constructor.
     * @param Template\Context $context
     * @param \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager,
        array $data = []
    ) {
        $this->connectionManager = $connectionManager;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function _toHtml()
    {
        $response = $this->connectionManager->getListOfFields();
        if (isset($response['status_code']) && $response['status_code'] == 200) {
            $newResponse = json_decode($response['response'], true);
            if (isset($newResponse['total']) && $newResponse['total'] > 0) {
                $fields = $newResponse['fields'];
                foreach ($fields as $field) {
                    if (strpos($field['alias'], 'ced_') !== 0 && !$this->isExcluded($field['alias'])) {
                        if (isset($field['alias'])) {
                            $value = $field['alias'];
                        } else {
                            $value = $field['label'];
                        }
                        $label = $field['label'];
                        $this->addOption($value, $label);
                    }
                }
            }
        }
        return parent::_toHtml();
    }

    public function isExcluded($attributeCode)
    {
        $excludedFields = ['email','firstname', 'lastname'];
        if (in_array($attributeCode, $excludedFields)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $value
     * @return mixed
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }
}
