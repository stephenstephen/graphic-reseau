<?php
/**
 * Copyright © Lyra Network.
 * This file is part of Sogecommerce plugin for Magento 2. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
namespace Lyranetwork\Sogecommerce\Block\Adminhtml\System\Config\Form\Field;

/**
 * Custom renderer for the contact us element.
 */
class ContactUs extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * Unset some non-related element parameters.
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $comment = \Lyranetwork\Sogecommerce\Model\Api\Form\Api::formatSupportEmails('support@sogecommerce.societegenerale.eu');
        $element->setComment($comment);

        return parent::render($element);
    }
}
