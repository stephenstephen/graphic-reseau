<?php

namespace Gone\Catalog\Block\Product\View;

use Magento\Catalog\Block\Product\View;

class Flash extends View
{

    /**
     * @return false|string
     */
    public function getIsFlash()
    {

        $_product = $this->getProduct();
        $isFlash = $_product->getOnFlashSale();
        if (!$isFlash) {
            return false;
        }

        $specialToDateRaw = $_product->getSpecialToDate();
        if (empty($specialToDateRaw)) {
            return false;
        }

        $specialToDate = date('Y-m-d', strtotime($specialToDateRaw)).'T23:59:59';
        return $specialToDate;
    }
}
