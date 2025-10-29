<?php
namespace AtooSync\GesCom\Model\Config\Backend;

use Magento\Eav\Model\Entity\TypeFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;
use Magento\Framework\Option\ArrayInterface;

class AttributeSetList implements ArrayInterface
{
    protected $CollectionFactory;
    protected $eavTypeFactory;
	
	public function __construct(
        CollectionFactory $CollectionFactory,
        TypeFactory $typeFactory
    )
    {
        $this->CollectionFactory = $CollectionFactory;
        $this->eavTypeFactory = $typeFactory;
    }

	public function toOptionArray(){

		$arr = $this->_toArray();
		$ret = [];
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
        
        $attributeSetCollection = $this->CollectionFactory->create();
        $attributeSets = $attributeSetCollection->getItems();
        foreach ($attributeSets as $attributeSet) {
            if ($attributeSet['entity_type_id'] == $entityType->getId()) {
                $arr[$attributeSet['attribute_set_id']] = $attributeSet['attribute_set_name'];
            }
        }
        return $arr;
	}
}
