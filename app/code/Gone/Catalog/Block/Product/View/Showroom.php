<?php

namespace Gone\Catalog\Block\Product\View;

use Magento\Catalog\Block\Product\View;

class Showroom extends View
{
    /**
     * @return string|bool
     */
    public function getIsInShowroom()
    {
        $isInShowroom = $this->getProduct()->getShowroom();
        if (!empty($isInShowroom)) {
            return $this->getProduct()->getShowroom();
        }
        return false;
    }
}
