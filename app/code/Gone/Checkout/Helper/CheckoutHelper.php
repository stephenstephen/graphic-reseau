<?php


namespace Gone\Checkout\Helper;

use Magento\Checkout\Helper\Cart as CartHelper;
use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class CheckoutHelper extends CartHelper
{

    public function __construct(
        Context $context,
        Cart $checkoutCart,
        Session $checkoutSession
    ) {
        parent::__construct(
            $context,
            $checkoutCart,
            $checkoutSession
        );
        $this->_checkoutSession = $checkoutSession;
    }

    /**
     * @return bool
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function hasDangerousItem()
    {
        $itemsArr = $this->_checkoutSession->getQuote()->getItems();

        foreach ($itemsArr as $item) {
            if ($item->getDangerousProduct()) {
                return true;
            }
        }
        return false;
    }
}
