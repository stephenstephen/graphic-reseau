<?php

namespace Amasty\Feed\Model\Export\RowCustomizer;

use Amasty\Feed\Model\Category\Repository;
use Amasty\Feed\Model\Category\ResourceModel\CollectionFactory;
use Amasty\Feed\Model\Export\Product;
use Magento\CatalogImportExport\Model\Export\RowCustomizerInterface;
use Magento\CatalogImportExport\Model\Import\Product as ImportProduct;

/**
 * Class Category
 */
class Category implements RowCustomizerInterface
{
    protected $_storeManager;

    protected $_export;

    protected $_mapping;

    protected $_mappingCategories;

    protected $_mappingData;

    protected $_rowCategories;

    protected $_categoriesPath;

    protected $_categoriesLast;

    /**
     * @var CollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * @var Repository
     */
    private $categoryRepository;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Product $export,
        CollectionFactory $categoryCollectionFactory,
        Repository $categoryRepository
    ) {
        $this->_storeManager = $storeManager;
        $this->_export = $export;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @inheritdoc
     */
    public function prepareData($collection, $productIds)
    {
        if ($this->_export->hasAttributes(Product::PREFIX_MAPPED_CATEGORY_ATTRIBUTE)
            || $this->_export->hasAttributes(Product::PREFIX_MAPPED_CATEGORY_PATHS_ATTRIBUTE)
        ) {
            $_skippedCategories = [];

            $this->_mappingCategories = array_merge(
                $this->_export->getAttributesByType(Product::PREFIX_MAPPED_CATEGORY_ATTRIBUTE),
                $this->_export->getAttributesByType(Product::PREFIX_MAPPED_CATEGORY_PATHS_ATTRIBUTE)
            );

            /** @var \Amasty\Feed\Model\Category\ResourceModel\Collection $categoryCollection */
            $categoryCollection = $this->categoryCollectionFactory->create()
                ->addOrder('name')
                ->addFieldToFilter('code', ['in' => $this->_mappingCategories]);
            foreach ($this->categoryRepository->getItemsWithDeps($categoryCollection) as $category) {
                $this->_mappingData[$category->getCode()] = [];

                foreach ($category->getMapping() as $mapping) {
                    if ($mapping->getData('skip')) {
                        $_skippedCategories[] = $mapping->getCategoryId();
                    }
                    $this->_mappingData[$category->getCode()][$mapping->getCategoryId()] = $mapping->getVariable();
                }
            }

            $rowsCategoriesNew = [];
            $multiRowData = $this->_export->getMultiRowData();
            $rowsCategories = $multiRowData['rowCategories'];

            foreach ($rowsCategories as $id => $rowCategories) {
                $rowCategories = array_diff($rowCategories, $_skippedCategories);

                if (!empty($rowCategories)) {
                    $rowsCategoriesNew[$id] = $rowCategories;
                }
            }
            $this->_rowCategories = $rowsCategoriesNew;

            $this->_categoriesPath = $this->_export->getCategoriesPath();
            $this->_categoriesLast = $this->_export->getCategoriesLast();
        }
    }

    /**
     * @inheritdoc
     */
    public function addHeaderColumns($columns)
    {
        return $columns;
    }

    /**
     * @inheritdoc
     */
    public function addData($dataRow, $productId)
    {
        $customData = &$dataRow['amasty_custom_data'];
        $customData[Product::PREFIX_MAPPED_CATEGORY_ATTRIBUTE] = [];
        $customData[Product::PREFIX_MAPPED_CATEGORY_PATHS_ATTRIBUTE] = [];

        if (is_array($this->_mappingCategories)) {
            foreach ($this->_mappingCategories as $code) {
                if (isset($this->_rowCategories[$productId])) {
                    $categories = $this->_rowCategories[$productId];
                    $lastCategoryId = $this->getLastCategoryId($categories);

                    if (isset($this->_categoriesLast[$lastCategoryId]) && is_array($this->_mappingCategories)) {
                        $lastCategoryVar = $this->_categoriesLast[$lastCategoryId];

                        if (isset($this->_mappingData[$code][$lastCategoryId])) {
                            $customData[Product::PREFIX_MAPPED_CATEGORY_ATTRIBUTE][$code] =
                                $this->_mappingData[$code][$lastCategoryId];
                        } else {
                            $customData[Product::PREFIX_MAPPED_CATEGORY_ATTRIBUTE][$code] = $lastCategoryVar;
                        }
                    }

                    $customData[Product::PREFIX_MAPPED_CATEGORY_PATHS_ATTRIBUTE][$code] = implode(
                        ImportProduct::PSEUDO_MULTI_LINE_SEPARATOR,
                        $this->getCategoriesPath($categories, $code)
                    );
                }
            }
        }

        return $dataRow;
    }

    /**
     * @param array $categories
     *
     * @return int|null
     */
    private function getLastCategoryId($categories)
    {
        while (count($categories) > 0) {
            $endCategoryId = array_pop($categories);

            foreach ($this->_mappingCategories as $code) {
                if (isset($this->_mappingData[$code][$endCategoryId])) {
                    return $endCategoryId;
                }
            }
        }

        return null;
    }

    /**
     * @param array $categories
     * @param string $code
     *
     * @return array
     */
    private function getCategoriesPath($categories, $code)
    {
        $categoriesPath = [];

        foreach ($categories as $categoryId) {
            if (isset($this->_categoriesPath[$categoryId])) {
                $path = $this->_categoriesPath[$categoryId];

                foreach ($path as $id => $var) {
                    if (isset($this->_mappingData[$code][$id])) {
                        $path[$id] = $this->_mappingData[$code][$id];
                    } else {
                        $path[$id] = $var;
                    }
                }

                $categoriesPath[] = implode('/', $path);
            }
        }

        return $categoriesPath;
    }

    /**
     * @inheritdoc
     */
    public function getAdditionalRowsCount($additionalRowsCount, $productId)
    {
        return $additionalRowsCount;
    }
}
