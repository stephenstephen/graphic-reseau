<?php

namespace Amasty\Feed\Setup\Operation;

/**
 * Class UpgradeTo101
 */
class UpgradeTo101
{
    /**
     * @var \Magento\Framework\Setup\SampleData\Executor
     */
    private $executor;

    /**
     * @var \Amasty\Feed\Setup\Updater
     */
    private $updater;

    public function __construct(
        \Magento\Framework\Setup\SampleData\Executor $executor,
        \Amasty\Feed\Setup\Updater $updater
    ) {
        $this->executor = $executor;
        $this->updater = $updater;
    }

    public function execute()
    {
        $this->updater->setTemplates(['bing']);
        $this->executor->exec($this->updater);
    }
}
