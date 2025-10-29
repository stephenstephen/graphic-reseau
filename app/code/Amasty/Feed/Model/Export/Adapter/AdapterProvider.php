<?php

namespace Amasty\Feed\Model\Export\Adapter;

use Magento\Framework\Exception\LocalizedException;

/**
 * Class AdapterProvider
 */
class AdapterProvider
{
    private $adapters;

    public function __construct($adapters)
    {
        $this->adapters = $adapters;
    }

    public function get($adapterName, $params)
    {
        if (!isset($this->adapters[$adapterName])) {
            throw new LocalizedException(__('Please correct the file format.'));
        }

        return $this->adapters[$adapterName]->create($params);
    }
}
