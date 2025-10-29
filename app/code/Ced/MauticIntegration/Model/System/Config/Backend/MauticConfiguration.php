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

class MauticConfiguration extends \Magento\Framework\App\Config\Value
{
    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    public $resourceConfig;

    /**
     * MauticConfiguration constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param \Magento\Config\Model\ResourceModel\Config $resourceConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig,
        array $data = []
    ) {
        $this->resourceConfig=$resourceConfig;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * @return \Magento\Framework\App\Config\Value|void
     */
    public function beforeSave()
    {
        if ($this->getValue() !== $this->getOldValue()) {
            if ($this->getConnectionStatus()) {
                $this->setMauticConfig('connection_established', 0);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getConnectionStatus()
    {
        return $this->_config->getValue('mautic_integration/mauticapi_integration/connection_established');
    }

    /**
     * @param $field
     * @param $value
     * @return \Magento\Config\Model\ResourceModel\Config|null
     */
    public function setMauticConfig($field, $value)
    {
        if (!$field) {
            return null;
        }
        $path = 'mautic_integration/mauticapi_integration/' . $field;
        return $this->resourceConfig->saveConfig($path, $value, 'default', 0);
    }

    /**
     * @param $value
     * @param $path
     * @param $oldValue
     */
    public function createLog($value, $path, $oldValue)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/mautic_conf.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $log = "value: " . $value." ; " .
            "old value:" .$oldValue." ; ".
        "path:" .$path.PHP_EOL;
        $logger->info($log);
    }
}
