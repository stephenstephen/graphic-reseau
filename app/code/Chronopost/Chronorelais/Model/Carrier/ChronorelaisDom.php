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
 * Class ChronorelaisDom
 *
 * @package Chronopost\Chronorelais\Model\Carrier
 */
class ChronorelaisDom extends AbstractChronopost
{
    /**
     * @var string
     */
    protected $_code = 'chronorelaisdom';

    const PRODUCT_CODE = '4P';
    const PRODUCT_CODE_STR = 'PRDOM';
    const CHECK_CONTRACT = true;
    const CHECK_RELAI_WS = true;
}
