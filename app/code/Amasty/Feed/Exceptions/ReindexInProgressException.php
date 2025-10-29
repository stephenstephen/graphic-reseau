<?php

namespace Amasty\Feed\Exceptions;

class ReindexInProgressException extends \Magento\Framework\Exception\LocalizedException
{
    public function __construct(\Magento\Framework\Phrase $phrase = null, \Exception $cause = null, $code = 0)
    {
        if (!$phrase) {
            $phrase = __('Couldn\'t lock indexer. Reindex in progress.');
        }
        parent::__construct($phrase, $cause, (int) $code);
    }
}
