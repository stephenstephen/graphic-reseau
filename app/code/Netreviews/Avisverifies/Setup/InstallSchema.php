<?php
namespace Netreviews\Avisverifies\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Netreviews\Avisverifies\Helper\Data;

class InstallSchema implements InstallSchemaInterface
{
    protected $helperData;
    public function __construct(
        Data $helperData
    ) {
        $this->helperData = $helperData;
    }

    /**
     * Module install code
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $connection = $setup->getConnection();
        $table_sales_order = $setup->getTable('sales_order');
        $column1 = array(
            'type' => Table::TYPE_SMALLINT,
            'length' => 4,
            'nullable' => true,
            'default' => 0,
            'comment' => 'AV Flag'
        );
        $column2 = array(
            'type' => Table::TYPE_TEXT,
            'length' => 32,
            'nullable' => true,
            'default' => null,
            'comment' => 'AV_horodate_get'
        );
        $connection->addColumn($table_sales_order, 'av_flag', $column1);
        $connection->addColumn($table_sales_order, 'av_horodate_get', $column2);

        // Flag all orders to 1
        $timestamp = strtotime(date('Y-m-d H:i:s'));
            //Update Data into table
        $sqlOrder = "Update $table_sales_order Set av_flag = null, av_horodate_get = $timestamp";
        $connection->query($sqlOrder);
        $setup->endSetup();
    }
}
