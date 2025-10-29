<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\OurReview\Helper;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory as EavCollectionFactory;
use Magento\Framework\App\Helper\Context;

class AttributeHelper extends AbstractHelper
{
    public const OUR_REVIEW_GROUP_NAME = 'notre-avis';
    public const ADVANCED_STAT_GROUP_NAME = 'application';

    protected EavCollectionFactory $_eavCollectionFactory;

    public function __construct(
        Context $context,
        EavCollectionFactory $eavCollectionFactory
    ) {
        parent::__construct($context);
        $this->_eavCollectionFactory = $eavCollectionFactory;
    }

    /**
     * @param Product | null $product
     * @param string $groupCode
     * @return mixed
     */
    public function getAttributeFromGroup(string $groupCode, $product = null)
    {
        $attributeCollection = $this->_eavCollectionFactory->create()
            ->addFieldToSelect(['frontend_label', 'attribute_code'])
            ->join(
                ['l' => 'eav_entity_attribute'],
                'l.attribute_id = main_table.attribute_id',
                ['attribute_group_id']
            )->join(
                ['g' => 'eav_attribute_group'],
                'g.attribute_group_id = l.attribute_group_id',
                ['attribute_group_code']
            )->addFieldToFilter('g.attribute_group_code', ['eq' => $groupCode])
            ->addFieldToFilter('main_table.backend_type', ['eq' => 'decimal']);

        if (!empty($product)) {
            $attributeCollection->addFieldToFilter('g.attribute_set_id', ['eq' => $product->getAttributeSetId()]);
        }

        return $attributeCollection;
    }
}
