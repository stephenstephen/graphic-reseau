<?php
/**
 * Copyright © Lyra Network.
 * This file is part of Systempay plugin for Magento 2. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
namespace Lyranetwork\Systempay\Model\System\Config\Backend;

use Lyranetwork\Systempay\Model\System\Config\Backend\Serialized\ArraySerialized\ConfigArraySerialized;

class CategoryMapping extends ConfigArraySerialized
{
    public function beforeSave()
    {
        $values = $this->getValue();

        if (! is_array($values) || empty($values)) {
            $this->setValue([]);
        } else {
            foreach ($values as $id => $value) {
                if (empty($value)) {
                    continue;
                }

                if (! isset($value['systempay_category']) || empty($value['systempay_category'])) {
                    unset($values[$id]);
                }
            }

            $this->setValue($values);
        }

        return parent::beforeSave();
    }
}
