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

namespace Ced\MauticIntegration\Observer\Order;

use Magento\Framework\Event\Observer;

class MauticCreateUpdateOrders implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Ced\MauticIntegration\Helper\ConnectionManager
     */
    private $connectionManager;

    /**
     * @var \Ced\MauticIntegration\Helper\ExportCustomers
     */
    private $exportCustomers;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * MauticCreateUpdateOrders constructor.
     * @param \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager
     */
    public function __construct(
        \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager,
        \Ced\MauticIntegration\Helper\ExportCustomers $exportCustomers,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->connectionManager = $connectionManager;
        $this->exportCustomers = $exportCustomers;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Observer $observer
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(Observer $observer)
    {
        $exportType = $this->scopeConfig->getValue('mautic_integration/mautic_customer_export/export_type');
        if ($this->connectionManager->getMauticConfig('enable') == 1 &&
            $exportType == \Ced\MauticIntegration\Block\Adminhtml\System\Config\ExportType::OBSERVER) {
            $customerId = $observer->getEvent()->getOrder()->getCustomerId();
            if ($customerId) {
                try {
                    $this->exportCustomers->exportCustomerOrder($customerId);
                } catch (\Exception $e) {
                    $this->connectionManager->createLog('Exception in Order create and update:- ' .
                        $e->getMessage(), '', '', '');
                }
            }
        }
    }
}
