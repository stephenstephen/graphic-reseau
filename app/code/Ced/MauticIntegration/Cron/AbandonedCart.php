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

class AbandonedCart
{
    /**
     * @var \Ced\MauticIntegration\Helper\ConnectionManager
     */
    public $connectionManager;

    /** @var \Ced\MauticIntegration\Helper\ExportCustomers  */
    public $exportCustomers;

    /**
     * AbandonedCart constructor.
     * @param \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager
     * @param \Ced\MauticIntegration\Helper\ExportCustomers $exportCustomers
     */
    public function __construct(
        \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager,
        \Ced\MauticIntegration\Helper\ExportCustomers $exportCustomers
    ) {
        $this->connectionManager = $connectionManager;
        $this->exportCustomers = $exportCustomers;
    }

    /**
     * Cron to handle the abandoned cart
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        if ($this->connectionManager->getMauticConfig('enable') == 1 &&
            $this->connectionManager->isCustomerGroupEnabled('abandoned_cart')) {
            try {
                $this->exportCustomers->exportCustomerCart();
            } catch (\Exception $e) {
                $this->connectionManager->createLog(
                    'Exception in Abandoned cart cron:- '.$e->getMessage(),
                    '',
                    '',
                    ''
                );
            }
        }
    }
}
