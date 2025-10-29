<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Setup\Console\Command;

use Gone\OurReview\Helper\AttributeHelper;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Serialize\SerializerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Eav\Setup\EavSetupFactory;

class FixAttributeMigration extends Command
{

    protected ResourceConnection $_resourceConnection;
    protected SerializerInterface $_serializer;
    protected AttributeHelper $_attributeHelper;
    protected EavSetupFactory $_eavSetupFactory;

    // store data
    protected OutputInterface $_output;

    public function __construct(
        ResourceConnection $resourceConnection,
        SerializerInterface $serializer,
        AttributeHelper $attributeHelper,
        EavSetupFactory $eavSetupFactory,
        string $name = null
    ) {
        parent::__construct($name);
        $this->_resourceConnection = $resourceConnection;
        $this->_serializer = $serializer;
        $this->_attributeHelper = $attributeHelper;
        $this->_eavSetupFactory = $eavSetupFactory;
    }

    /**
     * ex : php bin/magento gone_migration:fix
     */
    protected function configure(): void
    {
        $this->setName('gone_migration:fix');
        $this->setDescription('Fix attribute with format issue');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->_output = $output;

        $this->_fixGrReview();
        $this->_removeAttributeFromProductDetails();
    }

    protected function _fixGrReview()
    {
        $this->_output->writeln('<comment>Begin reformat notre_avis data</comment>');
        $connection = $this->_resourceConnection->getConnection();
        $eavAttributeTable = $this->_resourceConnection->getTableName('eav_attribute');
        $eavTextTable = $this->_resourceConnection->getTableName('catalog_product_entity_text');
        $attributeCode = 'notre_avis';
        $attributeId = $connection->fetchOne(
            $connection->select()
                ->from(
                    $eavAttributeTable,
                    'attribute_id'
                )->where(
                    'attribute_code LIKE ?',
                    $attributeCode
                )
        );

        if ($attributeId) {
            $valuesToChange = $connection->fetchAll(
                $connection->select()
                    ->from(
                        $eavTextTable,
                        ['value', 'value_id']
                    )->where(
                        'attribute_id LIKE ?',
                        $attributeId
                    )
            );

            $numberToChange = count($valuesToChange);
            if ($numberToChange > 0) {
                $i = 1;
                foreach ($valuesToChange as $data) {
                    $oldValue = $data['value'];

                    $this->_output->writeln('<comment>Process value ' . $i . ' / ' . $numberToChange . '</comment>');
                    if (!empty($oldValue) && preg_match('/a:[0-9]{1}:({i:[0-9]{1,3})?(.*?)}?}/', $oldValue) == 1) {
                        // only option to get array and then data, else get PHP __PHP_Incomplete_Class Object usable
                        $newValue = $this->_serializer->unserialize(
                            $this->_serializer->serialize(unserialize($oldValue))
                        );

                        if (array_key_exists(0, $newValue)) {
                            $newValue = $newValue[0]['text'];
                        } else {
                            $newValue = '';
                        }

                        $connection->update($eavTextTable, ['value' => $newValue], ['value_id = ?' => $data['value_id']]);
                    } else {
                        $this->_output->writeln('<comment>Value don\'t need to change</comment>');
                    }
                    $i++;
                }
            }
        }
    }

    protected function _removeAttributeFromProductDetails()
    {
        $this->_output->writeln('<comment>Begin hide attribute from product page</comment>');
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->_eavSetupFactory->create();
        $reviewAttributes = $this->_attributeHelper->getAttributeFromGroup(AttributeHelper::OUR_REVIEW_GROUP_NAME);
        foreach ($reviewAttributes as $attribute) {
            $this->_output->writeln('<comment>Process attribute ' . $attribute->getAttributeCode() . '</comment>');
            $eavSetup->updateAttribute(
                Product::ENTITY,
                $attribute->getAttributeCode(),
                'is_visible_on_front',
                false
            );
        }
        $eavSetup->updateAttribute(
            Product::ENTITY,
            'av_average',
            'is_visible_on_front',
            false
        );
        $this->_output->writeln('<comment>End hide attribute from product page</comment>');
    }
}
