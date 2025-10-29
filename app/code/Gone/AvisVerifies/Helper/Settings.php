<?php

namespace Gone\AvisVerifies\Helper;

use Gone\Base\Helper\CoreConfigData;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Settings extends AbstractHelper
{
    const WIDGET_CODE = "av_configuration/widget_integration/widget_code";

    protected CoreConfigData $_coreConfigData;

    public function __construct(
        Context $context,
        CoreConfigData $coreConfigData
    )
    {
        parent::__construct($context);
        $this->_coreConfigData = $coreConfigData;
    }

    public function getWigetCode()
    {
       return $this->_coreConfigData->getValueOnCurrentStore(self::WIDGET_CODE);
    }
}
