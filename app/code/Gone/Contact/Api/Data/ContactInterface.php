<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Contact\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ContactInterface extends ExtensibleDataInterface
{

    public const CREATED_AT = 'created_at';
    public const CONTACT_ID = 'contact_id';
    public const CONTACT_NAME = 'contact_name';
    public const CONTACT_REQUEST = 'contact_request';
    public const CONTACT_EMAIL = 'contact_email';
    public const CONTACT_COMPANY = 'contact_company';
    public const CONTACT_PHONE = 'contact_phone';
    public const CONTACT_STORE_ID = 'contact_store_id';

    /**
     * Get contact_id
     * @return string|null
     */
    public function getContactId();

    /**
     * Set contact_id
     * @param string $contactId
     * @return \Gone\Contact\Api\Data\ContactInterface
     */
    public function setContactId($contactId);

    /**
     * Get contact_name
     * @return string|null
     */
    public function getContactName();

    /**
     * Set contact_name
     * @param string $contactName
     * @return \Gone\Contact\Api\Data\ContactInterface
     */
    public function setContactName($contactName);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Gone\Contact\Api\Data\ContactExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Gone\Contact\Api\Data\ContactExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Gone\Contact\Api\Data\ContactExtensionInterface $extensionAttributes
    );

    /**
     * Get contact_email
     * @return string|null
     */
    public function getContactEmail();

    /**
     * Set contact_email
     * @param string $contactEmail
     * @return \Gone\Contact\Api\Data\ContactInterface
     */
    public function setContactEmail($contactEmail);

    /**
     * Get contact_company
     * @return string|null
     */
    public function getContactCompany();

    /**
     * Set contact_company
     * @param string $contactCompany
     * @return \Gone\Contact\Api\Data\ContactInterface
     */
    public function setContactCompany($contactCompany);

    /**
     * Get contact_phone
     * @return string|null
     */
    public function getContactPhone();

    /**
     * Set contact_phone
     * @param string $contactPhone
     * @return \Gone\Contact\Api\Data\ContactInterface
     */
    public function setContactPhone($contactPhone);

    /**
     * Get contact_request
     * @return string|null
     */
    public function getContactRequest();

    /**
     * Set contact_request
     * @param string $contactRequest
     * @return \Gone\Contact\Api\Data\ContactInterface
     */
    public function setContactRequest($contactRequest);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Gone\Contact\Api\Data\ContactInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get contact_store_id
     * @return string|null
     */
    public function getContactStoreId();

    /**
     * Set contact_store_id
     * @param string $contactStoreId
     * @return \Gone\Contact\Api\Data\ContactInterface
     */
    public function setContactStoreId($contactStoreId);
}
