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

namespace Ced\MauticIntegration\Model\System\Config\Backend;

class FieldsMapping extends \Magento\Config\Model\Config\Backend\Serialized
{
    /**
     * @var \Magento\Framework\Math\Random
     */
    public $random;

    /**
     * FieldsMapping constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\Math\Random $random
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Math\Random $random,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->random = $random;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * @return $this|void
     */
    public function beforeSave()
    {
        $mappingValues = $this->getValue();
        unset($mappingValues['__empty']);
        foreach ($mappingValues as $rowId => $values) {
            if (isset($values['mautic_fields']) && isset($values['magento_fields'])) {
                $data[$values['mautic_fields']] = $values['magento_fields'];
            }
        }
        if (isset($data)) {
            $json_data = json_encode($data);
            $this->setValue($json_data);
        } else {
            $this->setValue(null);
        }

        parent::beforeSave();
    }

    /**
     * @return $this
     */
    public function afterLoad()
    {
        $value = $this->getValue();
        if (json_decode($value, true)) {
            $value = $this->encodeArrayFieldValue(json_decode($value, true));
            $this->setValue($value);
        }
        return $this;
    }

    /**
     * @param array $value
     * @return array
     */
    public function encodeArrayFieldValue(array $value)
    {
        $result = [];
        foreach ($value as $mauticField => $magentoField) {
            $id = $this->random->getUniqueHash('_');
            $result[$id] = ['mautic_fields' => $mauticField, 'magento_fields' => $magentoField];
        }
        return $result;
    }
}
