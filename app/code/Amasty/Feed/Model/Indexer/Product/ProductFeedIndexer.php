<?php

namespace Amasty\Feed\Model\Indexer\Product;

use Amasty\Feed\Model\Indexer\AbstractIndexer;

class ProductFeedIndexer extends AbstractIndexer
{
    /**
     * Override constructor. Indexer is changed
     *
     * @param IndexBuilder $productIndexBuilder
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     */
    //phpcs:ignore
    public function __construct(
        \Amasty\Feed\Model\Indexer\Product\IndexBuilder $productIndexBuilder,
        \Magento\Framework\Event\ManagerInterface $eventManager
    ) {
        parent::__construct($productIndexBuilder, $eventManager);
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecuteList($productIds)
    {
        $this->indexBuilder->reindexByProductIds(array_unique($productIds));
        $this->getCacheContext()->registerEntities(\Magento\Catalog\Model\Product::CACHE_TAG, $productIds);
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecuteRow($productId)
    {
        $this->indexBuilder->reindexByProductId($productId);
    }
}
