<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Glossary\Controller\Index;

use Gone\Glossary\Api\Data\DefinitionInterface;
use Gone\Glossary\Api\DefinitionRepositoryInterface;
use Gone\Glossary\Controller\Router;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;

class Definition implements HttpGetActionInterface
{

    protected DefinitionRepositoryInterface $_definitionRepository;
    protected SearchCriteriaBuilder $_searchCriteriaBuilder;
    private PageFactory $_pageFactory;
    private RequestInterface $_request;
    private UrlInterface $_urlInterface;
    private Escaper $_escaper;
    private RedirectFactory $_redirectFactory;

    /**
     * @param PageFactory $pageFactory
     * @param UrlInterface $urlInterface
     * @param RedirectFactory $redirectFactory
     * @param Escaper $escaper
     * @param DefinitionRepositoryInterface $definitionRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     */
    public function __construct(
        PageFactory                   $pageFactory,
        UrlInterface                  $urlInterface,
        RedirectFactory               $redirectFactory,
        Escaper                       $escaper,
        DefinitionRepositoryInterface $definitionRepository,
        SearchCriteriaBuilder         $searchCriteriaBuilder,
        RequestInterface              $request
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_redirectFactory = $redirectFactory;
        $this->_urlInterface = $urlInterface;
        $this->_definitionRepository = $definitionRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_escaper = $escaper;
        $this->_request = $request;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        // Get the params that were passed from our Router
        $term = urldecode($this->_escaper->escapeHtml($this->_request->getParam('term'))) ?? null;

        if ($term && $definition = $this->isTermExists($term)) {

            $page = $this->_pageFactory->create();
            $pageConfig = $page->getConfig();
            $pageConfig->getTitle()->set($term);
            $pageConfig->setMetaTitle(__('%1 : Definition', $term));
            $pageConfig->addRemotePageAsset(
                $this->_urlInterface->getBaseUrl() . Router::ROUTE_ID . '/' . $term,
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );

            $block = $page->getLayout()->getBlock('glossary_definition');
            $block->setData('expression', $definition->getText());
            $block->setData('definition', $definition->getDescription());

            return $page;
        } else {
            $redirect = $this->_redirectFactory->create();
            return $redirect->setPath(Router::ROUTE_ID);
        }
    }

    /**
     * @param string $term
     * @return false|DefinitionInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function isTermExists(string $term)
    {

        $this->_searchCriteriaBuilder
            ->addFilter(DefinitionInterface::STATUS, 1)
            ->addFilter(DefinitionInterface::TEXT, $term)
            ->setPageSize(1);

        $definitionCollection = $this->_definitionRepository->getList($this->_searchCriteriaBuilder->create());
        $itemArr = $definitionCollection->getItems();

        return $definitionCollection->getTotalCount() > 0 ? reset($itemArr) : false;
    }

}
