<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Api\Data;

/**
 * Interface GuestCreateRequestInterface
 */
interface GuestCreateRequestInterface
{
    const CREATE_ID = 'create_id';
    const ORDER_ID = 'order_id';
    const BILLING_LAST_NAME = 'billing_last_name';
    const EMAIL = 'email';
    const ZIP = 'zip';
    const SECRET_CODE = 'secret_code';

    /**
     * @param int $createId
     *
     * @return \Amasty\Rma\Api\Data\GuestCreateRequestInterface
     */
    public function setCreateId($createId);

    /**
     * @return int
     */
    public function getCreateId();

    /**
     * @param int $orderId
     *
     * @return \Amasty\Rma\Api\Data\GuestCreateRequestInterface
     */
    public function setOrderId($orderId);

    /**
     * @return int
     */
    public function getOrderId();

    /**
     * @param string $billingLastName
     *
     * @return \Amasty\Rma\Api\Data\GuestCreateRequestInterface
     */
    public function setBillingLastName($billingLastName);

    /**
     * @return string
     */
    public function getBillingLastName();

    /**
     * @param string $email
     *
     * @return \Amasty\Rma\Api\Data\GuestCreateRequestInterface
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $zip
     *
     * @return \Amasty\Rma\Api\Data\GuestCreateRequestInterface
     */
    public function setZip($zip);

    /**
     * @return string
     */
    public function getZip();

    /**
     * @param string $secretCode
     *
     * @return \Amasty\Rma\Api\Data\GuestCreateRequestInterface
     */
    public function setSecretCode($secretCode);

    /**
     * @return string
     */
    public function getSecretCode();
}
