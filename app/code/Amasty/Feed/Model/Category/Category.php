<?php

namespace Amasty\Feed\Model\Category;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Category Model
 *
 * @package Amasty\Feed
 */
class Category extends AbstractModel
{
    const FEED_CATEGORY_ID = 'feed_category_id';

    protected function _construct()
    {
        $this->_init(\Amasty\Feed\Model\Category\ResourceModel\Category::class);
        $this->setIdFieldName(self::FEED_CATEGORY_ID);
    }
}
