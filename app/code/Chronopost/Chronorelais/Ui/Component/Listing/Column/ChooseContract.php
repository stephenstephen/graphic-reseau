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
use Chronopost\Chronorelais\Helper\Webservice;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class ChooseContract
 *
 * @package Chronopost\Chronorelais\Ui\Component\Listing\Column
 */
class ChooseContract extends Column
{

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var Webservice
     */
    protected $helperWS;

    /**
     * LivraisonSamedi constructor.
     *
     * @param ContextInterface     $context
     * @param UiComponentFactory   $uiComponentFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param Data                 $helper
     * @param Webservice           $helperWS
     * @param array                $components
     * @param array                $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ScopeConfigInterface $scopeConfig,
        Data $helper,
        Webservice $helperWS,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
        $this->helperWS = $helperWS;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['shipment_id'])) {
                    $entity_id = $item['entity_id'];
                    $contract = $this->helper->getContractByOrderId($entity_id);

                    if (!$contract) {
                        $render = "<select style='font-size: 12px;' data-entityid='" . $entity_id . "' id='contract-" . $entity_id . "'>";
                        $contracts = $this->helper->getConfigContracts();
                        foreach ($contracts as $key => $contract) {
                            $shippingMethodCode = explode('_', $item['shipping_method']);
                            $shippingMethodCode = isset($shippingMethodCode[1]) ? $shippingMethodCode[1] :
                                $shippingMethodCode[0];
                            if (!$this->helperWS->shippingMethodEnabled($shippingMethodCode, $key)) {
                                continue;
                            }

                            $defaultContract = $this->helper->getCarrierContract($shippingMethodCode);
                            $selected = ($key === $defaultContract['numContract']) ? 'selected' : '';
                            $render .= '<option value="' . $key . '" ' . $selected . ' >' . $contract['name'] . '</option>';
                        }

                        $render .= '<select>';
                    } else {
                        $render = $contract->getData('contract_name');
                    }

                    $item[$this->getData('name')] = $render;
                }
            }
        }

        return $dataSource;
    }
}
