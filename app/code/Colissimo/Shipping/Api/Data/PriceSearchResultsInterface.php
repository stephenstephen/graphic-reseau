<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface PriceSearchResultsInterface
 */
interface PriceSearchResultsInterface extends SearchResultsInterface
{

    /**
     * Get price list.
     *
     * @return \Colissimo\Shipping\Api\Data\PriceInterface[]
     */
    public function getItems();

    /**
     * Set blocks list.
     *
     * @param \Colissimo\Shipping\Api\Data\PriceInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
