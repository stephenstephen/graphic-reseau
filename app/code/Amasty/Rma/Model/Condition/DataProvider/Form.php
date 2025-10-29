<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Model\Condition\DataProvider;

use Amasty\Rma\Api\Data\ConditionInterface;
use Amasty\Rma\Model\Condition\Repository;
use Amasty\Rma\Model\Condition\ResourceModel\CollectionFactory;
use Amasty\Rma\Controller\Adminhtml\RegistryConstants;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;

class Form extends AbstractDataProvider
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var array
     */
    private $loadedData;

    public function __construct(
        CollectionFactory $collectionFactory,
        Repository $repository,
        StoreManagerInterface $storeManager,
        DataPersistorInterface $dataPersistor,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->repository = $repository;
        $this->dataPersistor = $dataPersistor;
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $this->getCollection()->addFieldToSelect(ConditionInterface::CONDITION_ID);
        $data = parent::getData();
        if (isset($data['items'][0])) {
            $conditionId = $data['items'][0][ConditionInterface::CONDITION_ID];
            $condition = $this->repository->getById($conditionId);
            $this->loadedData[$conditionId] = $condition->getData();
            $selectedStores = [];
            foreach ($condition->getStores() as $store) {
                $this->loadedData[$conditionId]['storelabel' . $store->getStoreId()] = $store->getLabel();
                $selectedStores[] = (string)$store->getStoreId();
            }
            $this->loadedData[$conditionId]['store_ids'] = $selectedStores;
        }
        $data = $this->dataPersistor->get(RegistryConstants::CONDITION_DATA);

        if (!empty($data)) {
            $conditionId = isset($data[RegistryConstants::CONDITION_ID])
                ? $data[RegistryConstants::CONDITION_ID]
                : null;
            $this->loadedData[$conditionId] = $data;
            $this->dataPersistor->clear(RegistryConstants::CONDITION_DATA);
        }

        return $this->loadedData;
    }

    public function getMeta()
    {
        $meta = parent::getMeta();

        $storeCount = 0;
        foreach ($this->storeManager->getWebsites() as $website) {
            $meta['labels']['children']['website' . $website->getId()]['arguments']['data']['config'] = [
                'label' => $website->getName(),
                'collapsible' => true,
                'opened' => false,
                'visible' => true,
                'componentType' => 'fieldset'
            ];
            foreach ($website->getGroups() as $storeGroup) {
                $meta['labels']['children']['website' . $website->getId()]
                ['children']['group' . $storeGroup->getId()]['arguments']['data']['config'] = [
                    'label' => $storeGroup->getName(),
                    'collapsible' => true,
                    'opened' => true,
                    'visible' => true,
                    'componentType' => 'fieldset'
                ];

                foreach ($storeGroup->getStores() as $store) {
                    $storeCount++;
                    $meta['labels']['children']['website' . $website->getId()]
                    ['children']['group' . $storeGroup->getId()]['children']['store' . $store->getId()]
                    ['arguments']['data']['config'] = [
                        'label' => $store->getName(),
                        'collapsible' => true,
                        'opened' => true,
                        'visible' => true,
                        'componentType' => 'fieldset'
                    ];
                    $meta['labels']['children']['website' . $website->getId()]
                    ['children']['group' . $storeGroup->getId()]['children']['store' . $store->getId()]
                    ['children']['storelabel' . $store->getId()]['arguments']['data']['config'] = [
                        'label' => __('Label'),
                        'dataType' => 'text',
                        'formElement' => 'input',
                        'component' => 'Amasty_Rma/js/form/element/abstract',
                        'visible' => true,
                        'componentType' => 'field',
                        'tooltip' => [
                            'description' => __('A \'Title\' will be displayed to store admins in the '
                                . 'backend while a \'Label\' will be displayed to customers on the frontend')
                        ],
                        'source' => 'storelabel' . $store->getId()
                    ];
                }
            }
        }

        if ($storeCount === 1) {
            $meta['labels']['children']['website' . $website->getId()]['arguments']['data']['opened'] = false;
        }

        return $meta;
    }
}
