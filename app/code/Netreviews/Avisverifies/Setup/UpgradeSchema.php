<?php
namespace Netreviews\Avisverifies\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\DB\Ddl\Table;



class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Module install code
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $connection = $setup->getConnection();

        if (version_compare($context->getVersion(), '3.9.2', '<=')) {

            $table_sales_order = $setup->getTable('sales_order');
            $table_quote = $setup->getTable('quote');

            // Declare data
            $column3 = array(
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'default' => null,
                'comment' => 'AV_send_review'
            );

            $connection->addColumn($table_sales_order, 'av_send_review', $column3);
            $connection->addColumn($table_quote, 'av_send_review', $column3);
        }

        $setup->endSetup();
    }
}
