<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Api\Data;

interface ReturnRulesCustomerGroupsInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const RULE_CUSTOMER_GROUP_ID = 'rule_customer_group_id';
    const RULE_ID = 'rule_id';
    const CUSTOMER_GROUP_ID = 'customer_group_id';
    /**#@-*/

    /**
     * @param int $id
     *
     * @return \Amasty\Rma\Api\Data\ReturnRulesCustomerGroupsInterface
     */
    public function setRuleCustomerGroupId($id);

    /**
     * @return int
     */
    public function getRuleCustomerGroupId();

    /**
     * @param int $id
     *
     * @return \Amasty\Rma\Api\Data\ReturnRulesCustomerGroupsInterface
     */
    public function setRuleId($id);

    /**
     * @return int
     */
    public function getRuleId();

    /**
     * @param int $id
     *
     * @return \Amasty\Rma\Api\Data\ReturnRulesCustomerGroupsInterface
     */
    public function setCustomerGroupId($id);

    /**
     * @return int
     */
    public function getCustomerGroupId();
}
