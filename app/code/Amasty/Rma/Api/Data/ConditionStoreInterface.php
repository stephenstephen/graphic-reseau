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
interface ConditionStoreInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const CONDITION_STORE_ID = 'condition_store_id';
    const CONDITION_ID = 'condition_id';
    const STORE_ID = 'store_id';
    const LABEL = 'label';
    /**#@-*/

    /**
     * @param int $conditionStoreId
     *
     * @return \Amasty\Rma\Api\Data\ConditionStoreInterface
     */
    public function setConditionStoreId($conditionStoreId);

    /**
     * @return int
     */
    public function getConditionStoreId();

    /**
     * @param int $conditionId
     *
     * @return \Amasty\Rma\Api\Data\ConditionStoreInterface
     */
    public function setConditionId($conditionId);

    /**
     * @return int
     */
    public function getConditionId();

    /**
     * @param int $storeId
     *
     * @return \Amasty\Rma\Api\Data\ConditionStoreInterface
     */
    public function setStoreId($storeId);

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param string $label
     *
     * @return \Amasty\Rma\Api\Data\ConditionStoreInterface
     */
    public function setLabel($label);

    /**
     * @return string
     */
    public function getLabel();
}
