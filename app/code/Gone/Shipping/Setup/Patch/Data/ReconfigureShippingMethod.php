<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Shipping\Setup\Patch\Data;

use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class ReconfigureShippingMethod implements DataPatchInterface
{

    private ModuleDataSetupInterface $moduleDataSetup;
    protected ConfigInterface $_configInterface;
    protected DirectoryList $_dir;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ConfigInterface $configInterface,
        DirectoryList $dir
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->_configInterface = $configInterface;
        $this->_dir = $dir;
    }

    public static function getDependencies() : array
    {
        return [];
    }

    public function getAliases() : array
    {
        return [];
    }

    public function apply() : void
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $baseDir = $this->_dir->getPath("app");
        $owebiaConfig = file_get_contents($baseDir . '/code/Gone/Shipping/Setup/Patch/Data/fixtures/config_owebia.txt');

        // cannot pass by config.xml, data already in bdd
        $configArr = [
            "carriers/owsh1/config" => $owebiaConfig,
            "carriers/owsh1/active" => 1,
            "carriers/owsh1/title" => "Livraison à domicile",
            "carriers/chronorelais/config" => '[{ "destination": "FR", "fees": "{0.5:5.8, 1:6, 2:6.66, 3:6.95, 4:7.5, 5:8.1, 6:8.5, 7:8.72, 8:9.23, 9:9.9, 10:11, 11:12, 12:13, 13:13.5, 14:14, 15:15, 16:16, 17:17, 18:18}" }]',
            "carriers/chronorelais/show_map" => 1,
            "carriers/chronorelais/title" => "Livraison express point relais",
            "carriers/chronorelais/name" => "",
            "general/locale/timezone" => "Europe/Paris",
            "chronorelais/shipping/account_number" => '42946601',
            "chronorelais/shipping/account_pass" => '059769',
        ];

        foreach ($configArr as $configPath => $configValue) {
            $this->_configInterface
                ->saveConfig($configPath, $configValue);
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
