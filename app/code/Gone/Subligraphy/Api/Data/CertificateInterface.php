<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Subligraphy\Api\Data;

interface CertificateInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const CREATED_AT = 'created_at';
    const CUSTOMER_ID = 'customer_id';
    const PUBLISHER = 'publisher';
    const YEAR = 'year';
    const CERTIFICATE_ID = 'certificate_id';
    const COUNT = 'count';
    const IMAGE = 'image';
    const NUMBER = 'number';
    const TITLE = 'title';
    const AUTHOR = 'author';
    const WIDTH = 'width';
    const MANUFACTURER = 'manufacturer';
    const HEIGHT = 'height';
    const FILENAME = 'filename';

    /**
     * Get certificate_id
     * @return string|null
     */
    public function getCertificateId();

    /**
     * Set certificate_id
     * @param string $certificateId
     * @return \Gone\Subligraphy\Api\Data\CertificateInterface
     */
    public function setCertificateId($certificateId);

    /**
     * Get customer_id
     * @return string|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param string $customerId
     * @return \Gone\Subligraphy\Api\Data\CertificateInterface
     */
    public function setCustomerId($customerId);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Gone\Subligraphy\Api\Data\CertificateExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Gone\Subligraphy\Api\Data\CertificateExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Gone\Subligraphy\Api\Data\CertificateExtensionInterface $extensionAttributes
    );

    /**
     * Get image
     * @return string|null
     */
    public function getImage();

    /**
     * Set image
     * @param string $image
     * @return \Gone\Subligraphy\Api\Data\CertificateInterface
     */
    public function setImage($image);

    /**
     * Get title
     * @return string|null
     */
    public function getTitle();

    /**
     * Set title
     * @param string $title
     * @return \Gone\Subligraphy\Api\Data\CertificateInterface
     */
    public function setTitle($title);

    /**
     * Get year
     * @return string|null
     */
    public function getYear();

    /**
     * Set year
     * @param string $year
     * @return \Gone\Subligraphy\Api\Data\CertificateInterface
     */
    public function setYear($year);

    /**
     * Get width
     * @return string|null
     */
    public function getWidth();

    /**
     * Set width
     * @param string $width
     * @return \Gone\Subligraphy\Api\Data\CertificateInterface
     */
    public function setWidth($width);

    /**
     * Get height
     * @return string|null
     */
    public function getHeight();

    /**
     * Set height
     * @param string $height
     * @return \Gone\Subligraphy\Api\Data\CertificateInterface
     */
    public function setHeight($height);

    /**
     * Get author
     * @return string|null
     */
    public function getAuthor();

    /**
     * Set author
     * @param string $author
     * @return \Gone\Subligraphy\Api\Data\CertificateInterface
     */
    public function setAuthor($author);

    /**
     * Get manufacturer
     * @return string|null
     */
    public function getManufacturer();

    /**
     * Set manufacturer
     * @param string $manufacturer
     * @return \Gone\Subligraphy\Api\Data\CertificateInterface
     */
    public function setManufacturer($manufacturer);

    /**
     * Get publisher
     * @return string|null
     */
    public function getPublisher();

    /**
     * Set publisher
     * @param string $publisher
     * @return \Gone\Subligraphy\Api\Data\CertificateInterface
     */
    public function setPublisher($publisher);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Gone\Subligraphy\Api\Data\CertificateInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get number
     * @return string|null
     */
    public function getNumber();

    /**
     * Set number
     * @param string $number
     * @return \Gone\Subligraphy\Api\Data\CertificateInterface
     */
    public function setNumber($number);

    /**
     * Get count
     * @return string|null
     */
    public function getCount();

    /**
     * Set count
     * @param string $count
     * @return \Gone\Subligraphy\Api\Data\CertificateInterface
     */
    public function setCount($count);

    /**
     * Get filename
     * @return string|null
     */
    public function getFilename();

    /**
     * Set filename
     * @param string $filename
     * @return \Gone\Subligraphy\Api\Data\CertificateInterface
     */
    public function setFilename($filename);
}
