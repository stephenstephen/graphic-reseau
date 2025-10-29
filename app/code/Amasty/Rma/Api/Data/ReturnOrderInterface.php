<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Api\Data;

/**
 * Interface ReturnOrderInterface
 */
interface ReturnOrderInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const ORDER = 'order';
    const ITEMS = 'items';
    /**#@-*/

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return \Amasty\Rma\Api\Data\ReturnOrderInterface
     */
    public function setOrder($order);

    /**
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    public function getOrder();

    /**
     * @return \Amasty\Rma\Api\Data\ReturnOrderItemInterface[]
     */
    public function getItems();

    /**
     * @param \Amasty\Rma\Api\Data\ReturnOrderItemInterface[] $items
     *
     * @return \Amasty\Rma\Api\Data\ReturnOrderInterface
     */
    public function setItems($items);
}
