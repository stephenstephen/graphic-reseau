<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Subligraphy\Ui\Component\Listing\Column;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class CertificateActions extends Column
{
    /**
     * @var Filesystem
     */
    protected Filesystem $_filesystem;
    protected $urlBuilder;
    public const URL_PATH_DELETE = 'gone_subligraphy/certificate/delete';
    public const URL_PATH_DL = 'gone_subligraphy/certificate/download';

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param Filesystem $filesystem
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        Filesystem $filesystem,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->_filesystem = $filesystem;
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
                if (isset($item['certificate_id'])) {

                    $href = '';
                    if ($item['filename'] != '') {
                        $path = $this->_filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
                        $url = $path->getAbsolutePath($item['filename']);
                        if ($url) {
                            $href = $this->context->getUrl(self::URL_PATH_DL, ['file'=> base64_encode($item['filename'])]);
                        }
                    }

                    $item[$this->getData('name')] = [
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'certificate_id' => $item['certificate_id']
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete"'),
                                'message' => __('Are you sure you wan\'t to delete this record?')
                            ]
                        ],
                        'download' => [
                            'href' => $href,
                            'label' => $href ? __('Download') : __('Not available')
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
