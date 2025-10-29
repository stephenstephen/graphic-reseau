<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Contact\Block;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;

class ContactForm extends \Magento\Contact\Block\ContactForm
{

    protected PageRepositoryInterface $_pageRepository;

    public function __construct(
        Template\Context $context,
        PageRepositoryInterface $pageRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_pageRepository = $pageRepository;
    }

    public function getPrefilledData() : ?string
    {
        $pageId = $this->_request->getParam("source_id");
        if (!empty($pageId)) {
            try {
                $page = $this->_pageRepository->getById($pageId);
                return $page->getTitle() . "\n" .__("Your printer model:") . "\n" . __("Describe your issue:");
            } catch (LocalizedException $e) {
                return "";
            }
        }

        return "";
    }

}
