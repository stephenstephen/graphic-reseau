<?php
/**
 * Copyright © Lyra Network.
 * This file is part of Systempay plugin for Magento 2. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
namespace Lyranetwork\Systempay\Model\System\Config\Backend\Fullcb;

class FullcbPaymentOptions extends \Lyranetwork\Systempay\Model\System\Config\Backend\Serialized\ArraySerialized\ConfigArraySerialized
{
    public function beforeSave()
    {
        $values = $this->getValue();

        if (! is_array($values) || empty($values)) {
            $this->setValue([]);
        } else {
            $i = 0;
            foreach ($values as $value) {
                $i++;

                if (empty($value)) {
                    continue;
                }

                if (empty($value['label'])) {
                    $this->throwException('Label', $i);
                }

                $this->checkAmount($value['amount_min'], 'Minimum amount', $i);
                $this->checkAmount($value['amount_max'], 'Maximum amount', $i);
                $this->checkRate($value['rate'], 'Rate', $i);
                $this->checkAmount($value['cap'], 'Cap', $i);
            }
        }

        return parent::beforeSave();
    }
}
