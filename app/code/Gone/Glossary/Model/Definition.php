<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Glossary\Model;

use Gone\Glossary\Api\Data\DefinitionInterface;
use Gone\Glossary\Api\Data\DefinitionInterfaceFactory;
use Gone\Glossary\Model\ResourceModel\Definition\Collection;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class Definition extends AbstractModel
{

    protected $dataObjectHelper;

    protected $_eventPrefix = 'gone_glossary_definition';
    protected $definitionDataFactory;


    /**
     * @param Context $context
     * @param Registry $registry
     * @param DefinitionInterfaceFactory $definitionDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param ResourceModel\Definition $resource
     * @param Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DefinitionInterfaceFactory $definitionDataFactory,
        DataObjectHelper $dataObjectHelper,
        ResourceModel\Definition $resource,
        Collection $resourceCollection,
        array $data = []
    ) {
        $this->definitionDataFactory = $definitionDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve definition model with definition data
     * @return DefinitionInterface
     */
    public function getDataModel()
    {
        $definitionData = $this->getData();

        $definitionDataObject = $this->definitionDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $definitionDataObject,
            $definitionData,
            DefinitionInterface::class
        );

        return $definitionDataObject;
    }
}
