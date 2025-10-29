<?php

namespace Gone\Subligraphy\Block;

use Gone\Subligraphy\Helper\SubligraphyConfig;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\View\Element\Template;

class FormCertificates extends Template
{

    private DateTime $_date;

    public function __construct(
        Template\Context $context,
        DateTime $date,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
        $this->_date = $date;
    }

    /**
     * @return string
     */
    public function getCurrentYear():string
    {
        return $this->_date->date('Y');
    }

    public function getFileMaxSize():int
    {
        return SubligraphyConfig::MAX_IMG_SIZE;
    }

    /**
     * @return string
     */
    public function getFormActionUrl():string
    {
        return $this->getUrl('subligraphie/certificate/createpost');
    }

    /**
     * @param $url
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImageUrl($url)
    {
        return $this->getMediaUrl().SubligraphyConfig::CERTIFICATE_MEDIA_BASE_URL . $url;
    }
}
