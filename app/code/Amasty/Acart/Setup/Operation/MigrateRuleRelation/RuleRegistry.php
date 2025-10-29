<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Abandoned Cart Email Base for Magento 2
 */

namespace Amasty\Acart\Setup\Operation\MigrateRuleRelation;

class RuleRegistry
{
    public const STORE_IDS = 'storeIds';
    public const CUSTOMER_GROUP_IDS = 'customerGroupIds';

    /**
     * @var array
     */
    private $registry = [];

    public function registry($key)
    {
        if (isset($this->registry[$key])) {
            return $this->registry[$key];
        }

        return null;
    }

    public function register($key, $value, $graceful = false)
    {
        if (isset($this->registry[$key])) {
            if ($graceful) {
                return;
            }
            throw new \RuntimeException('Registry key "' . $key . '" already exists');
        }
        $this->registry[$key] = $value;
    }
}
