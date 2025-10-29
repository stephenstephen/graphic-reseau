<?php

namespace Gone\Troiscentsoixante\Block\Product\View;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\View\Gallery;
use Magento\Catalog\Model\Product\Gallery\ImagesConfigFactoryInterface;
use Magento\Catalog\Model\Product\Image\UrlBuilder;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Stdlib\ArrayUtils;
use function GuzzleHttp\Psr7\str;

class Troiscentsoixante extends Gallery
{

    public const CONFIG_VIEWER = [
        'frame_width' => 'troiscentsoixante/general/frame_width',
        'frame_height' => 'troiscentsoixante/general/frame_height',
        'x_frame_number' => 'troiscentsoixante/general/x_frame_number',
        'y_frame_number' => 'troiscentsoixante/general/y_frame_number'
    ];

    protected array $_viewerConfig;
    protected Context $_context;

    public function __construct(
        Context $context,
        ArrayUtils $arrayUtils,
        EncoderInterface $jsonEncoder,
        ImagesConfigFactoryInterface $imagesConfigFactory = null,
        UrlBuilder $urlBuilder = null,
        array $galleryImagesConfig = [],
        array $data = []
    ) {
        parent::__construct(
            $context,
            $arrayUtils,
            $jsonEncoder,
            $data,
            $imagesConfigFactory,
            $galleryImagesConfig,
            $urlBuilder
        );

        $this->_context = $context;
    }
//core helper
    /**
     * @return string|false
     */
    public function getTroiscentsoixanteSpriteUrl()
    {
        $url = $this->getImage($this->getProduct(), 'troiscentsoixante_img')->getImageUrl();
        return strstr($url, 'placeholder') ? false : $url;
    }

    /**
     * @return array
     */
    public function getSpriteSpinnerConfigArray(): array
    {
        if (!isset($this->_viewerConfig)) {
            $this->_viewerConfig = [];
            foreach (self::CONFIG_VIEWER as $key => $path) {
                $this->_viewerConfig[$key] = $this->_context->getScopeConfig()->getValue($path);
            }
        }
        return $this->_viewerConfig;
    }
}
