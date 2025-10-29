<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Api\Data;

interface ReturnRulesInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const ID = 'id';
    const NAME = 'name';
    const STATUS = 'status';
    const PRIORITY = 'priority';
    const DEFAULT_RESOLUTION = 'default_resolution';
    const CONDITIONS_SERIALIZED = 'conditions_serialized';
    const CUSTOMER_GROUPS = 'customer_groups';
    const WEBSITES = 'websites';
    const RESOLUTIONS = 'resolutions';
    /**#@-*/

    /**
     * @param int $id
     *
     * @return \Amasty\Rma\Api\Data\ReturnRulesInterface
     */
    public function setRuleId($id);

    /**
     * @return int
     */
    public function getRuleId();

    /**
     * @param string $name
     *
     * @return \Amasty\Rma\Api\Data\ReturnRulesInterface
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param int $status
     *
     * @return \Amasty\Rma\Api\Data\ReturnRulesInterface
     */
    public function setStatus($status);

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @param int $priority
     *
     * @return \Amasty\Rma\Api\Data\ReturnRulesInterface
     */
    public function setPriority($priority);

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @param int $period
     *
     * @return \Amasty\Rma\Api\Data\ReturnRulesInterface
     */
    public function setDefaultResolution($period);

    /**
     * @return int
     */
    public function getDefaultResolution();

    /**
     * @param string $conditions
     *
     * @return \Amasty\Rma\Api\Data\ReturnRulesInterface
     */
    public function setConditionsSerialized($conditions);

    /**
     * @return string
     */
    public function getConditionsSerialized();

    /**
     * @param \Amasty\Rma\Api\Data\ReturnRulesCustomerGroupsInterface[] $groups
     *
     * @return \Amasty\Rma\Api\Data\ReturnRulesInterface
     */
    public function setCustomerGroups($groups);

    /**
     * @return \Amasty\Rma\Api\Data\ReturnRulesCustomerGroupsInterface[]
     */
    public function getCustomerGroups();

    /**
     * @param \Amasty\Rma\Api\Data\ReturnRulesWebsitesInterface[] $websites
     *
     * @return \Amasty\Rma\Api\Data\ReturnRulesInterface
     */
    public function setWebsites($websites);

    /**
     * @return \Amasty\Rma\Api\Data\ReturnRulesWebsitesInterface[]
     */
    public function getWebsites();

    /**
     * @param \Amasty\Rma\Api\Data\ReturnRulesResolutionsInterface[] $resolutions
     *
     * @return \Amasty\Rma\Api\Data\ReturnRulesInterface
     */
    public function setResolutions($resolutions);

    /**
     * @return \Amasty\Rma\Api\Data\ReturnRulesResolutionsInterface
     */
    public function getResolutions();
}
