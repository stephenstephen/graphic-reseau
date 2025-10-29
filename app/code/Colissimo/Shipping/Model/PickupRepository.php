<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Model;

use Colissimo\Shipping\Api\PickupRepositoryInterface;
use Colissimo\Shipping\Api\Data\PickupSearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Exception;

/**
 * Class PickupRepository
 */
class PickupRepository implements PickupRepositoryInterface
{

    /**
     * @var PickupFactory $pickupFactory
     */
    protected $pickupFactory;

    /**
     * @var PickupSearchResultsInterfaceFactory $searchResultsFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CartRepositoryInterface $quoteRepository
     */
    protected $quoteRepository;

    /**
     * PickupRepository constructor.
     *
     * @param PickupFactory $pickupFactory
     * @param PickupSearchResultsInterfaceFactory $searchResultsFactory
     * @param CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        PickupFactory $pickupFactory,
        PickupSearchResultsInterfaceFactory $searchResultsFactory,
        CartRepositoryInterface $quoteRepository
    ) {
        $this->pickupFactory = $pickupFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function get($pickupId, $network)
    {
        $pickup = $this->pickupFactory->create();
        $pickup->load($pickupId, $network);

        if (!$pickup->hasData()) {
            throw new Exception(__('Unable to load pickup, please select another shipping method'));
        }

        return $pickup;
    }

    /**
     * {@inheritdoc}
     */
    public function current($cartId)
    {
        $quote = $this->quoteRepository->getActive($cartId);

        $pickup = $this->pickupFactory->create();
        $pickup->current($quote->getId());

        return $pickup;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $pickup = $this->pickupFactory->create();

        $required = ['street', 'city', 'postcode', 'country'];

        $data = [];

        foreach ($searchCriteria->getFilterGroups() as $group) {
            foreach ($group->getFilters() as $filter) {
                $data[$filter->getField()] = $filter->getValue();
            }
        }

        foreach ($required as $field) {
            if (!isset($data[$field])) {
                throw new Exception(__('%1 field is required', $field));
            }
        }

        $list = $pickup->getList($data['street'], $data['city'], $data['postcode'], $data['country']);

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($list->getItems());
        $searchResult->setTotalCount($list->getSize());

        return $searchResult;
    }

    /**
     * {@inheritdoc}
     */
    public function save($cartId, $pickupId, $networkCode, $telephone)
    {
        $quote = $this->quoteRepository->getActive($cartId);

        $pickup = $this->pickupFactory->create();

        return $pickup->save($quote->getId(), $pickupId, $networkCode, $telephone);
    }

    /**
     * {@inheritdoc}
     */
    public function reset($cartId)
    {
        $quote = $this->quoteRepository->getActive($cartId);

        $pickup = $this->pickupFactory->create();

        return $pickup->reset($quote->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function shippingData($orderId)
    {
        $pickup = $this->pickupFactory->create();

        return $pickup->shippingData($orderId);
    }
}
