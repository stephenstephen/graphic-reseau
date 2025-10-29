<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Contact\Model\Data;

use Gone\Contact\Api\Data\ContactExtensionInterface;
use Gone\Contact\Api\Data\ContactInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

class Contact extends AbstractExtensibleObject implements ContactInterface
{

    /**
     * Get contact_id
     * @return string|null
     */
    public function getContactId()
    {
        return $this->_get(self::CONTACT_ID);
    }

    /**
     * Set contact_id
     * @param string $contactId
     * @return ContactInterface
     */
    public function setContactId($contactId)
    {
        return $this->setData(self::CONTACT_ID, $contactId);
    }

    /**
     * Get contact_name
     * @return string|null
     */
    public function getContactName()
    {
        return $this->_get(self::CONTACT_NAME);
    }

    /**
     * Set contact_name
     * @param string $contactName
     * @return ContactInterface
     */
    public function setContactName($contactName)
    {
        return $this->setData(self::CONTACT_NAME, $contactName);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return ContactExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param ContactExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        ContactExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get contact_email
     * @return string|null
     */
    public function getContactEmail()
    {
        return $this->_get(self::CONTACT_EMAIL);
    }

    /**
     * Set contact_email
     * @param string $contactEmail
     * @return ContactInterface
     */
    public function setContactEmail($contactEmail)
    {
        return $this->setData(self::CONTACT_EMAIL, $contactEmail);
    }

    /**
     * Get contact_phone
     * @return string|null
     */
    public function getContactPhone()
    {
        return $this->_get(self::CONTACT_PHONE);
    }

    /**
     * Set contact_phone
     * @param string $contactPhone
     * @return ContactInterface
     */
    public function setContactPhone($contactPhone)
    {
        return $this->setData(self::CONTACT_PHONE, $contactPhone);
    }

    /**
     * Get contact_phone
     * @return string|null
     */
    public function getContactCompany()
    {
        return $this->_get(self::CONTACT_COMPANY);
    }

    /**
     * Set contact_phone
     * @param string $contactCompany
     * @return ContactInterface
     */
    public function setContactCompany($contactCompany)
    {
        return $this->setData(self::CONTACT_COMPANY, $contactCompany);
    }

    /**
     * Get contact_request
     * @return string|null
     */
    public function getContactRequest()
    {
        return $this->_get(self::CONTACT_REQUEST);
    }

    /**
     * Set contact_request
     * @param string $contactRequest
     * @return ContactInterface
     */
    public function setContactRequest($contactRequest)
    {
        return $this->setData(self::CONTACT_REQUEST, $contactRequest);
    }

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created_at
     * @param string $createdAt
     * @return ContactInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get contact_store_id
     * @return string|null
     */
    public function getContactStoreId()
    {
        return $this->_get(self::CONTACT_STORE_ID);
    }

    /**
     * Set contact_store_id
     * @param string $contactStoreId
     * @return ContactInterface
     */
    public function setContactStoreId($contactStoreId)
    {
        return $this->setData(self::CONTACT_STORE_ID, $contactStoreId);
    }
}
