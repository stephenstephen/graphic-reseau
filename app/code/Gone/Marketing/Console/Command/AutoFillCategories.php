<?php
/**
 *   Copyright � 410 Gone (contact@410-gone.fr). All rights reserved.
 *   See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace Gone\Marketing\Console\Command;

use Exception;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\Catalog\Api\CategoryLinkRepositoryInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\State;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Framework\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AutoFillCategories extends Command
{
    public const AUTOFILLCAT_PROMO = 'promotions';
    public const AUTOFILLCAT_NEW = 'Nouveaux Produits';
    public const AUTOFILLCAT_OCC = 'occasion';

    protected State $_state;
    protected Registry $_registry;
    protected ProductCollectionFactory $_productCollectionFactory;
    protected CategoryRepositoryInterface $_category;
    protected CategoryCollectionFactory $_categoryCollectionFactory;
    protected CategoryLinkManagementInterface $_categoryLinkManagement;
    protected CategoryLinkRepositoryInterface $_categoryLinkRepository;
    protected ResourceConnection $_resourceConnection;
    protected IndexerRegistry $_indexerRegistry;

    public function __construct(
        State                           $state,
        Registry                        $registry,
        ProductCollectionFactory        $productCollectionFactory,
        CategoryCollectionFactory       $categoryCollectionFactory,
        CategoryLinkManagementInterface $categoryLinkManagementInterface,
        CategoryLinkRepositoryInterface $categoryLinkRepositoryInterface,
        ResourceConnection              $resourceConnection,
        CategoryRepositoryInterface     $category,
        IndexerRegistry                 $indexerRegistry,
                                        $name = null
    )
    {
        $this->_registry = $registry;
        $this->_state = $state;
        $this->_category = $category;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_categoryLinkManagement = $categoryLinkManagementInterface;
        $this->_categoryLinkRepository = $categoryLinkRepositoryInterface;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_resourceConnection = $resourceConnection;
        $this->_indexerRegistry = $indexerRegistry;

        parent::__construct($name);
    }

    /**
     * ex : php bin/magento gone_marketing:autofillcat
     */
    protected function configure()
    {
        $this->setName('gone_marketing:autofillcat');
        $this->setDescription('Set des categories Promo, New, Occasion');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->_registry->unregister('isSecureArea');
        $this->_registry->register('isSecureArea', true);
        try {
            $this->_state->setAreaCode(FrontNameResolver::AREA_CODE);
        } catch (Exception $e) {

        }
        $output->writeln('start');

        $categoryIdToReindexArr[] = $discountProductCategoryId = $this->getCategoryId(self::AUTOFILLCAT_PROMO);
        $categoryIdToReindexArr[] = $newProductCategoryId = $this->getCategoryId(self::AUTOFILLCAT_NEW);
        $categoryIdToReindexArr[] = $hasOccasionProductCategoryId = $this->getCategoryId(self::AUTOFILLCAT_OCC);

        $output->writeln('################## Fill promotion product category ###################');
        if (!empty($discountProductCategoryId)) {
            $flashSaleProductCollection = $this->getFlashSaleProductCollection();
            $output->writeln('Add ' . count($flashSaleProductCollection) . ' products');
            $this->processingProductCollection($discountProductCategoryId, $flashSaleProductCollection);
        } else {
            $output->writeln('This category doesn\'t exists');
        }

        $output->writeln('################## Fill new product category ###################');
        if (!empty($newProductCategoryId)) {
            $newProductCollection = $this->getNewProductCollection();
            $output->writeln('Add ' . count($newProductCollection) . ' products');
            $this->processingProductCollection($newProductCategoryId, $newProductCollection);
        } else {
            $output->writeln('This category doesn\'t exists');
        }

        $output->writeln('################## Fill occasion product category ###################');
        if (!empty($hasOccasionProductCategoryId)) {
            $hasOccasionProductCollection = $this->getHasOccasionProductCollection();
            $output->writeln('Found ' . count($hasOccasionProductCollection) . ' products');
            $this->processingProductCollection($hasOccasionProductCategoryId, $hasOccasionProductCollection);
        } else {
            $output->writeln('This category doesn\'t exists');
        }

        $output->writeln('################## Reindex categories ###################');
        if(!empty($categoryIdToReindexArr)) {
            $categoryIdToReindex = array_unique($categoryIdToReindexArr);
            $this->_indexerRegistry->get(\Magento\Catalog\Model\Indexer\Product\Category::INDEXER_ID)->reindexList($categoryIdToReindex);
        }

        $output->writeln('end');
    }

    /**
     * @param string $categoryTitle
     * @return mixed
     */
    protected function getCategoryId(string $categoryTitle)
    {
        $collection = $this->_categoryCollectionFactory->create()
            ->addAttributeToFilter('name', $categoryTitle)
            ->setPageSize(1);

        if ($collection->getSize()) {
            echo $collection->getFirstItem()->getId();
            return $collection->getFirstItem()->getId();
        }
    }

    /**
     * @return Collection
     */
    protected function getFlashSaleProductCollection(): Collection
    {
        return $this->_productCollectionFactory->create()
            ->addAttributeToFilter(
                'special_to_date',
                [
                    'date' => true,
                    'gt' => date('Y-m-d') . ' 23:59:59'
                ]
            )
            ->addAttributeToSort(
                'special_to_date',
                'asc'
            );
    }

    /**
     * @param string $productCategoryId
     * @param Collection $productCollection
     */
    protected function processingProductCollection(
        string     $productCategoryId,
        Collection $productCollection
    )
    {
        $productSkuArr = [];
        /** @var  Product $product */
        foreach ($productCollection as $product) {
            if (!in_array($productCategoryId, $product->getCategoryIds())) {
                $categoryIds = array_unique(array_merge($product->getCategoryIds(), [$productCategoryId]));
                try {
                    $this->_categoryLinkManagement->assignProductToCategories(
                        $product->getSku(),
                        $categoryIds
                    );
                } catch (Exception $e) {
                    echo "Erreur lors de l'ajout du produit '" . $product->getSku() . "' dans la catégorie '" . $productCategoryId . "' - " . $e->getMessage();
                }
            }
            $productSkuArr[] = $product->getSku();
        }

        $category = $this->_category->get($productCategoryId);

        foreach ($category->getProductCollection() as $productToRemove) {
            if (!in_array($productToRemove->getSku(), $productSkuArr)) {
                $this->_categoryLinkRepository->deleteByIds($productCategoryId, $productToRemove->getSku());
            }
        }
    }

    /**
     * @return Collection
     */
    protected function getNewProductCollection(): Collection
    {
        return $this->_productCollectionFactory->create()
            ->addAttributeToFilter(
                'news_to_date',
                [
                    'date' => true,
                    'gt' => date('Y-m-d') . ' 23:59:59'
                ]
            )
            ->addAttributeToSort(
                'news_to_date',
                'asc'
            );
    }

    /**
     * @return Collection
     */
    protected function getHasOccasionProductCollection(): ?Collection
    {
        $subColl = $this->_productCollectionFactory->create()
            ->addFieldToFilter('related_new_product_sku', [
                'notnull' => true
            ])
            ->getColumnValues('related_new_product_sku');

        return $this->_productCollectionFactory->create()
            ->addFieldToFilter('sku', [
                'in' => array_unique($subColl)
            ]);
    }
}
