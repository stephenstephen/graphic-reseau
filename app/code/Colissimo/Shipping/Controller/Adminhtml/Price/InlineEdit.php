<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Controller\Adminhtml\Price;

use Colissimo\Shipping\Api\PriceRepositoryInterface;
use Colissimo\Shipping\Api\Data\PriceInterface;
use Colissimo\Shipping\Model\Price;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\App\Action;

/**
 * Class InlineEdit
 */
class InlineEdit extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Colissimo_Shipping::shipping_price';

    /**
     * @var PriceRepositoryInterface $priceRepository
     */
    protected $priceRepository;

    /**
     * @var JsonFactory $jsonFactory
     */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param PriceRepositoryInterface $priceRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        PriceRepositoryInterface $priceRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->priceRepository = $priceRepository;
        $this->jsonFactory     = $jsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $priceId) {
            $price = $this->priceRepository->getById($priceId);
            try {
                $extendedPageData = $price->getData();
                $this->setPriceData($price, $extendedPageData, $postItems[$priceId]);
                $this->priceRepository->save($price);
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithPriceId($price, $e->getMessage());
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add price title to error message
     *
     * @param PriceInterface $price
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithPriceId(PriceInterface $price, $errorText)
    {
        return '[Price ID: ' . $price->getPk() . '] ' . $errorText;
    }

    /**
     * Set price data
     *
     * @param Price $price
     * @param array $extendedPageData
     * @param array $priceData
     * @return $this
     */
    public function setPriceData(Price $price, array $extendedPageData, array $priceData)
    {
        $price->setData(array_merge($price->getData(), $extendedPageData, $priceData));
        return $this;
    }
}
