<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Colissimo\Shipping\Api\Data\PriceInterface;

/**
 * Interface PriceRepositoryInterface
 */
interface PriceRepositoryInterface
{

    /**
     * Save price.
     *
     * @param PriceInterface $price
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(PriceInterface $price);

    /**
     * Retrieve price.
     *
     * @param int $priceId
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($priceId);

    /**
     * Retrieve prices matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Colissimo\Shipping\Api\Data\PriceSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete price.
     *
     * @param PriceInterface $price
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(PriceInterface $price);

    /**
     * Delete price by ID.
     *
     * @param int $priceId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($priceId);

    /**
     * Truncate all prices.
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function truncate();
}
