<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Checkout\Observer;

use Magento\Checkout\Model\Cart;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote\Item;

class ChangeProductCustomOption implements ObserverInterface
{
    public const ACTION_REMOVE_OPTION = 'remove';
    public const ACTION_ADD_OPTION = 'add';

    /**
     * event: checkout_cart_update_items_before
     * @param Observer $observer
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        /** @var Cart $cart */
        $cart = $observer->getEvent()->getCart();
        $data = $observer->getEvent()->getInfo()->getData();
        foreach ($data as $itemId => $itemInfo) {
            $item = $cart->getQuote()->getItemById($itemId);
            if (!$item) {
                continue;
            }

            if (isset($itemInfo['product_custom_option']) && !empty($itemInfo['product_custom_option'])) {
                foreach ($itemInfo['product_custom_option'] as $optionId => $newValueId) {
                    $hasOption = false;
                    $currentOptions = $item->getOptions();

                    foreach ($currentOptions as $key => $option) {
                        if ($option->getCode() == 'option_' . $optionId) { // change value of option
                            $hasOption = true;
                            if (!empty($newValueId)) { // select other option
                                $option->setValue($newValueId);
                                $item->setOptions($currentOptions);
                            } else { // remove option
                                $newItemOptionIds = $this->_getNewOptionIdsValue(
                                    $item,
                                    $optionId,
                                    self::ACTION_REMOVE_OPTION
                                );
                                if (!empty($newItemOptionIds)) {
                                    $item->getOptionByCode('option_ids')->setValue($newItemOptionIds);
                                } else {
                                    $item->removeOption('option_ids');
                                    $item->saveItemOptions();
                                }
                                $item->removeOption('option_' . $optionId);
                            }
                        }
                    }

                    if (!$hasOption && !empty($newValueId)) { // add option
                        $this->_getNewOptionIdsValue($item, $optionId, self::ACTION_ADD_OPTION);
                        $item->addOption(
                            [
                                'code' => 'option_ids',
                                'product_id' => $item->getProduct()->getId(),
                                'value' => $this->_getNewOptionIdsValue(
                                    $item,
                                    $optionId,
                                    self::ACTION_ADD_OPTION
                                )
                            ]
                        );

                        $item->addOption(
                            [
                                'code' => 'option_' . $optionId,
                                'product_id' => $item->getProduct()->getId(),
                                'value' => $newValueId
                            ]
                        );
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @param Item $item
     * @param int $optionId
     * @param string $action
     * @return string|null
     */
    protected function _getNewOptionIdsValue(Item $item, int $optionId, string $action)
    {
        if (!empty($item->getOptionByCode('option_ids'))) {
            $currentOptionIds = $item->getOptionByCode('option_ids');
            $value = $currentOptionIds->getValue();
            if (!empty($value)) {
                $value = explode(',', $value);

                switch ($action) {
                    case self::ACTION_ADD_OPTION:
                        $value[] = $optionId;
                        array_unique($value);
                        break;
                    case self::ACTION_REMOVE_OPTION:
                        array_unique($value);
                        $key = array_search($optionId, $value);
                        unset($value[$key]);
                        break;
                }

                return implode(',', $value);
            }
        }

        if ($action == self::ACTION_ADD_OPTION) {
            return (string) $optionId;
        }
        return null;
    }
}
