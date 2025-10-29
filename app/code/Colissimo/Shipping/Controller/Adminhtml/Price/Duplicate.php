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
use Colissimo\Shipping\Model\PriceFactory;
use Magento\Backend\App\Action;
use Exception;

/**
 * Class Duplicate
 */
class Duplicate extends Action
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
     * @var PriceFactory $priceFactory
     */
    protected $priceFactory;

    /**
     * @param Action\Context $context
     * @param PriceRepositoryInterface $priceRepository
     * @param PriceFactory $priceFactory
     */
    public function __construct(
        Action\Context $context,
        PriceRepositoryInterface $priceRepository,
        PriceFactory $priceFactory
    ) {
        $this->priceRepository = $priceRepository;
        $this->priceFactory    = $priceFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $price = $this->priceFactory->create();

        $priceId = $this->getRequest()->getParam('price_id');
        if ($priceId) {
            $price = $this->priceRepository->getById($priceId);
            $price->setPk(null);
        }

        try {
            $this->priceRepository->save($price);
            $this->messageManager->addSuccessMessage(__('You duplicated the price.'));
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while duplicating the price.'));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
