<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Customer\Plugin;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\CustomerExtractor;
use Magento\Framework\App\RequestInterface;

class AddTvaCustomerExtractor
{

    public function afterExtract(
        CustomerExtractor $subject,
        CustomerInterface $result,
        $formCode,
        RequestInterface $request,
        array $attributeValues = []
    )
    {
        $vat = $request->getParam('vat_id');
        if (!empty($vat)) {
            $result->setTaxvat($vat);
        }

        return $result;
    }
}
