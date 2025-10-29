<?php

namespace Gone\Subligraphy\Setup;

use \Magento\Eav\Setup\EavSetup;

class CertifBaseNumberSetup extends EavSetup
{
    public function getDefaultEntities()
    {
        return [
            'subligraphy_base_number' => [
                'entity_type_code'      =>  "subligraphy_base_number",
                'entity_model' => '',
                'table'        => '',
                'increment_model' => \Magento\Eav\Model\Entity\Increment\NumericValue::class,
                'attributes' => [],
            ]
        ];
    }
}
