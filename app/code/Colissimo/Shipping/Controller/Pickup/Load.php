<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2017 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Controller\Pickup;

use Colissimo\Shipping\Helper\Data as ShippingHelper;
use Magento\Framework\Controller\ResultFactory;
use Magento\Checkout\Model\Type\Onepage;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

/**
 * Class Load
 */
class Load extends Action
{

    /**
     * @var ShippingHelper $shippingHelper
     */
    protected $shippingHelper;

    /**
     * @var Onepage $onepage
     */
    protected $onepage;

    /**
     * @param Context $context
     * @param ShippingHelper $shippingHelper
     * @param Onepage $onepage
     */
    public function __construct(
        Context $context,
        ShippingHelper $shippingHelper,
        Onepage $onepage
    ) {
        parent::__construct($context);
        $this->shippingHelper = $shippingHelper;
        $this->onepage = $onepage;
    }

    /**
     * Execute
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Layout\Interceptor $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);

        /** @var \Colissimo\Shipping\Block\Frontend\Pickup\Load $block */
        $block = $result->getLayout()->getBlock('colissimo_pickup_load');
        $block->setData(
            $this->getAddress()
        );

        return $result;
    }

    /**
     * Retrieve current address
     *
     * @return array
     */
    protected function getAddress()
    {
        $shipping = $this->onepage->getQuote()->getShippingAddress();
        if (!$shipping->getPostcode() && $this->onepage->getCustomerSession()->isLoggedIn()) {
            $default = $this->onepage->getCustomerSession()->getCustomer()->getDefaultShippingAddress();
            if ($default) {
                $shipping = $default;
            }
        }

        $address = $this->shippingHelper->getDefaultAddress();

        $telephone = '';
        $countryId = $shipping->getCountryId();

        if ($shipping->getPostcode()) {
            $countryId = $this->shippingHelper->getCountry($shipping->getCountryId());

            $address = [
                'street'     => $shipping->getStreet()[0],
                'city'       => $shipping->getCity(),
                'postcode'   => $shipping->getPostcode(),
                'country_id' => $countryId,
            ];

            $telephone = $shipping->getTelephone();
        }

        $data = $this->getRequest()->getParams();

        if (!empty($data)) {
            if (isset($data['city']) && isset($data['postcode'])) {
                $countryId = $this->shippingHelper->getDefaultCountry();
                if (isset($data['country_id'])) {
                    $countryId = $data['country_id'];
                }
                $address = [
                    'street'     => isset($data['street'])   ? $data['street'] : '',
                    'city'       => isset($data['city'])     ? $data['city'] : '',
                    'postcode'   => isset($data['postcode']) ? $data['postcode'] : '',
                    'country_id' => $countryId,
                ];

                if (isset($data['telephone'])) {
                    $telephone = $data['telephone'];
                }
            }
        }

        $telephone = preg_replace('/[^0-9+]/', '', $telephone);

        if ($countryId && $telephone) {
            $phoneData = $this->shippingHelper->getMobilePhoneData($countryId);
            if (isset($phoneData['code'])) {
                if (!empty($phoneData['code'])) {
                    $telephone = ltrim(str_replace($phoneData['code'], '', $telephone), 0);
                }
            }
        }

        $address['telephone'] = $telephone;

        return $address;
    }
}
