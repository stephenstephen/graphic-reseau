<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Customer\Block\Account;

use Magento\Customer\Block\Account\SortLinkInterface;
use Magento\Framework\App\DefaultPathInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Html\Link\Current;
use Magento\Framework\View\Element\Template\Context;
use Aheadworks\Affiliate\Model\ResourceModel\Account\CollectionFactory as AffiliateCollectionFactory;
use Magento\Customer\Model\Session;

class AffiliateLink extends Current implements SortLinkInterface
{
    /**
     * @var AffiliateCollectionFactory
     */
    protected $_affiliateCollectionFactory;

    /**
     * @var Session
     */
    protected $_customerSession;

    public function __construct(
        Context $context,
        DefaultPathInterface $defaultPath,
        AffiliateCollectionFactory $affiliateCollectionFactory,
        Session $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $defaultPath, $data);
        $this->_affiliateCollectionFactory = $affiliateCollectionFactory;
        $this->_customerSession = $customerSession;
    }

    public function toHtml()
    {
        $currentCustomerId = $this->_customerSession->getCustomerId();

        $affiliate = $this->_affiliateCollectionFactory->create()
            ->addFieldToFilter('customer_id', ['eq' => $currentCustomerId])
            ->getFirstItem();

        if ($affiliate->getId()) {
            return parent::toHtml();
        }
        return '';
    }

    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }
}
