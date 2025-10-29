<?php
/**
 *   Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 *   See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace Gone\Funding\Block\Adminhtml\Config\Form\Field;

use Gone\Funding\Helper\FundingHelper;
use Magento\Backend\Block\Template\Context;

/**
 * Backend system config array field renderer
 */
class FundingMapping extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{

    protected FundingHelper $_fundingHelper;

    /**
     * Grid columns
     *
     * @var array
     */
    protected $_columns = [];

    /**
     * Enable the "Add after" button or not
     *
     * @var bool
     */
    protected $_addAfter = true;
    /**
     * Label of add button
     *
     * @var string
     */
    protected $_addButtonLabel;

    public function __construct(
        Context $context,
        FundingHelper $fundingHelper,
        array $data = [],
        ?\Magento\Framework\View\Helper\SecureHtmlRenderer $secureRenderer = null
    )
    {
        parent::__construct(
            $context,
            $data,
            $secureRenderer
        );
        $this->_fundingHelper = $fundingHelper;
    }

    /**
     * Render array cell for prototypeJS template
     *
     * @param string $columnName
     * @return string
     * @throws \Exception
     */
    public
    function renderCellTemplate($columnName)
    {
        if ($columnName == "active") {
            $this->_columns[$columnName]['class'] = 'input-text required-entry validate-number';
            $this->_columns[$columnName]['style'] = 'width:50px';
        }
        return parent::renderCellTemplate($columnName);
    }

    /**
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'value_mini',
            [
                'label' => __('Valeur Mini €'),
                'style' => 'width: 80px',
                "class" => "required-entry"

            ]
        );
        $this->addColumn(
            'value_max',
            [
                'label' => __('Valeur Max €'),
                'style' => 'width: 80px',
                'class' => "required-entry",
                'default' => 0,
                'value' => 99
            ]
        );

        foreach ($this->_fundingHelper->getMonthsDuration() as $duration) {
            $this->addColumn(
                'duration_' . $duration['value'],
                [
                    'label' => __($duration['label']),
                    'style' => 'width: 80px',
                    'class' => 'required-entry'
                ]
            );
        }

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * @param \Magento\Framework\DataObject $row
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {

        $availableMonths = $this->_fundingHelper->getMonthsDuration();
        foreach ($availableMonths as $duration) {
            $key = 'duration_' . $duration['value'];
            if (!array_key_exists($key, $row->getData())) {
                $newColumnsValues = array_merge(
                    $row->getData('column_values'),
                    [$row->getData('_id') . '_' . $key => 0]
                );
                $row->setData($key, "0");
                $row->setData('column_values', $newColumnsValues);
            }
        }
    }
}
