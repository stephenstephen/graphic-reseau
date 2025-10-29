<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Glossary\Block;

use Gone\Glossary\Api\Data\DefinitionInterface;
use Gone\Glossary\Api\DefinitionRepositoryInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;

class Index extends Template
{
    public const NUMBERS_LABEL = '123';
    public const ALL_LABEL = 'all';

    protected DefinitionRepositoryInterface $_definitionRepository;
    protected SearchCriteriaBuilder $_searchCriteriaBuilder;
    protected SortOrderBuilder $_sortOrderBuilder;

    public int $definitionCollectionCount;
    public int $numberOfPages;

    public function __construct(
        Template\Context $context,
        DefinitionRepositoryInterface $definitionRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_definitionRepository = $definitionRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_sortOrderBuilder = $sortOrderBuilder;
    }

    public function getDefinitionCollection()
    {
        $this->_sortOrderBuilder
            ->setField(DefinitionInterface::TEXT)
            ->setDirection(SortOrder::SORT_ASC);

        $this->_searchCriteriaBuilder
            ->addFilter(DefinitionInterface::STATUS, 1)
            ->setSortOrders([$this->_sortOrderBuilder->create()]);

        $definitionCollection = $this->_definitionRepository->getList($this->_searchCriteriaBuilder->create());
        $this->definitionCollectionCount = $definitionCollection->getTotalCount();
        return $definitionCollection->getItems();
    }

    public function getAlphabet()
    {
        return ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
    }

    public function getFirstLetter($name)
    {
        return substr($name, 0, 1);
    }
}
