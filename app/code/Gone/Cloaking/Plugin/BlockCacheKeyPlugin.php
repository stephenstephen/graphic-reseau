<?php
/*
 *
 * @copyright Copyright © 2020 410-Gone. All rights reserved.
 * @author    contact@410-gone.fr
 *
 */

namespace Gone\Cloaking\Plugin;

use Magento\Customer\Model\Context;
use Magento\Customer\Model\GroupManagement;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\AbstractAction;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Template;

/**
 * Plugin for Magento\Catalog\Model\Product
 */
class BlockCacheKeyPlugin
{

    /**
     * @var HttpContext
     */
    protected $httpContext;

    /**
     * Set customer group and customer session id to HTTP context
     *
     * @param AbstractAction $subject
     * @param RequestInterface $request
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetCacheKeyInfo(Template $subject, $result)
    {
        $result['is_home'] = ($subject->getRequest()->getFullActionName() == 'cms_index_index');
        return $result;
    }
}
