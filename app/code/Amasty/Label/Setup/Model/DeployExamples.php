<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Label
 */


declare(strict_types=1);

namespace Amasty\Label\Setup\Model;

use Amasty\Base\Model\Serializer;
use Amasty\Label\Api\Data\LabelInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\StoreManagerInterface;

class DeployExamples
{
    const EXAMPLES_PATH = 'data/examples';

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var File
     */
    private $file;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var ConvertFlatLabelDataToStructuredView
     */
    private $converter;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        Reader $reader,
        File $file,
        Serializer $serializer,
        ConvertFlatLabelDataToStructuredView $converter,
        StoreManagerInterface $storeManager
    ) {
        $this->reader = $reader;
        $this->file = $file;
        $this->serializer = $serializer;
        $this->converter = $converter;
        $this->storeManager = $storeManager;
    }

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param int $firstPossibleId
     * @throws LocalizedException
     */
    public function execute(ModuleDataSetupInterface $moduleDataSetup, int $firstPossibleId = 1): void
    {
        $examples = $this->getLabelExamples();
        $labelId = $firstPossibleId;
        $connection = $moduleDataSetup->getConnection();
        $storeId = array_values($this->storeManager->getStores())[0]->getId() ?? 1;

        foreach ($examples as $example) {
            $example[LabelInterface::LABEL_ID] = $labelId++;
            $example[LabelInterface::STORES] = $storeId;
            $labels = $this->converter->convert($example);

            foreach ($labels as $labelData) {
                foreach ($labelData as $tableName => $tableData) {
                    $tableName = $moduleDataSetup->getTable($tableName);

                    if (!empty($tableData)) {
                        $connection->insertOnDuplicate($tableName, $tableData);
                    }
                }
            }
        }
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    private function getLabelExamples(): array
    {
        $labelsDir = $this->reader->getModuleDir('', 'Amasty_Label');
        $examplePath = $labelsDir . DIRECTORY_SEPARATOR . self::EXAMPLES_PATH . DIRECTORY_SEPARATOR . $this->fileName;

        if (empty($this->fileName) || !$this->file->fileExists($examplePath)) {
            throw new LocalizedException(__('Label examples file name invalid'));
        }

        $jsonContent = $this->file->read($examplePath);

        try {
            $result = $this->serializer->unserialize($jsonContent);
        } catch (\Exception $e) {
            throw new LocalizedException(__('Invalid examples content.'));
        }

        return $result;
    }
}
