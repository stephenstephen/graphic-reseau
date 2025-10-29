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

namespace Chronopost\Chronorelais\Controller\Ajax;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Chronopost\Chronorelais\Helper\Data as HelperData;
use Chronopost\Chronorelais\Helper\Webservice as HelperWS;
use Magento\Framework\App\Action\Action as Action;

/**
 * Class CheckCarrierConfigContract
 *
 * @package Chronopost\Chronorelais\Controller\Ajax
 */
class CheckCarrierConfigContract extends Action
{

    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * @var $_helperWS
     */
    protected $_helperWS;

    /**
     * CheckCarrierConfigContract constructor.
     *
     * @param Context     $context
     * @param JsonFactory $jsonFactory
     * @param HelperData  $_helperData
     * @param HelperWS    $_helperWS
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        HelperData $_helperData,
        HelperWS $_helperWS
    ) {
        parent::__construct($context);
        $this->_resultJsonFactory = $jsonFactory;
        $this->_helperData = $_helperData;
        $this->_helperWS = $_helperWS;
    }

    /**
     * Check if shipping method is enabled
     *
     * @return Json
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $shippingMethod = $params['shippingMethod'];
        $contractId = $params['contractId'];

        $result = $this->_resultJsonFactory->create();

        $data = "not allowed";
        if ($this->_helperWS->shippingMethodEnabled($shippingMethod, $contractId)) {
            $data = "allowed";
        }

        $result->setData($data);

        return $result;
    }
}
