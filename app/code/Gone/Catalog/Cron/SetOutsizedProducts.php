<?php
/**
 *   Copyright � 410 Gone (contact@410-gone.fr). All rights reserved.
 *   See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace Gone\Catalog\Cron;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\ResourceConnection;

class SetOutsizedProducts
{
    public const OUTSIZED_ATTRIBUTE = 'outsized';
    public const PRODUCT_SIZE_ATTRIBUTE = 'paper_width';
    public const SIZE_LIMIT = 1200; //mm

    protected ProductCollectionFactory $_productCollectionFactory;
    protected ResourceConnection $_resourceConnection;

    public function __construct(
        ProductCollectionFactory $productCollectionFactory,
        ResourceConnection       $resourceConnection
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_resourceConnection = $resourceConnection;
    }

    public function execute()
    {
        $productsToSet = $this->getOversizedProductCollection();
        try {
            /** @var  Product $product */
            foreach ($productsToSet as $product) {
                $attributeValue = (int)$product->getData(self::PRODUCT_SIZE_ATTRIBUTE) ?? null;
                if (!empty($attributeValue)) {
                    if ($attributeValue >= self::SIZE_LIMIT) {
                        $outsizedValueNew = '1';
                    } else {
                        $outsizedValueNew = '0';
                    }
                    if ($product->getData(self::OUTSIZED_ATTRIBUTE) !== $outsizedValueNew) {
                        $product->addAttributeUpdate(self::OUTSIZED_ATTRIBUTE, $outsizedValueNew, 0);
                    }
                }
            }

        } catch (Exception $e) {
            echo "Erreur :'" . $product->getSku() . "' - " . $e->getMessage();
        }
    }

    /**
     * @return Collection
     */
    protected
    function getOversizedProductCollection(): Collection
    {
        return $this->_productCollectionFactory->create()
            ->addAttributeToSelect(
                self::PRODUCT_SIZE_ATTRIBUTE
            )
            ->addAttributeToSelect(
                self::OUTSIZED_ATTRIBUTE
            );
    }
}
