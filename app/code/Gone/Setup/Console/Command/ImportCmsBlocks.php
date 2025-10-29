<?php

namespace Gone\Setup\Console\Command;

use Gone\Cloaking\Ui\Component\Listing\Column\Cms\Block\CloakingOptions;
use Gone\Setup\Helper\CreateCmsContent;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\StoreRepositoryInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCmsBlocks extends \Symfony\Component\Console\Command\Command
{
    /**CreateCmsContent.php
     * @var CreateCmsContent
     */
    protected $_createCmsHelper;

    /**
     * @var StoreRepositoryInterface
     */
    protected $_storeRepository;

    // store data
    /**
     * @var OutputInterface
     */
    protected $_output;

    public function __construct(
        CreateCmsContent $createCmsHelper,
        StoreRepositoryInterface $storeRepository,
        string $name = null
    ) {
        $this->_createCmsHelper = $createCmsHelper;
        $this->_storeRepository = $storeRepository;
        parent::__construct($name);
    }

    /**
     * ex : php bin/magento gone:setup:cmsblocks
     */
    protected function configure(): void
    {
        $this->setName('gone:setup:cmsblocks');
        $this->setDescription('Setup to import CMS blocks.');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->_output = $output;
        $output->writeln('<info>Importing cms blocks...</info>');
        $blocks = $this->_initCmsBlock("all");

        $blocks ? $output->writeln('<info>Success import of cms blocks.</info>') : $output->writeln('<info>ERROR import of cms blocks.</info>');
    }

    protected function _initCmsBlock(string $storeCode, string $directory = CreateCmsContent::FRENCH_DIRECTORY): bool
    {
        try {
            $storeId = ($storeCode === "all") ? 0 : $this->_storeRepository->get($storeCode)->getId();
            $this->_output->writeln('<info>Importing store cms block for store ' . $storeCode . '...</info>');

            /*home*/
            $this->_createCmsHelper->createBlock('gr_vos_avantages', $storeId, $directory, CreateCmsContent::HOMEPAGE_BLOCK_DIRECTORY);

            /*header*/
            $this->_createCmsHelper->createBlock('header-links', $storeId, $directory, CreateCmsContent::HEADER_BLOCK_DIRECTORY, CloakingOptions::CLOAK_LINK_BY_CLASS_NOT_HOME);
            $this->_createCmsHelper->createBlock('social-icons-header', $storeId, $directory, CreateCmsContent::HEADER_BLOCK_DIRECTORY, CloakingOptions::CLOAK_LINK_BY_CLASS_NOT_HOME);
            /*footer*/
            $this->_createCmsHelper->createBlock('footer-links', $storeId, $directory, CreateCmsContent::FOOTER_BLOCK_DIRECTORY, CloakingOptions::CLOAK_LINK_BY_CLASS_NOT_HOME);
            $this->_createCmsHelper->createBlock('copyright-footer', $storeId, $directory, CreateCmsContent::FOOTER_BLOCK_DIRECTORY, CloakingOptions::CLOAK_LINK_BY_CLASS_NOT_HOME);
            /*contact*/
            $this->_createCmsHelper->createBlock('contact-information', $storeId, $directory, CreateCmsContent::CONTACT_BLOCK_DIRECTORY, CloakingOptions::CLOAK_NONE);

            //Pages
            $this->_createCmsHelper->createPage('homepage', $storeId, $directory);
            $this->_createCmsHelper->createPage('erreur-epson-sc-t', $storeId, $directory);
            $this->_createCmsHelper->createPage('erreur-epson-sc-s', $storeId, $directory);
            $this->_createCmsHelper->createPage('erreur-epson-sc-p', $storeId, $directory);
            $this->_createCmsHelper->createPage('erreur-epson-sc-f', $storeId, $directory);
            $this->_createCmsHelper->createPage('erreur-epson', $storeId, $directory);
            $this->_createCmsHelper->createPage('maintenance-depannage', $storeId, $directory);

            return true;

        } catch (NoSuchEntityException $e) {
            $this->_output->writeln('<info>ERROR - Store ' . $storeCode . ' not found</info>');

        }
    }
}
