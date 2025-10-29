<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Controller\Adminhtml\Test;

use Colissimo\Shipping\Helper\Data as ShippingHelper;
use Colissimo\Shipping\Model\Soap;
use Magento\Backend\App\Action;

/**
 * Class Index
 */
class Index extends Action
{

    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Magento_Backend::system';

    /**
     * @var ShippingHelper $shippingHelper
     */
    protected $shippingHelper;

    /**
     * @var Soap $soap
     */
    protected $soap;

    /**
     * @param Action\Context $context
     * @param ShippingHelper $shippingHelper
     * @param Soap $soap
     */
    public function __construct(
        Action\Context $context,
        ShippingHelper $shippingHelper,
        Soap $soap
    ) {
        $this->shippingHelper = $shippingHelper;
        $this->soap = $soap;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $website = $this->getRequest()->getParam('website');
        $store   = $this->getRequest()->getParam('store');

        $data = [
            'address'       => '2 Avenue Gabriel',
            'zipCode'       => '75008',
            'city'          => 'Paris',
            'countryCode'   => 'FR',
            'shippingDate'  => date('d/m/Y'),
            'filterRelay'   => '1',
            'requestId'     => md5(rand(0, 99999)),
            'optionInter'   => 0,
        ];

        $response = $this->soap
            ->setStoreId($store)
            ->setWebsiteId($website)
            ->execute('findRDVPointRetraitAcheminement', $data);

        if ($response['error']) {
            $this->messageManager->addErrorMessage($response['error']);
        } else {
            $this->messageManager->addSuccessMessage(__('The connection is working fine'));
        }

        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
}
