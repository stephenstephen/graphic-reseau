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
use Magento\Backend\App\Action;
use Exception;

/**
 * Class Delete
 */
class Delete extends Action
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
     * @param Action\Context $context
     * @param PriceRepositoryInterface $priceRepository
     */
    public function __construct(
        Action\Context $context,
        PriceRepositoryInterface $priceRepository
    ) {
        $this->priceRepository = $priceRepository;
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

        try {
            $priceId = $this->getRequest()->getParam('price_id');
            if ($priceId) {
                $this->priceRepository->deleteById($priceId);
            }
            $this->messageManager->addSuccessMessage(__('You deleted the price.'));
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the price.'));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
