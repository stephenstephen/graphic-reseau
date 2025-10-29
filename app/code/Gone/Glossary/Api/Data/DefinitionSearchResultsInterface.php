<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Glossary\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface DefinitionSearchResultsInterface extends SearchResultsInterface
{

    /**
     * Get Definition list.
     * @return DefinitionInterface[]
     */
    public function getItems();

    /**
     * Set status list.
     * @param DefinitionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
