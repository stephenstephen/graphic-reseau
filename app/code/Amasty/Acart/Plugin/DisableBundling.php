<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Abandoned Cart Email Base for Magento 2
 */

namespace Amasty\Acart\Plugin;

use Magento\Framework\View\Asset\Config as AssetConfig;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;

class DisableBundling
{
    public const ACTION_NAME = 'amasty_acart_reports_index';

    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function afterIsBundlingJsFiles(AssetConfig $subject, $result)
    {
        return $this->request->getFullActionName() === self::ACTION_NAME
            ? false
            : $result;
    }
}
