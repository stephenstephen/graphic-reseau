<?php

namespace Amasty\Feed\Model\Category\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

/**
 * Class Catalog Category Mapping
 *
 * @package Amasty\Feed
 */
class Mapping extends AbstractDb
{
    /**
     * Initialize table nad PK name
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('amasty_feed_category_mapping', 'entity_id');
    }
}
