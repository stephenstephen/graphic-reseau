<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Subligraphy\Model;

use Exception;
use Gone\Subligraphy\Api\CertificateRepositoryInterface;
use Gone\Subligraphy\Api\Data\CertificateInterface;
use Gone\Subligraphy\Api\Data\CertificateInterfaceFactory;
use Gone\Subligraphy\Api\Data\CertificateSearchResultsInterfaceFactory;
use Gone\Subligraphy\Model\ResourceModel\Certificate as ResourceCertificate;
use Gone\Subligraphy\Model\ResourceModel\Certificate\CollectionFactory as CertificateCollectionFactory;
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

class CertificateRepository implements CertificateRepositoryInterface
{

    protected $searchResultsFactory;

    protected $certificateFactory;

    private $storeManager;

    protected $dataObjectProcessor;

    protected $dataCertificateFactory;

    protected $extensionAttributesJoinProcessor;

    private $collectionProcessor;

    protected $dataObjectHelper;

    protected $certificateCollectionFactory;

    protected $resource;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceCertificate $resource
     * @param CertificateFactory $certificateFactory
     * @param CertificateInterfaceFactory $dataCertificateFactory
     * @param CertificateCollectionFactory $certificateCollectionFactory
     * @param CertificateSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceCertificate $resource,
        CertificateFactory $certificateFactory,
        CertificateInterfaceFactory $dataCertificateFactory,
        CertificateCollectionFactory $certificateCollectionFactory,
        CertificateSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->certificateFactory = $certificateFactory;
        $this->certificateCollectionFactory = $certificateCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataCertificateFactory = $dataCertificateFactory;
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
        CertificateInterface $certificate
    ) {
        /* if (empty($certificate->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $certificate->setStoreId($storeId);
        } */

        $certificateData = $this->extensibleDataObjectConverter->toNestedArray(
            $certificate,
            [],
            CertificateInterface::class
        );

        $certificateModel = $this->certificateFactory->create()->setData($certificateData);

        try {
            $this->resource->save($certificateModel);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the certificate: %1',
                $exception->getMessage()
            ));
        }
        return $certificateModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($certificateId)
    {
        $certificate = $this->certificateFactory->create();
        $this->resource->load($certificate, $certificateId);
        if (!$certificate->getId()) {
            throw new NoSuchEntityException(__('Certificate with id "%1" does not exist.', $certificateId));
        }
        return $certificate->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        SearchCriteriaInterface $criteria
    ) {
        $collection = $this->certificateCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            CertificateInterface::class
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
        CertificateInterface $certificate
    ) {
        try {
            $certificateModel = $this->certificateFactory->create();
            $this->resource->load($certificateModel, $certificate->getCertificateId());
            $this->resource->delete($certificateModel);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Certificate: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($certificateId)
    {
        return $this->delete($this->get($certificateId));
    }
}
