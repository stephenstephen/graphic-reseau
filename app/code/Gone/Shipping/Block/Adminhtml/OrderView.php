<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Shipping\Block\Adminhtml;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Sales\Block\Adminhtml\Order\AbstractOrder;
use Magento\Sales\Helper\Admin;
use Magento\Shipping\Helper\Data as ShippingHelper;
use Magento\Tax\Helper\Data as TaxHelper;

class OrderView extends AbstractOrder
{

    protected CartRepositoryInterface $_quoteRepository;

    public function __construct(
        Context $context,
        Registry $registry,
        Admin $adminHelper,
        CartRepositoryInterface $quoteRepository,
        array $data = [],
        ?ShippingHelper $shippingHelper = null,
        ?TaxHelper $taxHelper = null
    )
    {
        parent::__construct($context, $registry, $adminHelper, $data, $shippingHelper, $taxHelper);
        $this->_quoteRepository = $quoteRepository;
    }

    public function getQuote()
    {
        $quoteId = $this->getOrder()->getQuoteId();
        if (!empty($quoteId)) {
            try {
                return $this->_quoteRepository->get($quoteId);
            } catch (NoSuchEntityException $e) {
                return false;
            }
        }

        return false;
    }
}
