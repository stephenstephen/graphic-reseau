<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 17/9/19
 * Time: 11:54 AM
 */

namespace Ced\MauticIntegration\Model\ResourceModel\ErrorLog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function _construct()
    {
        $this->_init('Ced\MauticIntegration\Model\ErrorLog',
            'Ced\MauticIntegration\Model\ResourceModel\ErrorLog'
        );
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }
}