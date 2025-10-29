<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Setup\Setup\Patch\Data;

use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class SetupDefaultBackOrdersOption implements DataPatchInterface
{

    protected ConfigInterface $_configInterface;
    private ModuleDataSetupInterface $moduleDataSetup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ConfigInterface $configInterface
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->_configInterface = $configInterface;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        //cannot pass by config.xml, data already in bdd
        $configArr = [
            'cataloginventory/item_options/backorders' => 1
        ];

        foreach ($configArr as $configPath => $configValue) {
            $this->_configInterface
                ->saveConfig($configPath, $configValue);
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
