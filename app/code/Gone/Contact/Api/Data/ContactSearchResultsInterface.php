<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Contact\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ContactSearchResultsInterface extends SearchResultsInterface
{

    /**
     * Get Contact list.
     * @return ContactInterface[]
     */
    public function getItems();

    /**
     * Set contact_name list.
     * @param ContactInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
