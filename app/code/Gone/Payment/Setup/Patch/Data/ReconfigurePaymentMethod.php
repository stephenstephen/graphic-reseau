<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Payment\Setup\Patch\Data;

use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class ReconfigurePaymentMethod implements DataPatchInterface
{
    private ModuleDataSetupInterface $moduleDataSetup;
    protected ConfigInterface $_configInterface;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ConfigInterface $configInterface
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->_configInterface = $configInterface;
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

        // cannot pass by config.xml, data already in bdd
        $configArr = [
            'systempay/general/shop_name' => 'Graphic Reseau',
            'payment/systempay_standard/title' => 'Paiement par Carte Bancaire',
            'payment/systempay_paypal/active' => 1,
            'payment/systempay_paypal/sort_order' => 3,
            'payment/systempay_paypal/title' => 'Paypal',
//            "payment/systempay_oney/active" => 1,
//            "payment/systempay_oney/sort_order" => 2,
//            "payment/systempay_oney/title" => "Paiement en 3 ou 4 fois avec oney",
            'payment/banktransfer/active' => 1,
            'payment/banktransfer/title' => 'Virement bancaire',
            'payment/banktransfer/order_status' => 'pending_bankpayment',
            'payment/banktransfer/sort_order' => 4,
            'payment/vads/site_id' => 71663161,
            'payment/vads/key_test' => 1404075383780729,
            'payment/vads/key_prod' => 9114864292110136,

        ];

        foreach ($configArr as $configPath => $configValue) {
            $this->_configInterface
                ->saveConfig($configPath, $configValue);
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
