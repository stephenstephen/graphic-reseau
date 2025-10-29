<?php

namespace Gone\Catalog\Setup\Patch\Data;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollection;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class UpdateCategoriesSortByList implements DataPatchInterface
{

    public const AVAILABLE_SORT_BY = 'available_sort_by';

    private ModuleDataSetupInterface $moduleDataSetup;
    private CategoryCollection $_categoryCollection;
    private CategoryRepositoryInterface $_categoryRepositoryInterface;

    /**
     * IsUsedProductAttribute constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategoryCollection $categoryCollection,
        CategoryRepositoryInterface $categoryRepositoryInterface
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->_categoryCollection = $categoryCollection;
        $this->_categoryRepositoryInterface = $categoryRepositoryInterface;
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
//        $categoriesColl = $this->_categoryCollection->create()
//            ->addAttributeToSelect('name')
//            ->addAttributeToSelect(self::AVAILABLE_SORT_BY);
//
//        /** @var Category $category */
//        foreach ($categoriesColl as $category) {
//            if ($category->getName()) {
//                $category->setIsActive(true);
//                $category->setAvailableSortBy(
//                    [
//                        'position',
//                        'name',
//                        'price',
//                        'stock_sort_string',
//                        'quantity_and_stock_status'
//                    ]
//                );
//                $this->_categoryRepositoryInterface->save($category);
//            }
//        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
