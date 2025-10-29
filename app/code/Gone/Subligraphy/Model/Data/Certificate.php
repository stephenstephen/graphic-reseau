<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Subligraphy\Model\Data;

use Gone\Subligraphy\Api\Data\CertificateExtensionInterface;
use Gone\Subligraphy\Api\Data\CertificateInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

class Certificate extends AbstractExtensibleObject implements CertificateInterface
{

    /**
     * Get certificate_id
     * @return string|null
     */
    public function getCertificateId()
    {
        return $this->_get(self::CERTIFICATE_ID);
    }

    /**
     * Set certificate_id
     * @param string $certificateId
     * @return CertificateInterface
     */
    public function setCertificateId($certificateId)
    {
        return $this->setData(self::CERTIFICATE_ID, $certificateId);
    }

    /**
     * Get customer_id
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->_get(self::CUSTOMER_ID);
    }

    /**
     * Set customer_id
     * @param string $customerId
     * @return CertificateInterface
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return CertificateExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param CertificateExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        CertificateExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get image
     * @return string|null
     */
    public function getImage()
    {
        return $this->_get(self::IMAGE);
    }

    /**
     * Set image
     * @param string $image
     * @return CertificateInterface
     */
    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * Get title
     * @return string|null
     */
    public function getTitle()
    {
        return $this->_get(self::TITLE);
    }

    /**
     * Set title
     * @param string $title
     * @return CertificateInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Get year
     * @return string|null
     */
    public function getYear()
    {
        return $this->_get(self::YEAR);
    }

    /**
     * Set year
     * @param string $year
     * @return CertificateInterface
     */
    public function setYear($year)
    {
        return $this->setData(self::YEAR, $year);
    }

    /**
     * Get width
     * @return string|null
     */
    public function getWidth()
    {
        return $this->_get(self::WIDTH);
    }

    /**
     * Set width
     * @param string $width
     * @return CertificateInterface
     */
    public function setWidth($width)
    {
        return $this->setData(self::WIDTH, $width);
    }

    /**
     * Get height
     * @return string|null
     */
    public function getHeight()
    {
        return $this->_get(self::HEIGHT);
    }

    /**
     * Set height
     * @param string $height
     * @return CertificateInterface
     */
    public function setHeight($height)
    {
        return $this->setData(self::HEIGHT, $height);
    }

    /**
     * Get author
     * @return string|null
     */
    public function getAuthor()
    {
        return $this->_get(self::AUTHOR);
    }

    /**
     * Set author
     * @param string $author
     * @return CertificateInterface
     */
    public function setAuthor($author)
    {
        return $this->setData(self::AUTHOR, $author);
    }

    /**
     * Get manufacturer
     * @return string|null
     */
    public function getManufacturer()
    {
        return $this->_get(self::MANUFACTURER);
    }

    /**
     * Set manufacturer
     * @param string $manufacturer
     * @return CertificateInterface
     */
    public function setManufacturer($manufacturer)
    {
        return $this->setData(self::MANUFACTURER, $manufacturer);
    }

    /**
     * Get publisher
     * @return string|null
     */
    public function getPublisher()
    {
        return $this->_get(self::PUBLISHER);
    }

    /**
     * Set publisher
     * @param string $publisher
     * @return CertificateInterface
     */
    public function setPublisher($publisher)
    {
        return $this->setData(self::PUBLISHER, $publisher);
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
     * @return CertificateInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get number
     * @return string|null
     */
    public function getNumber()
    {
        return $this->_get(self::NUMBER);
    }

    /**
     * Set number
     * @param string $number
     * @return CertificateInterface
     */
    public function setNumber($number)
    {
        return $this->setData(self::NUMBER, $number);
    }

    /**
     * Get count
     * @return string|null
     */
    public function getCount()
    {
        return $this->_get(self::COUNT);
    }

    /**
     * Set count
     * @param string $count
     * @return CertificateInterface
     */
    public function setCount($count)
    {
        return $this->setData(self::COUNT, $count);
    }

    /**
     * Get filename
     * @return string|null
     */
    public function getFilename()
    {
        return $this->_get(self::FILENAME);
    }

    /**
     * Set filename
     * @param string $filename
     * @return CertificateInterface
     */
    public function setFilename($filename)
    {
        return $this->setData(self::FILENAME, $filename);
    }
}
