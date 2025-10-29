<?php

namespace Gone\Subligraphy\Model\ResourceModel\Certificate\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    /**
     * Override _initSelect to add custom columns
     *
     * @return
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()
            ->join(
                ['ce' => $this->getTable('customer_entity')],
                'ce.entity_id = main_table.customer_id',
                ["lastname" =>'ce.lastname', "firstname" =>'ce.firstname']
            );

        $this->getSelect()->columns(new \Zend_Db_Expr('CONCAT_WS(" ", ce.lastname, ce.firstname) as customer'));
        $this->addFilterToMap(
            'customer',
            new \Zend_Db_Expr('CONCAT_WS(" ", ce.lastname, ce.firstname)')
        );
        return $this->getSelect();
    }
}
