<?php

namespace Gone\Paymentbtob\Setup\Patch\Data;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddBToBCreditAttributes implements DataPatchInterface
{
    public const BTOB_CREDIT_AUTH = 'btob_credit_authorized';
    public const BTOB_CREDIT_MAX = 'btob_credit_max';

    private const ATTRIBUTES_LIST = [
        self::BTOB_CREDIT_AUTH,
        self::BTOB_CREDIT_MAX,
    ];

    protected CustomerSetupFactory $_customerSetupFactory;
    private ModuleDataSetupInterface $_moduleDataSetup;
    private AttributeSetFactory $_attributeSetFactory;


    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        AttributeSetFactory $attributeSetFactory,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->_moduleDataSetup = $moduleDataSetup;
        $this->_attributeSetFactory = $attributeSetFactory;
        $this->_customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->_moduleDataSetup->getConnection()->startSetup();

        /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $customerSetup = $this->_customerSetupFactory->create(['setup' => $this->_moduleDataSetup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->_attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            self::BTOB_CREDIT_AUTH,
            [
                'type' => 'int',
                'label' => 'B2B Credit authorized',
                'input' => 'boolean',
                'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                'is_used_in_grid' => true,
                'is_filterable_in_grid' => true,
                'is_visible_in_grid' => true,
                'user_defined' => true,
                'system' => false,
                'visible' => true,
                'required' => false,
                'default' => 0,
            ]
        )
            ->addAttribute(
                \Magento\Customer\Model\Customer::ENTITY,
                self::BTOB_CREDIT_MAX,
                [
                    'type' => 'decimal',
                    'label' => 'B2B Credit Max',
                    'input' => 'text',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'is_used_in_grid' => true,
                    'is_filterable_in_grid' => true,
                    'is_visible_in_grid' => true,
                    'user_defined' => true,
                    'system' => false,
                    'visible' => true,
                    'required' => false,
                    'default' => null,
                ]
            );

        foreach (self::ATTRIBUTES_LIST as $attribute) {
            $customerAttribute = $customerSetup->getEavConfig()
                ->getAttribute(\Magento\Customer\Model\Customer::ENTITY, $attribute)
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer']
                ]);
            $customerAttribute->save();
        }

        $this->_moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
