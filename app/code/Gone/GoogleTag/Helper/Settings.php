<?php

namespace Gone\GoogleTag\Helper;

use Gone\Base\Helper\CoreConfigData;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Settings extends AbstractHelper
{
    const GOOGLE_TAG_CORE_CONFIG_KEY = "analytics/gtag/gtag_id";

    protected CoreConfigData $_coreConfigData;

    public function __construct(
        Context $context,
        CoreConfigData $coreConfigData
    )
    {
        parent::__construct($context);
        $this->_coreConfigData = $coreConfigData;
    }

    public function getGoogleTagId()
    {
       return $this->_coreConfigData->getValueFromCoreConfig(self::GOOGLE_TAG_CORE_CONFIG_KEY);
    }
}
