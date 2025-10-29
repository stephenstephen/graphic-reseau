<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2019 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Model\ResourceModel\Price;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Zend_Db_Select;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     * @phpcs:disable
     */
    protected $_idFieldName = 'pk';

    /**
     * Define resource model
     *
     * @return void
     * @phpcs:disable
     */
    protected function _construct()
    {
        $this->_init('Colissimo\Shipping\Model\Price', 'Colissimo\Shipping\Model\ResourceModel\Price');
    }

    /**
     * Clear collection
     *
     * @return $this
     */
    public function clear()
    {
        $this->getSelect()->reset(Zend_Db_Select::WHERE);
        $this->getSelect()->reset(Zend_Db_Select::ORDER);

        parent::clear();

        return $this;
    }
}
