<?php

namespace Amasty\Feed\Model\Export\RowCustomizer;

use Amasty\Feed\Api\CustomFieldsRepositoryInterface;
use Amasty\Feed\Model\Export\Product as Export;
use Amasty\Feed\Model\Field\ResourceModel\CollectionFactory as FieldCollectionFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogImportExport\Model\Export\RowCustomizerInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class CustomField implements RowCustomizerInterface
{
    /**#@+
     * Modifier constants
     */
    const OPERATION = 0;

    const VALUE = 1;
    /**#@-*/

    /**
     * @var array
     */
    private $conditions = [];

    /**
     * @var Export
     */
    private $export;

    /**
     * @var \Amasty\Feed\Model\Field\ResourceModel\Collection
     */
    private $fieldCollection;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CustomFieldsRepositoryInterface
     */
    private $cFieldsRepository;

    public function __construct(
        Export $export,
        CustomFieldsRepositoryInterface $cFieldsRepository,
        ProductRepositoryInterface $productRepository,
        FieldCollectionFactory $collectionFactory
    ) {
        $this->export = $export;
        $this->cFieldsRepository = $cFieldsRepository;
        $this->productRepository = $productRepository;
        $this->fieldCollection = $collectionFactory->create();
    }

    /**
     * {@inheritdoc}
     *
     * @throws NoSuchEntityException
     */
    public function prepareData($collection, $productIds)
    {
        if ($this->export->hasAttributes(Export::PREFIX_CUSTOM_FIELD_ATTRIBUTE) && !$this->conditions) {
            $attributes = $this->export->getAttributesByType(Export::PREFIX_CUSTOM_FIELD_ATTRIBUTE);
            $data = $this->fieldCollection->getCustomConditions($attributes);

            if ($data) {
                foreach ($data as $record) {
                    $this->conditions[$record['code']][] = ['id' => $record['entity_id'], 'code' => $record['code']];
                }
            }

            $conformityArray = array_diff_key($attributes, $this->conditions);

            if ($conformityArray) {
                throw new NoSuchEntityException(
                    __(
                        'Error(s) occurred during feed generation, attribute code(s): "%1"',
                        implode(",", $conformityArray)
                    )
                );
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function addHeaderColumns($columns)
    {
        return $columns;
    }

    /**
     * @inheritdoc
     */
    public function addData($dataRow, $productId)
    {
        $dataRow['amasty_custom_data'][Export::PREFIX_CUSTOM_FIELD_ATTRIBUTE] = [];
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->productRepository->getById($productId);

        foreach ($this->conditions as $customField) {
            foreach ($customField as $condition) {
                /** @var \Amasty\Feed\Model\Field\Condition $rule */
                $rule = $this->cFieldsRepository->getConditionModel($condition['id']);

                if ($rule->getConditions()->validate($product)) {
                    $attributeValue = null;
                    if (is_array($product->getData('tier_price'))
                        && empty($product->getData('tier_price'))
                    ) {
                        $product->setData('tier_price', '');
                    }

                    if (!empty($rule->getFieldResult()['attribute'])) {
                        $currentAttribute = $rule->getFieldResult()['attribute'];
                        $productAttribute = $product->getData($currentAttribute);

                        if ($product->getAttributeText($currentAttribute) && !is_array($productAttribute)) {
                            $attributeValue = $product->getAttributeText($currentAttribute);
                        } else {
                            $attributeValue = $productAttribute;
                        }
                    }
                    $dataRow['amasty_custom_data'][Export::PREFIX_CUSTOM_FIELD_ATTRIBUTE][$condition['code']] =
                        $this->modifyValue($attributeValue, $rule);

                    break;
                }
            }
        }

        return $dataRow;
    }

    /**
     * @inheritdoc
     */
    public function getAdditionalRowsCount($additionalRowsCount, $productId)
    {
        return $additionalRowsCount;
    }

    /**
     * @param array|string|null $value
     * @param \Amasty\Feed\Model\Field\Condition $rule
     *
     * @return float|int|string
     */
    private function modifyValue($value, $rule)
    {
        $modifier = isset($rule->getFieldResult()['modify']) ? $rule->getFieldResult()['modify'] : '';

        //If value is null no sense to check modifier.
        if ($value === '' || $value === null) {
            return $modifier;
        }

        //If modifier is set, should check value is numeric, and return modified value or modifier itself.
        if ($modifier) {
            if (is_numeric($value)) {
                return $this->modifyNumeric($modifier, $value);
            }

            return $modifier;
        }

        //If modifier is null, return attribute value.
        //If attribute value consists of several ones, return them as one string.
        if (is_array($value)) {
            $value = implode(', ', $value);
        }

        return $value;
    }

    /**
     * Return modified value or modifier itself if modifier does not match the pattern.
     * Modifier patterns: (+ or -)number(%).
     *
     * @param string $modifier
     * @param float|int $value
     *
     * @return float|int
     */
    private function modifyNumeric($modifier, $value)
    {
        $modifierArray =
            preg_split('/([\d]+([.,][\d]+)?)/', $modifier, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $modifierValue = isset($modifierArray[self::VALUE]) ? str_replace(',', '.', $modifierArray[self::VALUE]) : 0;

        if ($modifierValue && end($modifierArray) === '%') {
            $modifierValue = $value * $modifierValue / 100;
        }

        switch ($modifierArray[self::OPERATION]) {
            case '-':
                $value -= $modifierValue;
                break;
            case '+':
                $value += $modifierValue;
                break;
            default:
                $value = $modifier;
                break;
        }

        return $value;
    }
}
