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

namespace Chronopost\Chronorelais\Model\Config\Source;

use Chronopost\Chronorelais\Helper\Data;
use Magento\Framework\Option\ArrayInterface;

/**
 * Class Contract
 *
 * @package Chronopost\Chronorelais\Model\Config\Source
 */
class Contract implements ArrayInterface
{

    /**
     * @var Data
     */
    protected $helper;

    /**
     * Contract constructor.
     *
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $contracts = $this->helper->getConfigContracts();

        $to_return = [];
        foreach ($contracts as $number => $contract) {
            array_push($to_return, [
                'value' => $number,
                'label' => $contract['name']
            ]);
        }

        return $to_return;
    }
}
