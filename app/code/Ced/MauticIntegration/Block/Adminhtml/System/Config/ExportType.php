<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 12/12/18
 * Time: 5:34 PM
 */

namespace Ced\MauticIntegration\Block\Adminhtml\System\Config;

class ExportType implements \Magento\Framework\Option\ArrayInterface
{
    const OBSERVER = 'observer';
    const CRON  = 'cron';
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => $this::OBSERVER, 'label' => 'Observer'],
            ['value' => $this::CRON, 'label' => 'Cron']];
    }
}
