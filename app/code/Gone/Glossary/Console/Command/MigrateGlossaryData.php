<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Glossary\Console\Command;

use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Serialize\SerializerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Gone\Glossary\Api\Data\DefinitionInterface;
use Gone\Glossary\Api\Data\DefinitionInterfaceFactory;
use Gone\Glossary\Api\DefinitionRepositoryInterface;

class MigrateGlossaryData extends Command
{

    protected DefinitionInterfaceFactory $_dataDefinitionFactory;
    protected DefinitionRepositoryInterface $_definitionRepository;
    protected DirectoryList $_dir;
    protected SerializerInterface $_serializer;

    public function __construct(
        DefinitionInterfaceFactory $dataDefinitionFactory,
        DefinitionRepositoryInterface $definitionRepository,
        DirectoryList $dir,
        SerializerInterface $serializer,
        string $name = null
    ) {
        parent::__construct($name);
        $this->_dataDefinitionFactory = $dataDefinitionFactory;
        $this->_definitionRepository = $definitionRepository;
        $this->_dir = $dir;
        $this->_serializer = $serializer;
    }

    /**
     * ex : php bin/magento gone_glossary:import
     */
    protected function configure(): void
    {
        $this->setName('gone_glossary:import');
        $this->setDescription('Migrate Glossary Data');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $baseDir = $this->_dir->getPath('app');
        $glossaryDefinitions = file_get_contents($baseDir . '/code/Gone/Glossary/Console/Command/fixtures/glossary-data.json');
        $glossaryDefinitions = $this->_serializer->unserialize($glossaryDefinitions);

        $numberOfDefinition = count($glossaryDefinitions);
        $i = 1;
        foreach ($glossaryDefinitions as $definition) {
            $output->writeln('<comment>Process definition ' . $i . ' / ' . $numberOfDefinition . '</comment>');
            $caseSensible = $definition['title'] == 'DUE' ? 1 : 0;
            $this->_createGlossaryDefinition(
                $definition['title'],
                $definition['description'],
                $definition['status'],
                $caseSensible
            );
            $i++;
        }
    }

    protected function _createGlossaryDefinition(string $text, string $description, bool $status, bool $caseSensible)
    {
        /** @var DefinitionInterface $definition */
        $definition = $this->_dataDefinitionFactory->create();
        $definition
            ->setText($text)
            ->setDescription($description)
            ->setStatus($status)
            ->setCaseSensible($caseSensible);
        $this->_definitionRepository->save($definition);
    }
}
