<?php
/**
 * Chronopost
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category  Chronopost
 * @package   Chronopost_Chronorelais
 * @copyright Copyright (c) 2021 Chronopost
 */
declare(strict_types=1);

namespace Chronopost\Chronorelais\Ui\Component\Listing\Column;

use Chronopost\Chronorelais\Helper\Data;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Store\Model\StoreManagerInterface;


use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class MultiColis
 *
 * @package Chronopost\Chronorelais\Ui\Component\Listing\Column
 */
class MultiColis extends Column
{

    protected $scopeConfig;
    protected $helper;
    protected $urlBuilder;
    protected $storeManager;
    protected $formKey;

    /**
     * LivraisonSamedi constructor.
     *
     * @param ContextInterface      $context
     * @param UiComponentFactory    $uiComponentFactory
     * @param ScopeConfigInterface  $scopeConfig
     * @param Data                  $helper
     * @param UrlInterface          $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param FormKey               $formKey
     * @param array                 $components
     * @param array                 $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ScopeConfigInterface $scopeConfig,
        Data $helper,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        FormKey $formKey,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->storeManager = $storeManager;
        $this->formKey = $formKey;
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     * @throws LocalizedException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['shipment_id'])) {
                    $contractNum = false;
                    $entity_id = $item['entity_id'];

                    $contract = $this->helper->getContractByOrderId($entity_id);
                    if (!$contract) {
                        $shippingMethod = $item['shipping_method'];
                        $shippingMethodCode = explode("_", $shippingMethod);
                        $shippingMethodCode = isset($shippingMethodCode[1]) ? $shippingMethodCode[1] :
                            $shippingMethodCode[0];
                        $contract = $this->helper->getCarrierContract($shippingMethodCode);
                        $contractNum = $contract['numContract'];
                    }

                    $indexFieldValues = explode(',', $item['shipment_id']);
                    if ($item['shipment_id'] === '--') {
                        $indexFieldValues = [$item['entity_id']];
                    }

                    $item[$this->getData('name')] = '';
                    $url = $this->urlBuilder->getUrl($this->getData('config/viewUrlPathGenerate') ?: '#');
                    $totalWeight = $this->helper->getWeightOfOrder($item['entity_id'], true);
                    $dimensions = '{"0":{"weight":"' . $totalWeight . '","width":"1","height":"1","length":"1"}}';

                    $render = '<form class="form_' . $item['entity_id'] . '" id="form_' . $item['entity_id'] . '" action="' . $url . '" method="post">';
                    $render .= '<input type="hidden" id="order_dimensions" class="dimensions container" name="dimensions" value=' . $dimensions . ' />';
                    $render .= '<input type="hidden" name="order_id" value="' . $item['entity_id'] . '" />';
                    $render .= '<input name="form_key" type="hidden" value="' . $this->formKey->getFormKey() . '" />';
                    $render .= "<input type='hidden' value='" . $contractNum . "' name='contract'/>";
                    $render .= "<input style='margin-bottom:5px;width:100%;text-align:center;' data-orderid='" . $item['entity_id'] . "' type='text' name='nb_colis' value='1'/>";

                    if (count($indexFieldValues) === 1) {
                        $render .= '<input name="shipment_id" type="hidden" value="' . $item['shipment_id'] . '" />';
                    } else {
                        $render .= '<select style="margin-bottom:5px;text-align:center;" name="shipment_id" required >';
                        $render .= '<option value="">' . __("Select a shipment") . '</option>';
                        foreach ($indexFieldValues as $indexFieldValue) {
                            $render .= '<option value="' . $indexFieldValue . '">' . $indexFieldValue . '</option>';
                        }
                        $render .= '</select>';
                    }

                    $render .= "<button style='width: 100%;' type='submit'>" . __('Generate') . "</button>";
                    $render .= '</form>';
                    $item[$this->getData('name')] = $render;
                }
            }
        }

        return $dataSource;
    }
}
