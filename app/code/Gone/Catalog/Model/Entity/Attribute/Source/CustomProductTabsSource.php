<?php


namespace Gone\Catalog\Model\Entity\Attribute\Source;

use Gone\Catalog\Ui\Column\Cms\Block\CmsBlockTypeOptions;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;

class CustomProductTabsSource extends AbstractSource
{

    protected $_blockRepository;
    protected $_searchCriteriaBuilder;
    protected $_options;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        BlockRepositoryInterface $blockRepository
    ) {
        $this->_blockRepository = $blockRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritDoc
     */
    public function getAllOptions()
    {

        if (!isset($this->_options)) {
            $this->_options = [];
            $cmsBlocksArr = $this->getCustomProductTabBlocksCmsList();
            foreach ($cmsBlocksArr as $block) {
                $this->_options[] = [
                    'label' => $block->getTitle(),
                    'value' => $block->getId()
                ];
            }
        }

        return $this->_options;
    }

    /**
     * @return BlockInterface
     * @throws LocalizedException
     */
    protected function getCustomProductTabBlocksCmsList()
    {
        $searchCriteria = $this->_searchCriteriaBuilder
            ->addFilter(
                'gr_type',
                CmsBlockTypeOptions::CUSTOM_PRODUCT_TAB,
                'eq'
            );
        return $this->_blockRepository->getList($searchCriteria->create())->getItems();
    }
}
