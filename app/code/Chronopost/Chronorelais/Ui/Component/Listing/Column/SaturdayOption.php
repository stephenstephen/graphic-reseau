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

use Chronopost\Chronorelais\Helper\Data as HelperData;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class SaturdayOption
 *
 * @package Chronopost\Chronorelais\Ui\Component\Listing\Column
 */
class SaturdayOption extends Column
{

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * SaturdayOption constructor.
     *
     * @param ContextInterface     $context
     * @param UiComponentFactory   $uiComponentFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param array                $components
     * @param array                $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ScopeConfigInterface $scopeConfig,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->scopeConfig = $scopeConfig;
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
                $shippingMethod = explode('_', $item['shipping_method']);
                $shippingMethod = isset($shippingMethod[1]) ? $shippingMethod[1] : $shippingMethod[0];

                $shippingMethodsAllowed = HelperData::SHIPPING_METHODS_SATURDAY_ALLOWED;
                if (!in_array($shippingMethod, $shippingMethodsAllowed)) {
                    $item[$this->getData('name')] = __('Option not available for this delivery method');
                    continue;
                }

                $saturdayDelivery = $item[$this->getData('name')];
                $saturdayDeliveryLabel = ($saturdayDelivery === '1') ? __('Yes') : __('No');

                $message = __('It is possible to update the value via the drop-down '.
                    'list located at the top left of the grid.');
                $saturdayDeliveryLabel .= ' <a href="javascript: void(0);" title="' . $message . '">(?)</a>';

                $item[$this->getData('name')] = $saturdayDeliveryLabel;
            }
        }

        return $dataSource;
    }
}
