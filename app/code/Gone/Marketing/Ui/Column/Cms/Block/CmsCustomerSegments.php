<?php
/*
 *
 * @copyright Copyright © 2020 410-Gone. All rights reserved.
 * @author    contact@410-gone.fr
 *
 */

namespace Gone\Marketing\Ui\Column\Cms\Block;

use Aheadworks\CustomerSegmentation\Api\SegmentRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;
use ReflectionClass;

/**
 * Store Options for Cms Pages and Blocks
 */
class CmsCustomerSegments implements OptionSourceInterface
{
    public const DEFAULT_ALL_CUSTOMER_SEGMENT = [
        'value' => "0",
        'label' => 'All Customers'
    ];

    protected SegmentRepositoryInterface $_segmentRepository;
    protected SearchCriteriaBuilder $_searchCriteriaBuilder;

    public function __construct(
        SegmentRepositoryInterface $segmentRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->_segmentRepository = $segmentRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $segments = $this->_getActiveCustomerSegments() ?? false;
        $options[] = [
            'label' => self::DEFAULT_ALL_CUSTOMER_SEGMENT['label'],
            'value' => self::DEFAULT_ALL_CUSTOMER_SEGMENT['value']
        ];

        if ($segments) {
            foreach ($segments as $segment) {
                $options [] = [
                    'label' => $segment->getName(),
                    'value' => $segment->getSegmentId()
                ];
            }
        }
        return $options;
    }

    protected function _getActiveCustomerSegments()
    {
        $searchCriteria = $this->_searchCriteriaBuilder->create();
        return $this->_segmentRepository->getList($searchCriteria)->getItems();
    }

    /**
     * @return mixed
     */
    private function getConstants()
    {
        $reflectionClass = new ReflectionClass(static::class);
        return $reflectionClass->getConstants();
    }


}
