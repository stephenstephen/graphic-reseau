<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\DataFlux\Console\Command;

use Gone\Catalog\Helper\Product as ProductHelper;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\State;
use Magento\Framework\Filesystem;
use Magento\Framework\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Gone\DataFlux\Cron\CansonFlux as CansonFluxCron;

class CansonFlux extends Command
{

    protected ProductCollectionFactory $_productCollectionFactory;
    protected State $_state;
    protected Registry $_registry;
    protected DirectoryList $_dir;
    protected Filesystem $_filesystem;
    protected ProductHelper $_productHelper;
    protected CansonFluxCron $_cron;

    public function __construct(
        CansonFluxCron $cron,
        State                    $state,
        ProductHelper            $productHelper,
        DirectoryList            $dir,
        ProductCollectionFactory $productCollectionFactory,
        Registry                 $registry,
        Filesystem               $filesystem,
        string                   $name = null
    )
    {
        parent::__construct($name);
        $this->_cron = $cron;
        $this->_registry = $registry;
        $this->_state = $state;
        $this->_dir = $dir;
        $this->_productHelper = $productHelper;
        $this->_filesystem = $filesystem;
        $this->_productCollectionFactory = $productCollectionFactory;
    }

    /**
     * ex : php bin/magento gone_glossary:import
     */
    protected function configure(): void
    {
        $this->setName('gone_dataflux:canson_gen');
        $this->setDescription('Generate csv file for Canson products');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->_registry->unregister('isSecureArea');
        $this->_registry->register('isSecureArea', true);
        try {
            $this->_state->setAreaCode(FrontNameResolver::AREA_CODE);
            $output->writeln('################## Building csv ###################');
            $this->_cron->execute();
            $output->writeln('################## CSV Built ###################');
        } catch (Exception $e) {

        }

    }
}
