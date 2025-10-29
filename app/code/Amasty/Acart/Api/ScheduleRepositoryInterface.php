<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Abandoned Cart Email Base for Magento 2
 */

namespace Amasty\Acart\Api;

use Amasty\Acart\Api\Data\ScheduleInterface;

interface ScheduleRepositoryInterface
{
    /**
     * @param int $id
     * @return \Amasty\Acart\Api\Data\ScheduleInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function getById(int $id): ScheduleInterface;

    /**
     * @param \Amasty\Acart\Api\Data\ScheduleInterface $schedule
     *
     * @return \Amasty\Acart\Api\Data\ScheduleInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(ScheduleInterface $schedule): ScheduleInterface;

    /**
     * @param \Amasty\Acart\Api\Data\ScheduleInterface $schedule
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(ScheduleInterface $schedule): bool;

    /**
     * @param int $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $id): bool;
}
