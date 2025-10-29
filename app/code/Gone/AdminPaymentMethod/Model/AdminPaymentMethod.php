<?php

namespace Gone\AdminPaymentMethod\Model;

/**
 * Class AdminPaymentMethod
 *
 * @package Gone\AdminPaymentMethod\Model
 */
class AdminPaymentMethod extends \Magento\Payment\Model\Method\AbstractMethod
{
    /**
     * Payment code
     *
     * @var string|bool
     */
    const CODE                  = 'adminpaymentmethod';

    /**
     * @var string
     */
    protected $_code            = self::CODE;

    /**
     * @var bool
     */
    protected $_isOffline       = true;

    /**
     * @var bool
     */
    protected $_canUseCheckout  = false;

    /**
     * @var bool
     */
    protected $_canUseInternal  = true;

    /**
     * Get pre select option from config
     *
     * @return string
     */
    public function getDataPreSelect()
    {
        return $this->getConfigData('preselect');
    }

    /**
     * Get Auto Create Invoice option from config
     *
     * @return bool
     */
    public function getDataAutoCreateInvoice()
    {
        return $this->getConfigData('createinvoice');
    }
}
