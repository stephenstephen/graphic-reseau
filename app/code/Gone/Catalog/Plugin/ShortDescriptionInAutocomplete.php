<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Catalog\Plugin;

use Magento\Catalog\Model\Product;

class ShortDescriptionInAutocomplete
{
    public function afterGetDescription(
        \Mirasvit\Search\Index\Magento\Catalog\Product\InstantProvider\Mapper $subject,
        $result,
        Product $product
    ) {
        // 410 override short_description chosen first
        $shortDescription = $product->getDataUsingMethod('short_description');

        if (empty($shortDescription)) {
            return $result; // is product description
        }

        return html_entity_decode(strip_tags($shortDescription)); // same that Mapper::clearString
    }
}
