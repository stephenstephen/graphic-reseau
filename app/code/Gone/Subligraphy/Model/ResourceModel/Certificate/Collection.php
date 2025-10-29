<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Subligraphy\Model\ResourceModel\Certificate;

use Gone\Subligraphy\Model\ResourceModel\Certificate;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'certificate_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Gone\Subligraphy\Model\Certificate::class,
            Certificate::class
        );
    }
}
