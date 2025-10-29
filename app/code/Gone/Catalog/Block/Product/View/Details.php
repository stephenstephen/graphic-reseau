<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Gone\Catalog\Block\Product\View;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Cms\Model\Template\FilterProvider;

/**
 * Product details block.
 *
 * Holds a group of blocks to show as tabs.
 *
 * @api
 * @since 103.0.1
 */
class Details extends \Magento\Catalog\Block\Product\View\Details
{
    protected BlockRepositoryInterface $_blockRepository;
    protected SearchCriteriaBuilder $_searchCriteriaBuilder;
    protected SortOrderBuilder $_sortOrderBuilder;
    protected FilterProvider $_filterProvider;
    protected Registry $_registry;

    public function __construct(
        Template\Context $context,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        BlockRepositoryInterface $blockRepository,
        FilterProvider $filterProvider,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
        $this->_blockRepository = $blockRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_sortOrderBuilder = $sortOrderBuilder;
        $this->_filterProvider = $filterProvider;
        $this->_registry = $registry;
    }

    /**
     * @return array|false
     * @throws LocalizedException
     */
    public function getCustomTabsContent()
    {
        $customTabs = $this->getCustomTabsList();
        if ($customTabs) {
            $blocksIdArr = explode(',', $customTabs);

            $sortByBlockPositionAsc = $this->_sortOrderBuilder
                ->setField('gr_position')
                ->setAscendingDirection()
                ->create();

            $searchCriteria = $this->_searchCriteriaBuilder
                ->addFilter(
                    'block_id',
                    $blocksIdArr,
                    'in'
                )
                ->setSortOrders([$sortByBlockPositionAsc]);

            return $this->_blockRepository->getList($searchCriteria->create())->getItems();
        }
        return [];
    }

    /**
     * @return string|false
     */
    protected function getCustomTabsList()
    {
        return $this->getCurrentProduct()->getCustomTabs() ?? false;
    }

    /**
     * @return mixed|null
     */
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getInterpretedContent(string $content){
        return  $this->_filterProvider->getBlockFilter()->filter($content);
    }
}
