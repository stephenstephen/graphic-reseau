<?php
/**
 * Chronopost
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category  Chronopost
 * @package   Chronopost_Chronorelais
 * @copyright Copyright (c) 2021 Chronopost
 */
declare(strict_types=1);

namespace Chronopost\Chronorelais\Model\Carrier;

/**
 * Class ChronopostC18
 *
 * @package Chronopost\Chronorelais\Model\Carrier
 */
class ChronopostC18 extends AbstractChronopost
{
    /**
     * @var string
     */
    protected $_code = 'chronopostc18';

    const PRODUCT_CODE = '16';
    const PRODUCT_CODE_STR = '18H';
    const CHECK_CONTRACT = true;
    const OPTION_BAL_ENABLE = true;
    const PRODUCT_CODE_BAL = '2M';
    const PRODUCT_CODE_BAL_STR = '18H BAL';
    const DELIVER_ON_SATURDAY = true;
}
