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

namespace Ced\MauticIntegration\Cron;

class ExportCustomers
{
    /**
     * @var \Ced\MauticIntegration\Helper\ConnectionManager
     */
    public $connectionManager;

    /** @var \Ced\MauticIntegration\Helper\ExportCustomers  */
    public $exportCustomers;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfig;

    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    public $resourceConfig;

    /**
     * AbandonedCart constructor.
     * @param \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager
     * @param \Ced\MauticIntegration\Helper\ExportCustomers $exportCustomers
     */
    public function __construct(
        \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager,
        \Ced\MauticIntegration\Helper\ExportCustomers $exportCustomers,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig
    ) {
        $this->connectionManager = $connectionManager;
        $this->exportCustomers = $exportCustomers;
        $this->scopeConfig = $scopeConfig;
        $this->resourceConfig = $resourceConfig;
    }
    public function execute()
    {
        $exportType = $this->scopeConfig->getValue('mautic_integration/mautic_customer_export/export_type');
        if ($this->connectionManager->getMauticConfig('enable') == 1 &&
            $exportType == \Ced\MauticIntegration\Block\Adminhtml\System\Config\ExportType::CRON) {
            try {
                $this->exportCustomers->exportCustomersByCron();
            } catch (\Exception $e) {
                $this->connectionManager->createLog(
                    'Exception in Abandoned cart cron:- '.$e->getMessage(),
                    '',
                    '',
                    ''
                );
            }
        } else {
            $this->resourceConfig->saveConfig(
                'mautic_integration/mautic_customer_export/cron_time',
                '0',
                'default',
                0
            );
        }
    }
}
