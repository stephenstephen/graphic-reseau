<?php

namespace Gone\Subligraphy\Block;

use Gone\Subligraphy\Helper\SubligraphyConfig;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;

class CheckCertificate extends Template
{
    protected SubligraphyConfig $_subligraphyConfig;

    public function __construct(
        Template\Context $context,
        SubligraphyConfig $subligraphyConfig,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
        $this->_subligraphyConfig = $subligraphyConfig;
        $this->_context=$context;
    }

    /**
     * @return string
     */
    public function getFormActionUrl():string
    {
        return $this->getUrl('subligraphie/certificate/check');
    }

    /**
     * @param $url
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImage($url):string
    {
        return $this->_subligraphyConfig->getImage($url);
    }
}
