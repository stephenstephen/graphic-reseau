<?php
/*
 * Copyright © Kiliba. All rights reserved.
 */

namespace Kiliba\Connector\Model;

use Kiliba\Connector\Api\Data\LogInterfaceFactory;
use Kiliba\Connector\Api\Data\LogSearchResultsInterfaceFactory;
use Kiliba\Connector\Api\LogRepositoryInterface;
use Kiliba\Connector\Model\ResourceModel\Log as ResourceLog;
use Kiliba\Connector\Model\ResourceModel\Log\CollectionFactory as LogCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class LogRepository implements LogRepositoryInterface
{

    /**
     * @var ResourceLog
     */
    protected $resource;

    /**
     * @var LogFactory
     */
    protected $logFactory;

    /**
     * @var LogCollectionFactory
     */
    protected $logCollectionFactory;

    /**
     * @var LogSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var LogInterfaceFactory
     */
    protected $dataLogFactory;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var JoinProcessorInterface
     */
    protected $extensionAttributesJoinProcessor;

    /**
     * @var ExtensibleDataObjectConverter
     */
    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceLog $resource
     * @param LogFactory $logFactory
     * @param LogInterfaceFactory $dataLogFactory
     * @param LogCollectionFactory $logCollectionFactory
     * @param LogSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceLog $resource,
        LogFactory $logFactory,
        LogInterfaceFactory $dataLogFactory,
        LogCollectionFactory $logCollectionFactory,
        LogSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->logFactory = $logFactory;
        $this->logCollectionFactory = $logCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataLogFactory = $dataLogFactory;
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
        \Kiliba\Connector\Api\Data\LogInterface $log
    ) {
        /* if (empty($log->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $log->setStoreId($storeId);
        } */

        $logData = $this->extensibleDataObjectConverter->toNestedArray(
            $log,
            [],
            \Kiliba\Connector\Api\Data\LogInterface::class
        );

        $logModel = $this->logFactory->create()->setData($logData);

        try {
            $this->resource->save($logModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the log: %1',
                $exception->getMessage()
            ));
        }
        return $logModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($logId)
    {
        $log = $this->logFactory->create();
        $this->resource->load($log, $logId);
        if (!$log->getId()) {
            throw new NoSuchEntityException(__('Log with id "%1" does not exist.', $logId));
        }
        return $log->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->logCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Kiliba\Connector\Api\Data\LogInterface::class
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
        \Kiliba\Connector\Api\Data\LogInterface $log
    ) {
        try {
            $logModel = $this->logFactory->create();
            $this->resource->load($logModel, $log->getLogId());
            $this->resource->delete($logModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Log: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($logId)
    {
        return $this->delete($this->get($logId));
    }
}
