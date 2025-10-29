<?php
/**
 *   Copyright � 410 Gone (contact@410-gone.fr). All rights reserved.
 *   See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace Gone\Catalog\Console\Command;

use Exception;
use Gone\Catalog\Cron\SetOutsizedProducts as SetOutsizedProductsCron;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\State;
use Magento\Framework\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetOutsizedProducts extends Command
{

    protected State $_state;
    protected Registry $_registry;
    protected ProductCollectionFactory $_productCollectionFactory;
    protected ResourceConnection $_resourceConnection;
    protected SetOutsizedProductsCron $_cron;

    public function __construct(
        SetOutsizedProductsCron  $cron,
        State                    $state,
        Registry                 $registry,
        ProductCollectionFactory $productCollectionFactory,
        ResourceConnection       $resourceConnection,
                                 $name = null
    )
    {
        $this->_cron = $cron;
        $this->_state = $state;
        $this->_registry = $registry;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_resourceConnection = $resourceConnection;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('gone_catalog:setoutsized');
        $this->setDescription('Set outsized attibutes on products');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->_registry->unregister('isSecureArea');
            $this->_registry->register('isSecureArea', true);
        } catch (Exception $e) {

        }
        $this->_state->setAreaCode(FrontNameResolver::AREA_CODE);
        $output->writeln('################## Set outsized products ###################');
        $this->_cron->execute();
        $output->writeln('end');
    }
}
