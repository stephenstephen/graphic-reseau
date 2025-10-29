<?php

namespace Gone\Glossary\Controller;

use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;

class Router implements RouterInterface
{

    const ROUTE_ID = 'glossaire';

    private ActionFactory $_actionFactory;
    private ResponseInterface $_response;

    /**
     * Router constructor.
     *
     * @param ActionFactory $actionFactory
     * @param ResponseInterface $response
     */
    public function __construct(
        ActionFactory     $actionFactory,
        ResponseInterface $response
    )
    {
        $this->_actionFactory = $actionFactory;
        $this->_response = $response;
    }

    /**
     * @param RequestInterface $request
     * @return ActionInterface|null
     */
    public function match(RequestInterface $request): ?ActionInterface
    {

        $identifier = trim($request->getPathInfo(), '/');

        if (strpos($identifier, 'glossaire') !== false) {

            $url_parts = explode('/', $identifier);

            $action = empty($term = $url_parts[1]) ? 'index' : 'definition';

            $request->setModuleName(self::ROUTE_ID);
            $request->setControllerName('index');
            $request->setActionName($action);
            $request->setParams([
                'term' => $term
            ]);

            return $this->_actionFactory->create(Forward::class, ['request' => $request]);
        }

        return null;
    }
}
