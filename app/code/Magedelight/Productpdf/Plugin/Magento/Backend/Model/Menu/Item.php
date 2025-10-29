<?php
    /**
     * Magedelight
     * Copyright (C) 2019 Magedelight <info@magedelight.com>
     *
     * @category Magedelight
     * @package {Vendor}_{Module}
     * @copyright Copyright (c) 2019 Mage Delight (http://www.magedelight.com/)
     * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
     * @author Magedelight <info@magedelight.com>
     */

    namespace Magedelight\Productpdf\Plugin\Magento\Backend\Model\Menu;

class Item
{
    public function afterGetUrl($subject, $result)
    {
        $menuId = $subject->getId();
       // echo $menuId; exit;
        if ($menuId == 'Magedelight_Productpdf::documentation') {
            $result = 'http://docs.magedelight.com/display/MAG/Product+PDF+Print+-+Magento+2';
        }
            
        return $result;
    }
}
