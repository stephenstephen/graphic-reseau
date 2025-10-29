<?php

namespace Gone\Catalog\Plugin;

use Magento\Framework\Filter\TranslitUrl;

class FormatUrlKeyPlugin
{

    protected TranslitUrl $_translit;

    /**
     * FormatUrlKeyPlugin constructor.
     * @param TranslitUrl $translit
     */
    public function __construct(TranslitUrl $translit)
    {
        $this->_translit = $translit;
    }

    /**
     * Prevent Magento from replacing slashes in url_key
     *
     * @param \Magento\Catalog\Model\Product\Url $subject
     * @param callable $proceed
     * @param $str
     * @return mixed
     */
    public function aroundFormatUrlKey(\Magento\Catalog\Model\Product\Url $subject, callable $proceed, $str)
    {
        $str = preg_replace('/[+]/', 'plus', $str);
        $str = strtolower($str);
        $str = trim($str, '-');
        return $this->_translit->filter($str);
    }
}
