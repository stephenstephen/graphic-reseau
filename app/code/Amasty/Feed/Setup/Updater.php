<?php

namespace Amasty\Feed\Setup;

use Amasty\Feed\Model\Import;
use Magento\Framework\Setup;

/**
 * Class Updater
 */
class Updater implements Setup\SampleData\InstallerInterface
{
    public $import;

    /**
     * @var array
     */
    public $templates = [];

    public function __construct(
        Import $import
    ) {
        $this->import = $import;
    }

    /**
     * {@inheritdoc}
     */
    public function install()
    {
        $this->import->update($this->templates);
    }

    public function setTemplates($templates)
    {
        $this->templates = $templates;
    }
}
