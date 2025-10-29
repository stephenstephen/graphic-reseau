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
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Edit
 */
class Edit extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Colissimo_Shipping::shipping_price';

    /**
     * @var Registry $coreRegistry
     */
    protected $coreRegistry;

    /**
     * @var PageFactory $resultPageFactory
     */
    protected $resultPageFactory;

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
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     * @param PriceRepositoryInterface $priceRepository
     * @param PriceFactory $priceFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $registry,
        PriceRepositoryInterface $priceRepository,
        PriceFactory $priceFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry      = $registry;
        $this->priceRepository   = $priceRepository;
        $this->priceFactory      = $priceFactory;
        parent::__construct($context);
    }

    /**
     * Edit Price
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $priceId = $this->getRequest()->getParam('price_id');

        $price = $this->priceFactory->create();

        if ($priceId) {
            $price = $this->priceRepository->getById($priceId);
        }

        $this->coreRegistry->register('price', $price);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Price')));

        return $resultPage;
    }
}
