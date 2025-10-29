<?php

namespace AtooSync\GesCom\Setup;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;


class UpgradeSchema implements  UpgradeSchemaInterface

{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $connection = $setup->getConnection();
        
        // 
        if (version_compare($context->getVersion(), '20.6.0.0', '<')) {
            /*
                Table orders
                2 champs ajoutés
                    + atoosync_transfered +  Index
                    + atoosync_number +  Index
            */
            
            // Table Order champ atoosync_transfered
            $tableName = $setup->getTable('sales_order');
            $columnName  = 'atoosync_transfered';
            if ($connection->tableColumnExists($tableName, $columnName) === false) {
                
                $connection->addColumn($tableName, $columnName, array(
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 1,
                    'nullable' => false,
                    'default' => 0,
                    'comment' => 'Order transfered in invoicing application by Atoo-Sync GesCom',
                ));
            }
            $connection->addIndex($tableName, $columnName, $columnName);
            
            // Table Order champ atoosync_number
            $tableName = $setup->getTable('sales_order');
            $columnName  = 'atoosync_number';
            if ($connection->tableColumnExists($tableName, $columnName) === false) {
                
                $connection->addColumn($tableName, $columnName, array(
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => false,
                    'default' => '',
                    'comment' => 'Order number in invoicing application',
                ));
            }
            
             // Table Order champ atoosync_tracking_number
            $tableName = $setup->getTable('sales_order');
            $columnName  = 'atoosync_tracking_number';
            if ($connection->tableColumnExists($tableName, $columnName) === false) {
                
                $connection->addColumn($tableName, $columnName, array(
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => false,
                    'default' => '',
                    'comment' => 'Tracking number in invoicing application',
                ));
            }
            /*
                Table category
                1 champ ajouté
                    + atoosync_key + Index
            */
            $tableName = $setup->getTable('catalog_category_entity');
            $columnName  = 'atoosync_key';
            if ($connection->tableColumnExists($tableName, $columnName) === false) {
                
                $connection->addColumn($tableName, $columnName, array(
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => false,
                    'default' => '',
                    'comment' => 'Category key in invoicing application',
                ));
            }
            $connection->addIndex($tableName, $columnName, $columnName);
            
            
            /*
                Table image
                1 champs ajouté
                    + atoosync_image_id + Index
            */
            $tableName = $setup->getTable('catalog_product_entity_media_gallery');
            $columnName  = 'atoosync_image_id';
            if ($connection->tableColumnExists($tableName, $columnName) === false) {
                
                $connection->addColumn($tableName, $columnName, array(
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => false,
                    'default' => '',
                    'comment' => 'Category key in invoicing application',
                ));
            }
            $connection->addIndex($tableName, $columnName, $columnName);
            
            /*
                Table group
                1 champs ajouté
                    + atoosync_key
            */
            $tableName = $setup->getTable('customer_group');
            $columnName  = 'atoosync_key';
            if ($connection->tableColumnExists($tableName, $columnName) === false) {
                
                $connection->addColumn($tableName, $columnName, array(
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => false,
                    'default' => '',
                    'comment' => 'key identifier for group came from Atoo-Sync GesCom',
                ));
            }
            
            
            /*
                Table customer
                1 champs ajouté
                    + atoosync_account + Index
            */
            $tableName = $setup->getTable('customer_address_entity');
            $columnName  = 'atoosync_key';
            if ($connection->tableColumnExists($tableName, $columnName) === false) {
                
                $connection->addColumn($tableName, $columnName, array(
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => false,
                    'default' => '',
                    'comment' => 'Customer number in invoicing application',
                ));
            }
            $connection->addIndex($tableName, $columnName, $columnName);
            
            /*
                Table address
                1 champ ajouté
                    + atoosync_key + Index
            */
            $tableName = $setup->getTable('customer_entity');
            $columnName  = 'atoosync_account';
            if ($connection->tableColumnExists($tableName, $columnName) === false) {
                
                $connection->addColumn($tableName, $columnName, array(
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => false,
                    'default' => '',
                    'comment' => 'Customer number in invoicing application',
                ));
            }
            $connection->addIndex($tableName, $columnName, $columnName);
            
             /*
                Table PRoduct
                1 champ ajouté
                    + atoosync_key + Index
            */
            $tableName = $setup->getTable('catalog_product_entity');
            $columnName  = 'atoosync_key';
            if ($connection->tableColumnExists($tableName, $columnName) === false) {
                
                $connection->addColumn($tableName, $columnName, array(
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => false,
                    'default' => '',
                    'comment' => 'Product key in invoicing application',
                ));
            }
            $connection->addIndex($tableName, $columnName, $columnName);
            $columnName  = 'atoosync_gamme_key';
            if ($connection->tableColumnExists($tableName, $columnName) === false) {
                
                $connection->addColumn($tableName, $columnName, array(
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => false,
                    'default' => '',
                    'comment' => 'Gamme key in invoicing application',
                ));
            }
            $connection->addIndex($tableName, $columnName, $columnName);
            /*
                Table attribute
                1 champ ajouté
                    + atoosync_key + Index
            */
            
            /*
                Table attribute_group
                1 champ ajouté
                    + atoosync_key + Index
            */
            
            /*
                Table attribute_group
                1 champ ajouté
                    + atoosync_key + Index
            */
            
            /*Creation d'une nouvellle table pour accueillir les documents*/
        
            $tableAtooSyncDocuments =  $connection->newTable(
                $setup->getTable('atoosync_orders_documents')
            )->addColumn(
                'id_document',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity Id'
            )->addColumn(
                'order_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Sales_Entity_Id'
            )->addColumn(
                'token',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'token'
            )->addColumn(
                'filename',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'filename'
            )->addColumn(
                'customer_account',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'customer AtooSync Account Number'
            )->addColumn(
                'document_number',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'document_number'
            )->addColumn(
                'document_reference',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'document_reference'
            )->addColumn(
                'document_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                null,
                ['nullable' => false],
                'document_date'
            )->addColumn(
                'document_total_tax_excl',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '20,6',
                ['nullable' => true],
                'document_total_tax_excl'
            )->addColumn(
                'document_total_tax_incl',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '20,6',
                ['nullable' => false],
                'document_total_tax_incl'
            )->setComment(
                'atoosync_orders_documents'
            );
            $connection->createTable($tableAtooSyncDocuments);
        }
        
        if (version_compare($context->getVersion(), '20.6.2.0', '<')) {
            $tableName = $setup->getTable('atoosync_orders_documents');
            
            // if the table exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $setup->getConnection()->addColumn($tableName, 'document_name', [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length'    => 255,
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => '',
                    'afters' => 'document_reference',
                    'comment' => 'Document Name'
                ]);
            }
        }
        $setup->endSetup();
    }
}
