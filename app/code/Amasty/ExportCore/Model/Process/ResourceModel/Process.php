<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_ExportCore
 */


declare(strict_types=1);

namespace Amasty\ExportCore\Model\Process\ResourceModel;

use Amasty\ExportCore\Model\Process\Process as ProcessModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Process extends AbstractDb
{
    const TABLE_NAME = 'amasty_export_process';

    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, ProcessModel::ID);
    }
}
