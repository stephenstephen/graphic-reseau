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
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class PrintAction
 *
 * @package Chronopost\Chronorelais\Ui\Component\Listing\Column
 */
class PrintAction extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var FormKey
     */
    protected $formKey;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * Constructor
     *
     * @param ContextInterface      $context
     * @param UiComponentFactory    $uiComponentFactory
     * @param UrlInterface          $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param FormKey               $formKey
     * @param Data                  $helper
     * @param array                 $components
     * @param array                 $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        FormKey $formKey,
        Data $helper,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->storeManager = $storeManager;
        $this->formKey = $formKey;
        $this->helper = $helper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['shipment_id'])) {
                    $shipmentIds = [];
                    if ($item['shipment_id'] !== '--') {
                        $shipmentIds = explode(',', $item['shipment_id']);
                    }

                    $trackNumbers = [];
                    if ($item['track_number'] !== '--') {
                        $trackNumbers = explode(',', $item['track_number']);
                    }

                    // If no tracking number, label canceled or never generated
                    if (!count($trackNumbers)) {
                        continue;
                    }

                    $item[$this->getData('name')] = '';

                    $urlEntityParamName = 'track_number';
                    $indexFieldValues = $trackNumbers;

                    if (count($shipmentIds) > count($trackNumbers)) {
                        $urlEntityParamName = 'shipment_id';
                        $indexFieldValues = $shipmentIds;
                    }

                    foreach ($indexFieldValues as $indexFieldValue) {
                        $url = $this->urlBuilder->getUrl(
                            $this->getData('config/viewUrlPath') ?: 'javascript:void(0);',
                            [$urlEntityParamName => trim($indexFieldValue)]
                        );

                        $render = '';
                        if (count($indexFieldValues)) {
                            $render = '<a class="printlink" href="' . $url . '">' . trim($indexFieldValue) .
                                '</a><br />';
                        }

                        $item[$this->getData('name')] .= $render;
                    }
                }
            }
        }

        return $dataSource;
    }
}
