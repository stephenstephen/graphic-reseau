<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Gone\Payment\Model\OfflinePayments;

use Magento\Framework\Escaper;
use Magento\Payment\Helper\Data as PaymentHelper;

class InstructionsConfigProvider extends \Magento\OfflinePayments\Model\InstructionsConfigProvider
{

    /**
     * Get instructions text from config
     *
     * @param string $code
     * @return string
     */
    protected function getInstructions($code)
    {
        return nl2br(
            $this->escaper->escapeHtml(
                $this->methods[$code]->getInstructions(),
                ['font','b','span','strong']
            )
        );
    }
}
