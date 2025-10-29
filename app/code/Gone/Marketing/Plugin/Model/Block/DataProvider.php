<?php

namespace Gone\Marketing\Plugin\Model\Block;

use Magento\Framework\Serialize\Serializer\Json;

class DataProvider
{

    protected Json $_json;

    public function __construct(
        Json $json
    )
    {
        $this->_json = $json;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function afterGetData(
        \Magento\Cms\Model\Block\DataProvider $subject,
        $result
    )
    {
        if (is_array($result)) {
            foreach ($result as &$item) {
                if (isset($item['segments_assignation']) && $item['segments_assignation']) {
                    $item['segments_assignation'] = $this->_json->unserialize($item['segments_assignation']);
                }
            }
        }
        return $result;
    }
}
