<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class PriceActions
 */
class PriceActions extends Column
{
    const PRICE_URL_PATH_EDIT      = 'colissimo_shipping/price/edit';

    const PRICE_URL_PATH_DELETE    = 'colissimo_shipping/price/delete';

    const PRICE_URL_PATH_DUPLICATE = 'colissimo_shipping/price/duplicate';

    /**
     * @var UrlInterface $urlBuilder
     */
    protected $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['pk'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::PRICE_URL_PATH_EDIT,
                                [
                                    'price_id' => $item['pk']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'duplicate' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::PRICE_URL_PATH_DUPLICATE,
                                [
                                    'price_id' => $item['pk']
                                ]
                            ),
                            'label' => __('Duplicate')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::PRICE_URL_PATH_DELETE,
                                [
                                    'price_id' => $item['pk']
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete'),
                                'message' => __('Are you sure you want to delete item?')
                            ]
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
