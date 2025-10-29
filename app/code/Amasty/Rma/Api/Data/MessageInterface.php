<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Api\Data;

/**
 * Interface MessageInterface
 */
interface MessageInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const MESSAGE_ID = 'message_id';
    const REQUEST_ID = 'request_id';
    const CREATED_AT = 'created_at';
    const MESSAGE = 'message';
    const NAME = 'name';
    const CUSTOMER_ID = 'customer_id';
    const MANAGER_ID = 'manager_id';
    const IS_SYSTEM = 'is_system';
    const IS_MANAGER = 'is_manager';
    const IS_READ = 'is_read';
    const MESSAGE_FILES = 'message_files';
    /**#@-*/

    /**
     * @param int $messageId
     *
     * @return \Amasty\Rma\Api\Data\MessageInterface
     */
    public function setMessageId($messageId);

    /**
     * @return int
     */
    public function getMessageId();

    /**
     * @param int $requestId
     *
     * @return \Amasty\Rma\Api\Data\MessageInterface
     */
    public function setRequestId($requestId);

    /**
     * @return int
     */
    public function getRequestId();

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $message
     *
     * @return \Amasty\Rma\Api\Data\MessageInterface
     */
    public function setMessage($message);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $name
     *
     * @return \Amasty\Rma\Api\Data\MessageInterface
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param int $customerId
     *
     * @return \Amasty\Rma\Api\Data\MessageInterface
     */
    public function setCustomerId($customerId);

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @param int $managerId
     *
     * @return \Amasty\Rma\Api\Data\MessageInterface
     */
    public function setManagerId($managerId);

    /**
     * @return int
     */
    public function getManagerId();

    /**
     * @param bool $isSystem
     *
     * @return \Amasty\Rma\Api\Data\MessageInterface
     */
    public function setIsSystem($isSystem);

    /**
     * @return bool
     */
    public function isSystem();

    /**
     * @param bool $isManager
     *
     * @return \Amasty\Rma\Api\Data\MessageInterface
     */
    public function setIsManager($isManager);

    /**
     * @return bool
     */
    public function isManager();

    /**
     * @param bool $isRead
     *
     * @return \Amasty\Rma\Api\Data\MessageInterface
     */
    public function setIsRead($isRead);

    /**
     * @return bool
     */
    public function isRead();

    /**
     * @param \Amasty\Rma\Api\Data\MessageFileInterface[] $files
     *
     * @return \Amasty\Rma\Api\Data\MessageInterface
     */
    public function setMessageFiles($files);

    /**
     * @return \Amasty\Rma\Api\Data\MessageFileInterface[]
     */
    public function getMessageFiles();
}
