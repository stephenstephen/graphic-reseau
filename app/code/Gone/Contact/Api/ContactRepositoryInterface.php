<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Contact\Api;

use Gone\Contact\Api\Data\ContactInterface;
use Gone\Contact\Api\Data\ContactSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface ContactRepositoryInterface
{

    /**
     * Save Contact
     * @param ContactInterface $contact
     * @return ContactInterface
     * @throws LocalizedException
     */
    public function save(
        ContactInterface $contact
    );

    /**
     * Retrieve Contact
     * @param string $contactId
     * @return ContactInterface
     * @throws LocalizedException
     */
    public function get($contactId);

    /**
     * Retrieve Contact matching the specified criteria.
     * @param SearchCriteriaInterface $searchCriteria
     * @return ContactSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Contact
     * @param ContactInterface $contact
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(
        ContactInterface $contact
    );

    /**
     * Delete Contact by ID
     * @param string $contactId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($contactId);
}
