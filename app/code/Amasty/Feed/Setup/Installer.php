<?php

namespace Amasty\Feed\Setup;

use Amasty\Feed\Model\Import;
use Magento\Framework\Setup;

/**
 * Class Installer
 */
class Installer implements Setup\SampleData\InstallerInterface
{
    protected $import;

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
        $this->import->install();
    }
}
