<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Abandoned Cart Email Base for Magento 2
 */

namespace Amasty\Acart\Model\History\ProductDetails;

use Amasty\Acart\Api\Data\HistoryDetailInterface;
use Magento\Framework\Exception\CouldNotSaveException;

class DetailSaver
{
    /**
     * @var ResourceModel\Detail
     */
    private $detailResource;

    public function __construct(ResourceModel\Detail $detailResource)
    {
        $this->detailResource = $detailResource;
    }

    public function execute(HistoryDetailInterface $historyDetail): HistoryDetailInterface
    {
        try {
            $this->detailResource->save($historyDetail);
        } catch (\Exception $e) {
            if ($historyDetail->getDetailId()) {
                throw new CouldNotSaveException(
                    __(
                        'Unable to save history product detail with ID %1. Error: %2',
                        [$historyDetail->getDetailId(), $e->getMessage()]
                    )
                );
            }

            throw new CouldNotSaveException(__('Unable to save new detail. Error: %1', $e->getMessage()));
        }

        return $historyDetail;
    }
}
