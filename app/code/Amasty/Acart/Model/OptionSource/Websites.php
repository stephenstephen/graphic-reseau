<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Abandoned Cart Email Base for Magento 2
 */

namespace Amasty\Acart\Model\OptionSource;

use Amasty\Acart\Ui\DataProvider\Reports\FilterConstants;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManager;

class Websites implements OptionSourceInterface
{
    /**
     * @var DataObject
     */
    private $objectConverter;

    /**
     * @var StoreManager
     */
    private $storeManager;

    public function __construct(DataObject $objectConverter, StoreManager $storeManager)
    {
        $this->objectConverter = $objectConverter;
        $this->storeManager = $storeManager;
    }

    public function toOptionArray()
    {
        $websites = $this->objectConverter->toOptionArray(
            $this->storeManager->getWebsites(),
            'website_id',
            'name'
        );

        array_unshift($websites, ['value' => FilterConstants::ALL, 'label' => __('All Websites')]);

        return $websites;
    }
}
