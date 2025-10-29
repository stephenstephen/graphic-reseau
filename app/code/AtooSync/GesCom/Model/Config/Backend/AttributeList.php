<?php
namespace AtooSync\GesCom\Model\Config\Backend;

use Magento\Framework\Option\ArrayInterface;
use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Eav\Model\Entity\TypeFactory;

class AttributeList implements ArrayInterface
{
    protected $attributeFactory;
	protected $eavTypeFactory;
	
	public function __construct(
        AttributeFactory $attributeFactory,
        TypeFactory $typeFactory
    )
    {
        $this->attributeFactory = $attributeFactory;
        $this->eavTypeFactory = $typeFactory;
    }

	public function toOptionArray(){

		$arr = $this->_toArray();
		$ret = [];
        $ret[] = [
				'value' => '0',
				'label' => ' '
			];
		foreach ($arr as $key => $value){
			$ret[] = [
				'value' => $key,
				'label' => $value
			];
		}
		return $ret;
	}
 
	private function _toArray(){
        $arr = [];
        $entityType = $this->eavTypeFactory->create()->loadByCode('catalog_product');        
        $collection = $this->attributeFactory->create()->getCollection();
        $collection->addFieldToFilter('entity_type_id', $entityType->getId());
        $collection->addFieldToFilter('is_user_defined', 1);
        $collection->addFieldToFilter('backend_type', array('varchar'));
        $collection->setOrder('attribute_code');
        
        /** @var Attribute $attribute */
        foreach ($collection as $attribute) {


           $arr[$attribute->getAttributeId()] = $attribute->getFrontendLabel();
        }
        return $arr;
	}
}