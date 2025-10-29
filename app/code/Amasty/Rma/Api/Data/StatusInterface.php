<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Api\Data;

/**
 * Interface StatusInterface
 */
interface StatusInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const STATUS_ID = 'status_id';
    const IS_ENABLED = 'is_enabled';
    const IS_INITIAL = 'is_initial';
    const AUTO_EVENT = 'auto_event';
    const STATE = 'state';
    const GRID = 'grid';
    const PRIORITY = 'priority';
    const TITLE = 'title';
    const COLOR = 'color';
    const LABEL = 'label';
    const STORE = 'store';
    const IS_DELETED = 'is_deleted';
    /**#@-*/

    /**
     * @param int $statusId
     *
     * @return \Amasty\Rma\Api\Data\StatusInterface
     */
    public function setStatusId($statusId);

    /**
     * @return int
     */
    public function getStatusId();

    /**
     * @param bool $isEnabled
     *
     * @return \Amasty\Rma\Api\Data\StatusInterface
     */
    public function setIsEnabled($isEnabled);

    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @param bool $isInitial
     *
     * @return \Amasty\Rma\Api\Data\StatusInterface
     */
    public function setIsInitial($isInitial);

    /**
     * @return bool
     */
    public function isInitial();

    /**
     * @param int $autoEvent
     *
     * @return \Amasty\Rma\Api\Data\StatusInterface
     */
    public function setAutoEvent($autoEvent);

    /**
     * @return int
     */
    public function getAutoEvent();

    /**
     * @param int $state
     *
     * @return \Amasty\Rma\Api\Data\StatusInterface
     */
    public function setState($state);

    /**
     * @return int
     */
    public function getState();

    /**
     * @param int $grid
     *
     * @return \Amasty\Rma\Api\Data\StatusInterface
     */
    public function setGrid($grid);

    /**
     * @return int
     */
    public function getGrid();

    /**
     * @param int $priority
     *
     * @return \Amasty\Rma\Api\Data\StatusInterface
     */
    public function setPriority($priority);

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @param string $title
     *
     * @return \Amasty\Rma\Api\Data\StatusInterface
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $color
     *
     * @return \Amasty\Rma\Api\Data\StatusInterface
     */
    public function setColor($color);

    /**
     * @return string
     */
    public function getColor();

    /**
     * @param string $label
     *
     * @return \Amasty\Rma\Api\Data\StatusInterface
     */
    public function setLabel($label);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param \Amasty\Rma\Api\Data\StatusStoreInterface $store
     *
     * @return \Amasty\Rma\Api\Data\StatusInterface
     */
    public function setStoreData($store);

    /**
     * @return \Amasty\Rma\Api\Data\StatusStoreInterface
     */
    public function getStoreData();

    /**
     * @param bool $isDeleted
     *
     * @return \Amasty\Rma\Api\Data\StatusStoreInterface
     */
    public function setIsDeleted($isDeleted);

    /**
     * @return bool
     */
    public function getIsDeleted();
}
