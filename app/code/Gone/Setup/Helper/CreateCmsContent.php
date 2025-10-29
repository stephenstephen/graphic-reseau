<?php

namespace Gone\Setup\Helper;

use Gone\Cloaking\Ui\Component\Listing\Column\Cms\Block\CloakingOptions;
use Magento\Framework\App\Helper\Context;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory as BlockCollectionFactory;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory as PageCollectionFactory;
use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\PageFactory;
use Magento\Framework\Filesystem\DirectoryList;

class CreateCmsContent extends \Magento\Framework\App\Helper\AbstractHelper
{
    const CMS_DIRECTORY = "/code/Gone/Setup/Console/Command/fixtures/";
    const BLOCK_DIRECTORY = "block-data";
    const PAGE_DIRECTORY = "page-data";
    const FOOTER_BLOCK_DIRECTORY = "footer";
    const HEADER_BLOCK_DIRECTORY = "header";
    const CONTACT_BLOCK_DIRECTORY = "contact";
    const HOMEPAGE_BLOCK_DIRECTORY = "homepage";
    const CATEGORY_BLOCK_DIRECTORY = "category";
    const SUPPORT_BLOCK_DIRECTORY = "support";
    const FRENCH_DIRECTORY = "french";

    protected BlockFactory $_blockFactory;
    protected PageFactory $_pageFactory;
    protected BlockCollectionFactory $_blockCollectionFactory;
    protected PageCollectionFactory $_pageCollectionFactory;
    protected DirectoryList $_dir;

    // store data
    protected $_appDirectory;

    public function __construct(
        Context $context,
        BlockFactory $blockFactory,
        PageFactory $pageFactory,
        BlockCollectionFactory $blockCollectionFactory,
        PageCollectionFactory $pageCollectionFactory,
        DirectoryList $dir,
        string $name = null
    ) {
        $this->_blockFactory = $blockFactory;
        $this->_pageFactory = $pageFactory;
        $this->_blockCollectionFactory = $blockCollectionFactory;
        $this->_pageCollectionFactory = $pageCollectionFactory;
        $this->_dir = $dir;
        $this->_appDirectory = $varDirectory = $this->_dir->getPath("app");
        parent::__construct($context);
    }

    public function createBlock(string $identifier, int $storeId, string $directory, string $blockType, int $cloakingMode = CloakingOptions::CLOAK_NONE): void
    {
        echo "Processing block " . $identifier . "...\n";
        $block = $this->_blockCollectionFactory->create()
            ->addFieldToFilter('identifier', ['eq' => $identifier])
            ->addFieldToFilter('store_id', ['eq' => $storeId])
            ->getFirstItem();
        $filePath = $this->_appDirectory . self::CMS_DIRECTORY . self::BLOCK_DIRECTORY . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR  . $blockType . DIRECTORY_SEPARATOR . $identifier . ".html";

        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            preg_match("/<!--@title\s*(.*?)\s*@-->/u", $content, $title);
            preg_match("/<!--@is_active\s*([01])\s*@-->/u", $content, $is_active);
            $content = preg_replace("/<!--@(.*?)\s*@-->/u", "", $content);

            if (!$block->getId()) {
                echo "Need to create block\n";
                $this->_blockFactory->create()
                    ->setTitle($title[1])
                    ->setIdentifier($identifier)
                    ->setStores([$storeId])
                    ->setIsActive($is_active[1])
                    ->setContent($content)
                    ->setCloakingMode($cloakingMode)
                    ->save();
            } else {
                echo "Need to update block\n";
                $block
                    ->setTitle($title[1])
                    ->setIdentifier($identifier)
                    ->setStores([$storeId])
                    ->setIsActive($is_active[1])
                    ->setContent($content)
                    ->setCloakingMode($cloakingMode)
                    ->save();
            }

        } else {
            echo "File " . $filePath . " doesn't exist !";
        }
    }

    public function createPage(string $identifier, int $storeId, string $directory, int $cloakingMode = CloakingOptions::CLOAK_NONE) : void
    {
        echo "Processing page " . $identifier . "...\n";
        $page = $this->_pageCollectionFactory->create()
            ->addFieldToFilter('identifier', ['eq' => $identifier])
            ->addFieldToFilter('store_id', ['eq' => $storeId])
            ->getFirstItem()
        ;
        $filePath = $this->_appDirectory . self::CMS_DIRECTORY . self::PAGE_DIRECTORY . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . $identifier . ".html";

        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            preg_match("/<!--@title\s*(.*?)\s*@-->/u", $content, $title);
            preg_match("/<!--@is_active\s*([01])\s*@-->/u", $content, $is_active);
            $content = preg_replace("/<!--@(.*?)\s*@-->/u", "", $content);


            if (!$page->getId()) {
                echo "Need to create page\n";
                $this->_pageFactory->create()
                    ->setTitle($title[1])
                    ->setIdentifier($identifier)
                    ->setStores([$storeId])
                    ->setIsActive($is_active[1])
                    ->setContent($content)
                    ->setPageLayout("1column")
                    ->setCloakingMode($cloakingMode)
                    ->save();
            } else {
                echo "Need to update page\n";
                $page
                    ->setTitle($title[1])
                    ->setIdentifier($identifier)
                    ->setStores([$storeId])
                    ->setIsActive($is_active[1])
                    ->setContent($content)
                    ->setPageLayout("1column")
                    ->setCloakingMode($cloakingMode)
                    ->save();
            }

        } else {
            echo "File " . $filePath . " doesn't exist !";
        }
    }
}
