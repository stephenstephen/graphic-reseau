<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Glossary\Model;

use Exception;
use Gone\Glossary\Api\Data\DefinitionInterface;
use Gone\Glossary\Api\Data\DefinitionInterfaceFactory;
use Gone\Glossary\Api\Data\DefinitionSearchResultsInterfaceFactory;
use Gone\Glossary\Api\DefinitionRepositoryInterface;
use Gone\Glossary\Model\ResourceModel\Definition as ResourceDefinition;
use Gone\Glossary\Model\ResourceModel\Definition\CollectionFactory as DefinitionCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class DefinitionRepository implements DefinitionRepositoryInterface
{

    protected $resource;

    protected $extensibleDataObjectConverter;
    protected $definitionCollectionFactory;

    protected $searchResultsFactory;

    private $storeManager;

    protected $dataDefinitionFactory;

    protected $definitionFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $extensionAttributesJoinProcessor;

    private $collectionProcessor;


    /**
     * @param ResourceDefinition $resource
     * @param DefinitionFactory $definitionFactory
     * @param DefinitionInterfaceFactory $dataDefinitionFactory
     * @param DefinitionCollectionFactory $definitionCollectionFactory
     * @param DefinitionSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceDefinition $resource,
        DefinitionFactory $definitionFactory,
        DefinitionInterfaceFactory $dataDefinitionFactory,
        DefinitionCollectionFactory $definitionCollectionFactory,
        DefinitionSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->definitionFactory = $definitionFactory;
        $this->definitionCollectionFactory = $definitionCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataDefinitionFactory = $dataDefinitionFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        DefinitionInterface $definition
    ) {
        /* if (empty($definition->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $definition->setStoreId($storeId);
        } */

        $definitionData = $this->extensibleDataObjectConverter->toNestedArray(
            $definition,
            [],
            DefinitionInterface::class
        );

        $definitionModel = $this->definitionFactory->create()->setData($definitionData);

        try {
            $this->resource->save($definitionModel);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the definition: %1',
                $exception->getMessage()
            ));
        }
        return $definitionModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($definitionId)
    {
        $definition = $this->definitionFactory->create();
        $this->resource->load($definition, $definitionId);
        if (!$definition->getId()) {
            throw new NoSuchEntityException(__('Definition with id "%1" does not exist.', $definitionId));
        }
        return $definition->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        SearchCriteriaInterface $criteria
    ) {
        $collection = $this->definitionCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            DefinitionInterface::class
        );

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        DefinitionInterface $definition
    ) {
        try {
            $definitionModel = $this->definitionFactory->create();
            $this->resource->load($definitionModel, $definition->getDefinitionId());
            $this->resource->delete($definitionModel);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Definition: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($definitionId)
    {
        return $this->delete($this->get($definitionId));
    }
}
