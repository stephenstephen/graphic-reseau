<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Subligraphy\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface CertificateSearchResultsInterface extends SearchResultsInterface
{

    /**
     * Get Certificate list.
     * @return CertificateInterface[]
     */
    public function getItems();

    /**
     * Set customer_id list.
     * @param CertificateInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
