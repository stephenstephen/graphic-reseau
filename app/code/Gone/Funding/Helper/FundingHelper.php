<?php

namespace Gone\Funding\Helper;

use Gone\Base\Helper\CoreConfigData;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class FundingHelper extends AbstractHelper
{
    const MONTHS_DURATION_CONF = 'funding/duration/months';
    const RATIOS_MAPPING = 'funding/ratios/mapping';

    protected CoreConfigData $_configDataHelper;
    protected $_mmonthsDuration;

    public function __construct(
        Context $context,
        CoreConfigData $configDataHelper
    )
    {
        parent::__construct($context);
        $this->_configDataHelper = $configDataHelper;
    }

    /**
     * @return array
     */
    public function getMonthsDurationFlat()
    {
        return array_map(function ($month) {
            return $month['value'];
        }, $this->getMonthsDuration());
    }

    /**
     * @return array
     */
    public function getMonthsDuration()
    {
        if (!isset($this->_mmonthsDuration)) {
            $monthDurationArr = $this->_configDataHelper->getDataFromSerializeArray(self::MONTHS_DURATION_CONF);
            $return = [];
            foreach ($monthDurationArr as $duration) {
                $return[] = [
                    'label' => $duration['duration'] . ' mois',
                    'value' => $duration['duration']
                ];
            }
            $this->_mmonthsDuration = $return;
        }
        return $this->_mmonthsDuration;
    }

    /**
     * @return array
     */
    public function getRatios()
    {
        return $this->_configDataHelper->getDataFromSerializeArray(self::RATIOS_MAPPING) ?? [];
    }

}
