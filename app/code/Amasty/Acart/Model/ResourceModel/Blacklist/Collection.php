<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Abandoned Cart Email Base for Magento 2
 */

namespace Amasty\Acart\Model\ResourceModel\Blacklist;

use Amasty\Acart\Model\Blacklist as BlacklistModel;
use Amasty\Acart\Model\ResourceModel\Blacklist as BlacklistResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init(BlacklistModel::class, BlacklistResource::class);
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }
}
