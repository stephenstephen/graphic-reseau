<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Abandoned Cart Email Base for Magento 2
 */

namespace Amasty\Acart\Model\ResourceModel\Schedule;

use Amasty\Acart\Model\ResourceModel\Schedule as ScheduleResource;
use Amasty\Acart\Model\Schedule as ScheduleModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * @method \Amasty\Acart\Model\Schedule[] getItems()
 * @method \Amasty\Acart\Model\Schedule getFirstItem()
 */
class Collection extends AbstractCollection
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init(ScheduleModel::class, ScheduleResource::class);
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }
}
