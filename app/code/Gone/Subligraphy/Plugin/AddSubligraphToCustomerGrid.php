<?php

namespace Gone\Subligraphy\Plugin;

use Magento\Customer\Model\ResourceModel\Grid\Collection as CustomerGridCollection;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\App\ResourceConnection;

class AddSubligraphToCustomerGrid
{
    private ResourceConnection $resourceConnection;
    private EavConfig $eavConfig;

    public function __construct(
        ResourceConnection $resourceConnection,
        EavConfig $eavConfig
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->eavConfig = $eavConfig;
    }

    /**
     * Add 'is_subligraph' to the customer grid collection.
     *
     * @param CustomerGridCollection $collection
     */
    public function beforeLoad(CustomerGridCollection $collection)
    {
        $connection = $this->resourceConnection->getConnection();
        $customerEntityTable = $connection->getTableName('customer_entity_int'); // Table pour les attributs 'int'

        // Obtenir l'attribute_id pour 'is_subligraph'
        $attributeId = $this->getAttributeId('is_subligraph', 'customer');

        if ($attributeId) {
            // Ajouter la jointure pour l'attribut 'is_subligraph' et changer la valeur 1/0 en "Oui"/"Non"
            $collection->getSelect()->joinLeft(
                ['subligraph_attr' => $customerEntityTable],
                sprintf(
                    'subligraph_attr.entity_id = main_table.entity_id AND subligraph_attr.attribute_id = %d',
                    $attributeId
                ),
                [
                    'is_subligraph' => new \Zend_Db_Expr(
                        'CASE WHEN subligraph_attr.value = 1 THEN "Oui" ELSE "Non" END'
                    )
                ]
            );
        }
    }

    /**
     * Get the attribute ID for a given attribute code and entity type.
     *
     * @param string $attributeCode
     * @param string $entityTypeCode
     * @return int|null
     */
    private function getAttributeId(string $attributeCode, string $entityTypeCode): ?int
    {
        /** @var \Magento\Eav\Model\Config $eavConfig */
        $eavConfig = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Eav\Model\Config::class);
        $entityTypeId = $eavConfig->getEntityType($entityTypeCode)->getEntityTypeId();  // Use Config class to get the entityTypeId
        $connection = $this->resourceConnection->getConnection();
        $eavAttributeTable = $connection->getTableName('eav_attribute');

        $attributeId = $connection->fetchOne(
            $connection->select()
                ->from($eavAttributeTable, 'attribute_id')
                ->where('attribute_code = ?', $attributeCode)
                ->where('entity_type_id = ?', $entityTypeId)
        );

        return $attributeId ? (int)$attributeId : null;
    }

}
