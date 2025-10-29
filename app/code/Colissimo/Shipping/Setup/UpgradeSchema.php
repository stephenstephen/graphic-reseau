<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2017 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class UpgradeSchema
 */
class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * @var string
     */
    private static $salesConnection = 'sales';

    /**
     * @var string
     */
    private static $checkoutConnection = 'checkout';

    /**
     * @var ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * UpgradeSchema constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig  = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var \Magento\Setup\Module\Setup $installer */
        $installer = $setup;
        $installer->startSetup();

        $salesConnection    = $installer->getConnection(self::$salesConnection);
        $checkoutConnection = $installer->getConnection(self::$checkoutConnection);

        if (version_compare($context->getVersion(), '1.0.0', '<=')) {
            /* Order address */
            $salesConnection->addColumn(
                $installer->getTable('sales_order_address'),
                'colissimo_pickup_id',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 6,
                    'nullable' => true,
                    'comment' => 'Colissimo Pickup Id'
                ]
            );
            $salesConnection->addColumn(
                $installer->getTable('sales_order_address'),
                'colissimo_product_code',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 4,
                    'nullable' => true,
                    'comment' => 'Colissimo Product Code'
                ]
            );
            $salesConnection->addColumn(
                $installer->getTable('sales_order_address'),
                'colissimo_network_code',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 6,
                    'nullable' => true,
                    'comment' => 'Colissimo Network Code'
                ]
            );

            /* Add Monaco and Dom-Tom */
            $bind = [
                ['country_id' => 'FR', 'code' => 'OM', 'default_name' => 'Outre-Mer'],
                ['country_id' => 'FR', 'code' => '98', 'default_name' => 'Monaco']
            ];
            foreach ($bind as $data) {
                $installer->getConnection()->insert(
                    $setup->getTable('directory_country_region'),
                    $data
                );
            }
        }

        if (version_compare($context->getVersion(), '1.1.0', '<=')) {
            $tableName = $installer->getTable('quote_colissimo_pickup');

            if (!$checkoutConnection->isTableExists($tableName)) {
                $table = $checkoutConnection
                    ->newTable($tableName)
                    ->addColumn(
                        'quote_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['unsigned' => true, 'nullable' => false],
                        'Quote Id'
                    )->addColumn(
                        'pickup_id',
                        Table::TYPE_TEXT,
                        10,
                        [],
                        'Pickup Id'
                    )->addColumn(
                        'network_code',
                        Table::TYPE_TEXT,
                        6,
                        [],
                        'Network Code'
                    )->addIndex(
                        $installer->getIdxName(
                            'quote_colissimo_pickup',
                            ['quote_id'],
                            AdapterInterface::INDEX_TYPE_UNIQUE
                        ),
                        ['quote_id'],
                        ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                    )->addForeignKey(
                        $installer->getFkName('quote_colissimo_pickup', 'quote_id', 'quote', 'entity_id'),
                        'quote_id',
                        $installer->getTable('quote'),
                        'entity_id',
                        Table::ACTION_CASCADE
                    )->setComment(
                        'Quote Colissimo Pickup Data'
                    );

                $checkoutConnection->createTable($table);
            }
        }

        if (version_compare($context->getVersion(), '1.2.0', '<=')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('colissimo_shipping_tablerate')
            )->addColumn(
                'pk',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Primary key'
            )->addColumn(
                'store_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Website Id'
            )->addColumn(
                'method',
                Table::TYPE_TEXT,
                25,
                ['nullable' => true],
                'Shipping Method code'
            )->addColumn(
                'country_id',
                Table::TYPE_TEXT,
                4,
                ['nullable' => true],
                'Destination country ISO/2'
            )->addColumn(
                'weight_from',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => true, 'default' => null],
                'Weight From'
            )->addColumn(
                'weight_to',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => true, 'default' => null],
                'Weight To'
            )->addColumn(
                'price',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Price'
            )->addColumn(
                'is_active',
                Table::TYPE_INTEGER,
                1,
                ['nullable' => false, 'default' => 0],
                'Is Active'
            )->setComment(
                'Colissimo Shipping Tablerate'
            );
            $installer->getConnection()->createTable($table);

            $methods = [
                'homecl',
                'homesi',
                'international',
                'domtomcl',
                'domtomsi',
                'pickup',
            ];

            $stores = $this->storeManager->getStores(true);

            foreach ($stores as $store) {
                foreach ($methods as $method) {
                    $price = $installer->getConnection()->fetchOne(
                        $installer->getConnection()->select()
                        ->from($installer->getTable('core_config_data'), ['value'])
                        ->where('path = ?', 'carriers/colissimo/' . $method . '/price')
                        ->where('scope_id = ?', $store->getId())
                        ->limit(1)
                    );

                    if ($price) {
                        try {
                            $prices = unserialize($price);
                        } catch (\Exception $e) {
                            $prices = [];
                        }

                        if (empty($prices) && json_decode($price)) {
                            $prices = json_decode($price, true);
                        }

                        foreach ($prices as $data) {
                            $data = [
                                'store_id'    => $store->getId(),
                                'method'      => $method,
                                'country_id'  => $data['country'],
                                'weight_from' => $data['weight_from'] ?: null,
                                'weight_to'   => $data['weight_to'] ?: null,
                                'price'       => $data['price'],
                                'is_active'   => 1,
                            ];

                            $installer->getConnection()->insertOnDuplicate(
                                $installer->getTable('colissimo_shipping_tablerate'),
                                $data,
                                array_keys($data)
                            );
                        }
                    }
                }
            }
        }

        if (version_compare($context->getVersion(), '1.2.1', '<=')) {
            $checkoutConnection->addColumn(
                $installer->getTable('quote_colissimo_pickup'),
                'telephone',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Customer Telephone'
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.3.0', '<')) {
            $checkoutConnection->addColumn(
                $installer->getTable('quote_colissimo_pickup'),
                'pickup_type',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Pickup Type'
                ]
            );
            $checkoutConnection->addColumn(
                $installer->getTable('quote_colissimo_pickup'),
                'company',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Pickup Company'
                ]
            );
            $checkoutConnection->addColumn(
                $installer->getTable('quote_colissimo_pickup'),
                'street',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Pickup Street'
                ]
            );
            $checkoutConnection->addColumn(
                $installer->getTable('quote_colissimo_pickup'),
                'postcode',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Pickup Postcode'
                ]
            );
            $checkoutConnection->addColumn(
                $installer->getTable('quote_colissimo_pickup'),
                'city',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Pickup City'
                ]
            );
            $checkoutConnection->addColumn(
                $installer->getTable('quote_colissimo_pickup'),
                'country_id',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 2,
                    'nullable' => true,
                    'comment' => 'Pickup Country'
                ]
            );
        }

        $installer->endSetup();
    }
}
