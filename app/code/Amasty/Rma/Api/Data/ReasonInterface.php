<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Api\Data;

/**
 * Interface ReasonInterface
 */
interface ReasonInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const REASON_ID = 'reason_id';
    const TITLE = 'title';
    const STATUS = 'status';
    const PAYER = 'payer';
    const POSITION = 'position';
    const STORES = 'stores';
    const LABEL = 'label';
    const IS_DELETED = 'is_deleted';
    /**#@-*/

    /**
     * @param int $reasonId
     *
     * @return \Amasty\Rma\Api\Data\ReasonInterface
     */
    public function setReasonId($reasonId);

    /**
     * @return int
     */
    public function getReasonId();

    /**
     * @param string $title
     *
     * @return \Amasty\Rma\Api\Data\ReasonInterface
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param int $status
     *
     * @return \Amasty\Rma\Api\Data\ReasonInterface
     */
    public function setStatus($status);

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @param int $payer
     *
     * @return \Amasty\Rma\Api\Data\ReasonInterface
     */
    public function setPayer($payer);

    /**
     * @return int
     */
    public function getPayer();

    /**
     * @param int $position
     *
     * @return \Amasty\Rma\Api\Data\ReasonInterface
     */
    public function setPosition($position);

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param \Amasty\Rma\Api\Data\ReasonStoreInterface[]
     *
     * @return \Amasty\Rma\Api\Data\ReasonInterface
     */
    public function setStores($stores);

    /**
     * @return \Amasty\Rma\Api\Data\ReasonStoreInterface[]
     */
    public function getStores();

    /**
     * @param string $label
     *
     * @return \Amasty\Rma\Api\Data\ReasonInterface
     */
    public function setLabel($label);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param bool $isDeleted
     *
     * @return \Amasty\Rma\Api\Data\ReasonInterface
     */
    public function setIsDeleted($isDeleted);

    /**
     * @return bool
     */
    public function getIsDeleted();
}
