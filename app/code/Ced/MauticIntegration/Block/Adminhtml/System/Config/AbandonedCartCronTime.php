<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 15/12/18
 * Time: 4:14 PM
 */

namespace Ced\MauticIntegration\Block\Adminhtml\System\Config;

class AbandonedCartCronTime implements \Magento\Framework\Option\ArrayInterface
{
    const ONCE_IN_A_DAY = '*/60 */24 * * *';
    const TWICE_A_DAY  = '*/60 */12 * * *';
    const FOUR_TIMES_A_DAY = '*/60 */6 * * *';
    const EVERY_HOUR = '*/60 */1 * * *';
    const EVERY_FIVE_MINUTES = "*/5 * * * *";
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['label'=>'Once In A Day','value'=>$this::ONCE_IN_A_DAY],
            ['label'=>'Twice A Day', 'value'=>$this::TWICE_A_DAY],
            ['label'=>'Four Times A Day', 'value'=>$this::FOUR_TIMES_A_DAY],
            ['label'=>'Every Hour', 'value'=>$this::EVERY_HOUR]
        ];
    }
}
