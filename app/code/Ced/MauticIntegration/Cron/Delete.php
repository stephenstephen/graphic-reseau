<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_MauticIntegration
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license     http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\MauticIntegration\Cron;

use Magento\Framework\App\Helper\AbstractHelper;
use Ced\MauticIntegration\Helper\ConnectionManager;

/**
 * Class Delete
 * @package Ced\MauticIntegration\Cron
 */
class Delete extends AbstractHelper
{
    /**
     * @var ConnectionManager
     */
    private $connectionManager;

    /**
     * ExportToMautic constructor.
     * @param ConnectionManager $connectionManager
     */
    public function __construct(
        ConnectionManager $connectionManager
    ) {
        $this->connectionManager = $connectionManager;
    }

    /**
     * @return bool
     */
    public function deleteErrorLog()
    {
        if (!$this->connectionManager->getModuleStatus()) {
            return true;
        }
        try {
            $days = $this->connectionManager->getConfigValue('mautic_integration/mautic_cron_configuration/delete_error_log');
            if ($days) {
                $this->connectionManager->deleteError($days);
            }
        } catch (\Exception $e) {
            $this->connectionManager->createLog(
                400,
                'exception',
                [
                    'cron' => 'Delete cron',
                    'message' => $e->getMessage()
                ]
            );
        }
        return true;
    }
}
