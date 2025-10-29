<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Contact\Block\Widget;

use Magento\Cms\Model\Page;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class ContactLink extends Template implements BlockInterface
{
    protected $_template = "widget/contact-link.phtml";
    protected Page $_page;


    public function __construct(
        Template\Context $context,
        Page $page,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_page = $page;
    }


    public function getCurrentPageId() : int
    {
        return $this->_page->getId();
    }

}