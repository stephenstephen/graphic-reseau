<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\SeoCustom\Model;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Indexer;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\App\RequestInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;

class Category extends \Magento\Catalog\Model\Category
{
    /**
     * @var RequestInterface
     */
    protected $_request;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Api\CategoryAttributeRepositoryInterface $metadataService,
        \Magento\Catalog\Model\ResourceModel\Category\Tree $categoryTreeResource,
        \Magento\Catalog\Model\ResourceModel\Category\TreeFactory $categoryTreeFactory,
        \Magento\Store\Model\ResourceModel\Store\CollectionFactory $storeCollectionFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Config $catalogConfig,
        \Magento\Framework\Filter\FilterManager $filter,
        Indexer\Category\Flat\State $flatState,
        \Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator $categoryUrlPathGenerator,
        UrlFinderInterface $urlFinder,
        \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry,
        CategoryRepositoryInterface $categoryRepository,
        RequestInterface $request,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $storeManager,
            $metadataService,
            $categoryTreeResource,
            $categoryTreeFactory,
            $storeCollectionFactory,
            $url,
            $productCollectionFactory,
            $catalogConfig,
            $filter,
            $flatState,
            $categoryUrlPathGenerator,
            $urlFinder,
            $indexerRegistry,
            $categoryRepository,
            $resource,
            $resourceCollection,
            $data
        );
        $this->_request = $request;

        //MGS
        // If Flat Index enabled then use it but only on frontend
        if ($this->flatState->isAvailable() && !$this->getDisableFlat()) {
            $this->_init(\Magento\Catalog\Model\ResourceModel\Category\Flat::class);
            $this->_useFlatResource = true;
        } else {
            $this->_init(\Magento\Catalog\Model\ResourceModel\Category::class);
        }
    }

    public function getDescription()
    {
        if ($this->isNotFirstCategoryPage()) {
            return null;
        }

        return parent::getDescription();
    }

    public function getSeoDescription()
    {
        if ($this->isNotFirstCategoryPage()) {
            return null;
        }

        return parent::getSeoDescription();
    }

    public function isNotFirstCategoryPage() {
        if ($this->_request->getFullActionName() != "catalog_category_view") {
            return false;
        }

        $currentPage = $this->_request->getParam("p");
        if (!empty($currentPage) && $currentPage != 1) {
            return true;
        }

        return false;
    }

    // MGS
    public function getDisableFlat(){

        $urlString = str_replace($this->getUrlInstance()->getUrl(), '', $this->getUrlInstance()->getCurrentUrl());
        $arrUrl = explode('/',$urlString);
        if($arrUrl[0]=='fbuilder' && $arrUrl[1]=='index' && $arrUrl[2]=='publish'){
            return true;
        }
        return false;
    }
}
