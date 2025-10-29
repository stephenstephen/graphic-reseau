<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 16/9/19
 * Time: 7:02 PM
 */

namespace Ced\MauticIntegration\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class ErrorLog
 * @package Ced\MauticIntegration\Model
 */
class ErrorLog extends AbstractModel
{

    public function _construct()
    {
        $this->_init('Ced\MauticIntegration\Model\ResourceModel\ErrorLog');
    }
}
