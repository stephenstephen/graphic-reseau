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

class CustomerFields extends \Magento\Framework\View\Element\Html\Select
{
    /**
     * @var \Magento\Eav\Model\AttributeRepository
     */
    public $eavAttributeRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaInterface
     */
    public $searchCriteria;

    /**
     * CustomerFields constructor.
     * @param Template\Context $context
     * @param \Magento\Eav\Model\AttributeRepository $eavAttributeRepository
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Eav\Model\AttributeRepository $eavAttributeRepository,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        array $data = []
    ) {
        $this->eavAttributeRepository = $eavAttributeRepository;
        $this->searchCriteria = $searchCriteria;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\InputException
     */
    public function _toHtml()
    {
        $attributes = $this->eavAttributeRepository->getList('customer', $this->searchCriteria);
        foreach ($attributes->getItems() as $attribute) {
            if (!$this->isExcluded($attribute->getAttributeCode())) {
                $value = $attribute->getAttributeCode();
                if ($attribute->getDefaultFrontendLabel() != null) {
                    $label = $attribute->getDefaultFrontendLabel();
                } else {
                    $label = $attribute->getAttributeCode();
                }

                $this->addOption($value, $label);
            }
        }
        return parent::_toHtml();
    }

    /**
     * @param $value
     * @return mixed
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * @param $attributeCode
     * @return bool
     */
    public function isExcluded($attributeCode)
    {
        $excludedFields = ['password_hash', 'rp_token', 'rp_token_created_at',
            'disable_auto_group_change', 'failures_num', 'first_failure', 'lock_expires'];
        if (in_array($attributeCode, $excludedFields)) {
            return true;
        } else {
            return false;
        }
    }
}
