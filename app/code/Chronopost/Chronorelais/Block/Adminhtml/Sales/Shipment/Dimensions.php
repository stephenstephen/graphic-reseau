<?php
/**
 * Chronopost
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category  Chronopost
 * @package   Chronopost_Chronorelais
 * @copyright Copyright (c) 2021 Chronopost
 */
declare(strict_types=1);

namespace Chronopost\Chronorelais\Block\Adminhtml\Sales\Shipment;

use \Magento\Backend\Block\Template\Context;
use \Chronopost\Chronorelais\Helper\Webservice as HelperWS;
use Magento\Framework\View\Element\Template;

/**
 * Class Dimensions
 *
 * @package Chronopost\Chronorelais\Block\Adminhtml\Sales\Shipment
 */
class Dimensions extends Template
{

    /**
     * @var HelperWS
     */
    private $_helperData;

    /**
     * Dimensions constructor.
     *
     * @param Context  $context
     * @param HelperWS $_helperWS
     * @param array    $data
     */
    public function __construct(
        Context $context,
        HelperWS $_helperWS,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_helperData = $_helperWS;
    }

    /**
     * Get contracts html
     *
     * @param string $orderId
     *
     * @return string
     */
    public function getContractsHtml($orderId)
    {
        return $this->_helperData->getContractsHtml($orderId);
    }
}
