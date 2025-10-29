<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_ProductAttachments
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductAttachments\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * @codeCoverageIgnore
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

        $connection = $installer->getConnection();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            if ($installer->tableExists('mageplaza_productattachments_file')) {
                $connection->addColumn(
                    $installer->getTable('mageplaza_productattachments_file'),
                    'type',
                    [
                        'type' => Table::TYPE_SMALLINT,
                        null,
                        'default' => 0,
                        'comment' => 'File type',
                        'after' => 'name'
                    ]
                );
            }
        }
        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            if ($installer->tableExists('mageplaza_productattachments_log')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_productattachments_log'),
                    'created_at',
                    ['default' => Table::TIMESTAMP_INIT, 'type' => Table::TYPE_TIMESTAMP]
                );
            }
        }

        $installer->endSetup();
    }
}
