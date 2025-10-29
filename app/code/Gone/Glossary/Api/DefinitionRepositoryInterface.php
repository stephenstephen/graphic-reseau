<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Glossary\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface DefinitionRepositoryInterface
{

    /**
     * Save Definition
     * @param \Gone\Glossary\Api\Data\DefinitionInterface $definition
     * @return \Gone\Glossary\Api\Data\DefinitionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Gone\Glossary\Api\Data\DefinitionInterface $definition
    );

    /**
     * Retrieve Definition
     * @param string $definitionId
     * @return \Gone\Glossary\Api\Data\DefinitionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($definitionId);

    /**
     * Retrieve Definition matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Gone\Glossary\Api\Data\DefinitionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Definition
     * @param \Gone\Glossary\Api\Data\DefinitionInterface $definition
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Gone\Glossary\Api\Data\DefinitionInterface $definition
    );

    /**
     * Delete Definition by ID
     * @param string $definitionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($definitionId);
}
