<?php
namespace Netreviews\Avisverifies\Block\Adminhtml\Management\Edit;

use \Magento\Backend\Block\Widget\Form\Generic;

class Form extends Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    /**
     * @var \Magento\Framework\Data\FormFactory
     */
    protected $_formFactory;
    /**
     * @var \Magento\Framework\App\ObjectManager
     */
    protected $orderStatusCollection;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $orderStatusCollection,
        array $data = array()
    ) {
        $this->_systemStore = $systemStore;
        $this->_formFactory = $formFactory;
        $this->orderStatusCollection = $orderStatusCollection;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('management_form');
        $this->setTitle(__('Management Information'));
    }

    /**
     * @return Generic
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(array(
                'data' => array(
                    'id' => 'edit_form',
                    'action' => $this->getUrl('netreviews_avisverifies_admin_management/*/*'),
                    'method' => 'post'
                )
            ));
        $form->setHtmlIdPrefix('management_');
        $fieldset = $form->addFieldset('base_fieldset', array(
                'legend' => __('Export orders (CSV file)'),
                'class' => 'fieldset-wide'
            ));
        $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        $fieldset->addField('store_ids', 'select', array(
                'name' => 'store_ids[]',
                'label' => __('Store views'),
                'title' => __('Store views title'),
                'note' => __('Before all please select the store to export orders.'),
                'class' => 'required-entry',
                'required' => true,
                //'values'   => $this->_systemStore->getWebsiteValuesForForm(true, true)
                'values' => $this->_systemStore->getStoreValuesForForm(false, true)
            ));
        // Get All Status
        $arrayStatusCollectionSimplified = $this->orderStatusCollection->create()->load()->toOptionHash();
        $a_status = array();
        $i = 0;
        foreach ($arrayStatusCollectionSimplified as $key => $value) {
            $a_status[$i]['value'] = $key;
            $a_status[$i]['label'] = $value;
            $i++;
        }
        $fieldset->addField('checkboxStatus', 'checkboxes', [
                'name' => 'checkboxStatus[]',
                'label' => __('Order status'),
                'note' => __('Keep all the options unchecked to get orders from all status.'),
                'class' => 'required-entry',
                'values' => $a_status
                //'checked'   => array('fraud','processing')
            ]);
        $fieldset->addField('fromDate', 'date', array(
                'name' => 'fromDate',
                'label' => __('From'),
                'title' => __('From'),
                'note' => __('Leave it empty to get orders from the beginning of time.'),
                'format' => 'yy-mm-dd',
                'input_format' => 'yy-mm-dd'
            ));
        $fieldset->addField('toDate', 'date', array(
                'name' => 'toDate',
                'label' => __('To'),
                'title' => __('To'),
                'note' => __('Leave it empty to use the current date.'),
                'format' => 'yy-mm-dd',
                'input_format' => 'yy-mm-dd'
            ));
        $fieldset->addField('selectProducts', 'select', array(
                'name' => 'selectProducts',
                'label' => __('Request product reviews?'),
                'note' => __('It add product information to orders.'),
                'class' => 'required-entry',
                'required' => true,
                'values' => array(
                    array(
                        'value' => 0,
                        'label' => __('No')
                    ),
                    array(
                        'value' => 1,
                        'label' => __('Yes')
                    )
                )
            ));
        $fieldset = $form->addFieldset('title_export', array(
                'legend' => __('Export Reviews (CSV file)'),
                'class' => 'fieldset-wide'
            ));
        $fieldset->addField('selectReviews', 'select', array(
                'name' => 'selectReviews',
                'label' => __('Retrieve product Reviews'),
                'note' => __('It will retrieve reviews from Magento default Module. Please select one Store'),
                'class' => 'required-entry',
                'required' => true,
                'values' => array(
                    array(
                        'value' => 0,
                        'label' => __('No')
                    ),
                    array(
                        'value' => 1,
                        'label' => __('Yes')
                    )
                )
            ));
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
