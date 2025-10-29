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

namespace Ced\MauticIntegration\Observer\Quote;

use Magento\Framework\Event\Observer;

class MauticQuoteItemRemove implements \Magento\Framework\Event\ObserverInterface
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
     * @var \Magento\Quote\Model\QuoteFactory
     */
    private $quoteFactory;

    /**
     * MauticCreateUpdateOrders constructor.
     * @param \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager
     */
    public function __construct(
        \Ced\MauticIntegration\Helper\ConnectionManager $connectionManager,
        \Ced\MauticIntegration\Helper\ExportCustomers $exportCustomers,
        \Magento\Quote\Model\QuoteFactory $quoteFactory
    ) {
        $this->connectionManager = $connectionManager;
        $this->exportCustomers = $exportCustomers;
        $this->quoteFactory = $quoteFactory;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->connectionManager->getMauticConfig('enable') == 1) {
            $quoteId = $observer->getEvent()->getQuoteItem()->getQuoteId();
            $quote = $this->quoteFactory->create()->load($quoteId);
            $quoteItem = count($quote->getAllVisibleItems());
            if ($quoteItem==1) {
                $customerId = $this->quoteFactory->create()->load($quoteId)->getCustomerId();
                if ($customerId) {
                    try {
                        $this->exportCustomers->exportCustomerEmptyCart($customerId);
                    } catch (\Exception $e) {
                        $this->connectionManager->createLog('Exception in quote remove item:- ' .
                            $e->getMessage(), '', '', '');
                    }
                }
            }
        }
    }
}
