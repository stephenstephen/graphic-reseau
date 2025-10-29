<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Glossary\Controller\Index;

use Gone\Glossary\Controller\Router;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;

class Index implements ActionInterface
{
    protected PageFactory $_resultPageFactory;
    private UrlInterface $_urlInterface;

    public function __construct(
        UrlInterface $urlInterface,
        PageFactory  $resultPageFactory
    )
    {
        $this->_urlInterface = $urlInterface;
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        $page = $this->_resultPageFactory->create();
        $pageConfig = $page->getConfig();
        $pageConfig->addRemotePageAsset(
            $this->_urlInterface->getBaseUrl() . Router::ROUTE_ID,
            'canonical',
            ['attributes' => ['rel' => 'canonical']]
        );

        return $page;
    }
}
