<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Api\Data;

/**
 * Interface ReturnRulesWebsitesInterface
 */
interface ReturnRulesWebsitesInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const RULE_WEBSITE_ID = 'rule_website_id';
    const RULE_ID = 'rule_id';
    const WEBSITE_ID = 'website_id';
    /**#@-*/

    /**
     * @param int $id
     *
     * @return \Amasty\Rma\Api\Data\ReturnRulesWebsitesInterface
     */
    public function setRuleWebsiteId($id);

    /**
     * @return int
     */
    public function getRuleWebsiteId();

    /**
     * @param int $id
     *
     * @return \Amasty\Rma\Api\Data\ReturnRulesWebsitesInterface
     */
    public function setRuleId($id);

    /**
     * @return int
     */
    public function getRuleId();

    /**
     * @param int $id
     *
     * @return \Amasty\Rma\Api\Data\ReturnRulesWebsitesInterface
     */
    public function setWebsiteId($id);

    /**
     * @return int
     */
    public function getWebsiteId();
}
