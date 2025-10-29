<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Api\Data;

/**
 * Interface ConditionStoreInterface
 */
interface StatusStoreInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const STATUS_STORE_ID = 'status_store_id';
    const STATUS_ID = 'status_id';
    const STORE_ID = 'store_id';
    const LABEL = 'label';
    const DESCRIPTION = 'description';
    const SEND_EMAIL_TO_CUSTOMER = 'send_email_to_customer';
    const CUSTOMER_EMAIL_TEMPLATE = 'customer_email_template';
    const CUSTOMER_CUSTOM_TEXT = 'customer_custom_text';
    const SEND_EMAIL_TO_ADMIN = 'send_email_to_admin';
    const ADMIN_EMAIL_TEMPLATE = 'admin_email_template';
    const ADMIN_CUSTOM_TEXT = 'admin_custom_text';
    const SEND_TO_CHAT = 'send_to_chat';
    const CHAT_MESSAGE = 'chat_message';
    /**#@-*/

    /**
     * @param int $statusStoreId
     *
     * @return \Amasty\Rma\Api\Data\StatusStoreInterface
     */
    public function setStatusStoreId($statusStoreId);

    /**
     * @return int
     */
    public function getStatusStoreId();

    /**
     * @param int $statusId
     *
     * @return \Amasty\Rma\Api\Data\StatusStoreInterface
     */
    public function setStatusId($statusId);

    /**
     * @return int
     */
    public function getStatusId();

    /**
     * @param int $storeId
     *
     * @return \Amasty\Rma\Api\Data\StatusStoreInterface
     */
    public function setStoreId($storeId);

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param string $label
     *
     * @return \Amasty\Rma\Api\Data\StatusStoreInterface
     */
    public function setLabel($label);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $description
     *
     * @return \Amasty\Rma\Api\Data\StatusStoreInterface
     */
    public function setDescription($description);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param bool $send
     *
     * @return \Amasty\Rma\Api\Data\StatusStoreInterface
     */
    public function setIsSendEmailToCustomer($send);

    /**
     * @return bool
     */
    public function isSendEmailToCustomer();

    /**
     * @param int $emailTemplate
     *
     * @return \Amasty\Rma\Api\Data\StatusStoreInterface
     */
    public function setCustomerEmailTemplate($emailTemplate);

    /**
     * @return int
     */
    public function getCustomerEmailTemplate();

    /**
     * @param string $customText
     *
     * @return \Amasty\Rma\Api\Data\StatusStoreInterface
     */
    public function setCustomerCustomText($customText);

    /**
     * @return string
     */
    public function getCustomerCustomText();

    /**
     * @param bool $send
     *
     * @return \Amasty\Rma\Api\Data\StatusStoreInterface
     */
    public function setIsSendEmailToAdmin($send);

    /**
     * @return bool
     */
    public function isSendEmailToAdmin();

    /**
     * @param int $emailTemplate
     *
     * @return \Amasty\Rma\Api\Data\StatusStoreInterface
     */
    public function setAdminEmailTemplate($emailTemplate);

    /**
     * @return int
     */
    public function getAdminEmailTemplate();

    /**
     * @param string $customText
     *
     * @return \Amasty\Rma\Api\Data\StatusStoreInterface
     */
    public function setAdminCustomText($customText);

    /**
     * @return string
     */
    public function getAdminCustomText();

    /**
     * @param bool $send
     *
     * @return \Amasty\Rma\Api\Data\StatusStoreInterface
     */
    public function setIsSendToChat($send);

    /**
     * @return bool
     */
    public function isSendToChat();

    /**
     * @param string $chatMessage
     *
     * @return \Amasty\Rma\Api\Data\StatusStoreInterface
     */
    public function setChatMessage($chatMessage);

    /**
     * @return string
     */
    public function getChatMessage();
}
