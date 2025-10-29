<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_ExportCore
 */


namespace Amasty\ExportCore\Export\Config;

use Amasty\ExportCore\Api\Config\EntityConfigInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;

class EntityConfigProvider
{
    /**
     * @var EntityConfig[]
     */
    private $entitiesConfig;

    /**
     * @var EntitySource\EntitySourceInterface[]
     */
    private $entitySources;

    /**
     * @var ManagerInterface
     */
    private $eventManager;

    public function __construct(
        ManagerInterface $eventManager,
        array $entitySources
    ) {
        $this->entitySources = $entitySources;
        $this->eventManager = $eventManager;
    }

    public function get(string $entityCode): EntityConfigInterface
    {
        if (!($config = $this->getConfig($entityCode))) {
            throw new \LogicException('No entity config found for entity "' . $entityCode . '"');
        }

        return $config;
    }

    public function getConfig($entityCode = null)
    {
        if ($this->entitiesConfig === null) {
            $this->entitiesConfig = [];
            foreach ($this->entitySources as $entitySource) {
                foreach ($entitySource->get() as $entity) {
                    if (isset($this->entitiesConfig[$entity->getEntityCode()])) {
                        throw new LocalizedException(
                            __('Entity "%1" already exists.', $entity->getEntityCode())
                        );
                    }

                    $this->entitiesConfig[$entity->getEntityCode()] = $entity;

                    //extension point for entity extension attributes
                    $this->eventManager->dispatch(
                        'amexport_entity_loaded',
                        ['entity' => $entity]
                    );
                }
            }
        }

        if ($entityCode) {
            return $this->entitiesConfig[$entityCode] ?? false;
        }

        return $this->entitiesConfig;
    }
}
