<?php
namespace Magedelight\Productpdf\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Module\Dir;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File as IO;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;
    protected $_storeManager;
    protected $moduleReader;
    protected $io;
    
    public function __construct(
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\Module\Dir\Reader $moduleReader,
        EavSetupFactory $eavSetupFactory,
        DirectoryList $directory_list,
        \Magento\Framework\Filesystem $filesystem,
        IO $io
    ) {
        $this->_storeManager = $storeManager;
        $this->moduleReader = $moduleReader;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->directory_list = $directory_list;
        $this->filesystem=$filesystem;
        $this->io = $io;
    }
    
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $mediapath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();
        $destPath = $mediapath.'md_product_print'.DIRECTORY_SEPARATOR .'fonts';
        if (!is_dir($destPath)) {
              $this->io->mkdir($destPath, 0777, true);
        }
        $src = $this->moduleReader->getModuleDir(Dir::MODULE_VIEW_DIR, 'Magedelight_Productpdf') . DIRECTORY_SEPARATOR . 'adminhtml' . DIRECTORY_SEPARATOR . "web" . DIRECTORY_SEPARATOR .'fonts'.DIRECTORY_SEPARATOR;
        $dst = $destPath . DIRECTORY_SEPARATOR ;
        $dir = opendir($src);
        $this->io->mkdir($dst, 0777, true);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($src . '/' . $file)) {
                    recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
        $this->addRatingImages();
    }
    
    public function addRatingImages()
    {
        $mediapath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();
        $destPath = $mediapath.'md_product_print'.DIRECTORY_SEPARATOR;
        if (!is_dir($destPath)) {
              $this->io->mkdir($destPath, 0777, true);
        }
        $src = $this->moduleReader->getModuleDir(Dir::MODULE_VIEW_DIR, 'Magedelight_Productpdf') . DIRECTORY_SEPARATOR . 'adminhtml' . DIRECTORY_SEPARATOR . "web" . DIRECTORY_SEPARATOR .'images'.DIRECTORY_SEPARATOR;
        $dst = $destPath . DIRECTORY_SEPARATOR ;
        $dir = opendir($src);
        $this->io->mkdir($dst, 0777, true);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($src . '/' . $file)) {
                    recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
