<?php
/**
 * Colissimo Rule Module
 *
 * @author    Magentix
 * @copyright Copyright © 2019 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Rule\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class UpgradeSchema
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '2.0.0', '<=')) {
            $installer->getConnection()->addColumn(
                $setup->getTable('colissimo_rule'),
                'cart_rule_id',
                [
                    'type' => Table::TYPE_INTEGER,
                    'length' => 10,
                    'nullable' => false,
                    'comment' => 'Cart Rule Id',
                    'default' => 0,
                ]
            );
        }

        if (version_compare($context->getVersion(), '2.1.0', '<=')) {
            $installer->getConnection()->addColumn(
                $setup->getTable('colissimo_rule'),
                'currency_code',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 3,
                    'nullable' => true,
                    'comment' => 'Currency Code',
                    'default' => 'EUR',
                ]
            );
        }

        $installer->endSetup();
    }
}
