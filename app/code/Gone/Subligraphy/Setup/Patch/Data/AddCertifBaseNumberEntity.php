<?php
namespace Gone\Subligraphy\Setup\Patch\Data;

use \Gone\Subligraphy\Setup\CertifBaseNumberSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddCertifBaseNumberEntity implements DataPatchInterface
{

    private ModuleDataSetupInterface $moduleDataSetup;
    protected CertifBaseNumberSetupFactory $_certifBaseNumberSetupFactory;

    /**
     * AddCertifBaseNumberEntity constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CertifBaseNumberSetupFactory $certifBaseNumberSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CertifBaseNumberSetupFactory $certifBaseNumberSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->_certifBaseNumberSetupFactory = $certifBaseNumberSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->_certifBaseNumberSetupFactory->create(['setup' => $this->moduleDataSetup])->installEntities();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
