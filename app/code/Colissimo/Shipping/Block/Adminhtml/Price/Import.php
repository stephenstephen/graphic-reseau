<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2017 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Block\Adminhtml\Price;

use Colissimo\Shipping\Helper\Data as ShippingHelper;
use Magento\Backend\Block\Template;
use Magento\Backend\Model\UrlFactory;
use Magento\Framework\File\Size;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\UrlInterface;

/**
 * Class Import
 */
class Import extends Template
{

    /**
     * @var UrlInterface $url
     */
    protected $url;

    /**
     * @var Size $fileConfig
     */
    protected $fileConfig;

    /**
     * @var int $maxFileSize
     */
    protected $maxFileSize;

    /**
     * @param UrlFactory $backendUrlFactory
     * @param Size $fileConfig
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        UrlFactory $backendUrlFactory,
        Size $fileConfig,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->url = $backendUrlFactory->create();
        $this->fileConfig = $fileConfig;
        $this->maxFileSize = $this->getFileMaxSize();
    }

    /**
     * Return element html code
     *
     * @return string
     * @phpcs:disable
     */
    public function _toHtml()
    {
        $this->assign([
            'htmlId' => 'colissimo-price-file',
            'fileMaxSize' => $this->maxFileSize,
            'uploadUrl' => $this->_escaper->escapeHtml($this->getUploadUrl()),
            'runUrl' => $this->_escaper->escapeHtml($this->getRunUrl()),
            'filePlaceholderText' => __('Click here or drag and drop to add files.'),
            'importFileText' => __('Import'),
            'backUrl' => $this->_escaper->escapeHtml($this->getBackUrl())
        ]);

        return parent::_toHtml();
    }

    /**
     * Get url to upload files
     *
     * @return string
     */
    protected function getUploadUrl()
    {
        return $this->url->getUrl('*/*/upload');
    }

    /**
     * Retrieve run URL
     *
     * @return string
     */
    public function getRunUrl()
    {
        return $this->url->getUrl('colissimo_shipping/price/runImport');
    }

    /**
     * Retrieve back URL
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->url->getUrl('colissimo_shipping/price/index');
    }

    /**
     * Retrieve mode Erase value
     *
     * @return int
     */
    public function getModeErase()
    {
        return ShippingHelper::COLISSIMO_IMPORT_MODE_ERASE;
    }

    /**
     * Retrieve mode Append value
     *
     * @return int
     */
    public function getModeAppend()
    {
        return ShippingHelper::COLISSIMO_IMPORT_MODE_APPEND;
    }

    /**
     * Get maximum file size to upload in bytes
     *
     * @return int
     */
    protected function getFileMaxSize()
    {
        return $this->fileConfig->getMaxFileSize();
    }
}
