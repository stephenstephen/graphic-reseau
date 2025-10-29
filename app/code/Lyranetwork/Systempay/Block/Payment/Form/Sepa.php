<?php
/**
 * Copyright © Lyra Network.
 * This file is part of Systempay plugin for Magento 2. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
namespace Lyranetwork\Systempay\Block\Payment\Form;

class Sepa extends Systempay
{
    protected $_template = 'Lyranetwork_Systempay::payment/form/sepa.phtml';

    // Check if the 1-click payment is active for SEPA.
    public function isOneClickActive()
    {
        return $this->getMethod()->isOneClickActive();
    }
}
