<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Subligraphy\Api;

use Gone\Subligraphy\Api\Data\CertificateInterface;
use Gone\Subligraphy\Api\Data\CertificateSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface CertificateRepositoryInterface
{

    /**
     * Save Certificate
     * @param CertificateInterface $certificate
     * @return CertificateInterface
     * @throws LocalizedException
     */
    public function save(
        CertificateInterface $certificate
    );

    /**
     * Retrieve Certificate
     * @param string $certificateId
     * @return CertificateInterface
     * @throws LocalizedException
     */
    public function get($certificateId);

    /**
     * Retrieve Certificate matching the specified criteria.
     * @param SearchCriteriaInterface $searchCriteria
     * @return CertificateSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Certificate
     * @param CertificateInterface $certificate
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(
        CertificateInterface $certificate
    );

    /**
     * Delete Certificate by ID
     * @param string $certificateId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($certificateId);
}
