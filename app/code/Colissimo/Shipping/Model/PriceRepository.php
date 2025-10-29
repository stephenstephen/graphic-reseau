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

use Colissimo\Shipping\Api\PriceRepositoryInterface;
use Colissimo\Shipping\Api\Data\PriceInterface;
use Colissimo\Shipping\Api\Data\PriceInterfaceFactory;
use Colissimo\Shipping\Api\Data\PriceSearchResultsInterfaceFactory;
use Colissimo\Shipping\Model\ResourceModel\PriceFactory as PriceResourceFactory;
use Colissimo\Shipping\Model\ResourceModel\Price\CollectionFactory as PriceResourceCollectionFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Class PriceRepository
 */
class PriceRepository implements PriceRepositoryInterface
{

    /**
     * @var PriceFactory $priceFactory
     */
    protected $priceFactory;

    /**
     * @var PriceResourceFactory $priceResourceFactory
     */
    protected $priceResourceFactory;

    /**
     * @var PriceResourceCollectionFactory $priceResourceCollectionFactory
     */
    protected $priceResourceCollectionFactory;

    /**
     * @var PriceSearchResultsInterfaceFactory $searchResultsFactory
     */
    protected $searchResultsFactory;

    /**
     * @var PriceInterfaceFactory $dataPriceResourceFactory
     */
    protected $dataPriceResourceFactory;

    /**
     * @param PriceFactory $priceFactory
     * @param PriceResourceFactory $priceResourceFactory
     * @param PriceInterfaceFactory $dataPriceResourceFactory
     * @param PriceResourceCollectionFactory $priceResourceCollectionFactory
     * @param PriceSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        PriceFactory $priceFactory,
        PriceResourceFactory $priceResourceFactory,
        PriceInterfaceFactory $dataPriceResourceFactory,
        PriceResourceCollectionFactory $priceResourceCollectionFactory,
        PriceSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->priceFactory = $priceFactory;
        $this->priceResourceFactory = $priceResourceFactory;
        $this->priceResourceCollectionFactory = $priceResourceCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataPriceResourceFactory = $dataPriceResourceFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(PriceInterface $price)
    {
        $resource = $this->priceResourceFactory->create();
        
        try {
            $resource->save($price);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the price: %1', $exception->getMessage()),
                $exception
            );
        }
        return $price;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($priceId)
    {
        $price    = $this->priceFactory->create();
        $resource = $this->priceResourceFactory->create();

        $resource->load($price, $priceId);

        if (!$price->getId()) {
            throw new NoSuchEntityException(__('Price with id %1 does not exist.', $priceId));
        }
        return $price;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->priceResourceCollectionFactory->create();

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(PriceInterface $price)
    {
        $resource = $this->priceResourceFactory->create();
        try {
            $resource->delete($price);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the price: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($priceId)
    {
        return $this->delete($this->getById($priceId));
    }

    /**
     * {@inheritdoc}
     */
    public function truncate()
    {
        $resource = $this->priceResourceFactory->create();
        try {
            $resource->truncate();
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not truncate prices',
                $exception->getMessage()
            ));
        }
        return true;
    }
}
