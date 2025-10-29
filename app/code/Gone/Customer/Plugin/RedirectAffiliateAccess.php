<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Customer\Plugin;

use Aheadworks\Affiliate\Controller\Customer\Index;
use Aheadworks\Affiliate\Model\ResourceModel\Account\CollectionFactory as AffiliateCollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\Result\RedirectFactory;

class RedirectAffiliateAccess
{
    /**
     * @var AffiliateCollectionFactory
     */
    protected AffiliateCollectionFactory $_affiliateCollectionFactory;

    /**
     * @var Session
     */
    protected Session $_customerSession;

    /**
     * @var RedirectFactory
     */
    protected RedirectFactory $resultRedirectFactory;

    public function __construct(
        AffiliateCollectionFactory $affiliateCollectionFactory,
        Session $customerSession,
        RedirectFactory $resultRedirectFactory
    ) {
        $this->_affiliateCollectionFactory = $affiliateCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }

    public function aroundExecute(
        Index $subject,
        $proceed
    ) {
        $currentCustomerId = $this->_customerSession->getCustomerId();

        $affiliate = $this->_affiliateCollectionFactory->create()
            ->addFieldToFilter('customer_id', ['eq' => $currentCustomerId])
            ->getFirstItem();

        if (!$affiliate->getId()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account');
            return $resultRedirect;
        }
        return $proceed();
    }
}
