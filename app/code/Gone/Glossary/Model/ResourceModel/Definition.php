<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gone\Glossary\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Definition extends AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('gone_glossary_definition', 'definition_id');
    }
}
