<?php

namespace Gone\Subligraphy\Block;

use Gone\Subligraphy\Helper\SubligraphyConfig;
use Magento\Customer\Model\Context;
use Magento\Customer\Block\Account\SortLinkInterface;
use Magento\Framework\App\DefaultPathInterface;
use Magento\Framework\Math\Random;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

class SubligraphyLink extends \Magento\Framework\View\Element\Html\Link\Current implements SortLinkInterface
{

    protected SubligraphyConfig $_subligraphyConfig;

    public function __construct(
        SubligraphyConfig $subligraphyConfig,
        \Magento\Framework\View\Element\Template\Context $context,
        DefaultPathInterface $defaultPath,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $defaultPath,
            $data
        );

        $this->_subligraphyConfig=$subligraphyConfig;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->_subligraphyConfig->isSubligraphAuth()) {
            return parent::_toHtml();
        }
        return '';
    }

    /**
     * {@inheritdoc}
     * @since 100.2.0
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }
}
