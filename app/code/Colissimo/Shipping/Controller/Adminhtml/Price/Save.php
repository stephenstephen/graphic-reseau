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
 * Class Save
 */
class Save extends Action
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
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $price = $this->priceFactory->create();

            $priceId = $this->getRequest()->getParam('pk');
            if ($priceId) {
                $price = $this->priceRepository->getById($priceId);
            }

            $price->setData($data);

            try {
                $this->priceRepository->save($price);
                $this->messageManager->addSuccessMessage(__('You saved the price.'));
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the price.'));
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
