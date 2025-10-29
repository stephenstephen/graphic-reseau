<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Api\Data;

/**
 * Interface MessageFileInterface
 */
interface MessageFileInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const MESSAGE_FILE_ID = 'message_file_id';
    const MESSAGE_ID = 'message_id';
    const FILEPATH = 'filepath';
    const FILENAME = 'filename';
    const URL_HASH = 'url_hash';
    /**#@-*/

    /**
     * @param int $messageFileId
     *
     * @return \Amasty\Rma\Api\Data\MessageFileInterface
     */
    public function setMessageFileId($messageFileId);

    /**
     * @return int
     */
    public function getMessageFileId();

    /**
     * @param int $messageId
     *
     * @return \Amasty\Rma\Api\Data\MessageFileInterface
     */
    public function setMessageId($messageId);

    /**
     * @return int
     */
    public function getMessageId();

    /**
     * @param string $filepath
     *
     * @return \Amasty\Rma\Api\Data\MessageFileInterface
     */
    public function setFilepath($filepath);

    /**
     * @return string
     */
    public function getFilepath();

    /**
     * @param string $filename
     *
     * @return \Amasty\Rma\Api\Data\MessageFileInterface
     */
    public function setFilename($filename);

    /**
     * @return string
     */
    public function getFilename();

    /**
     * @param string $urlHash
     *
     * @return \Amasty\Rma\Api\Data\MessageFileInterface
     */
    public function setUrlHash($urlHash);

    /**
     * @return string
     */
    public function getUrlHash();
}
