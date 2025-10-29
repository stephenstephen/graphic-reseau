<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Controller\Adminhtml\Price;

use Colissimo\Shipping\Helper\Data as ShippingHelper;
use Colissimo\Shipping\Api\PriceRepositoryInterface;
use Colissimo\Shipping\Model\Price;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem\Driver\File;
use Exception;

/**
 * Class RunImport
 */
class RunImport extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Colissimo_Shipping::shipping_price';

    /**
     * @var JsonFactory $resultJsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var Filesystem $fileSystem
     */
    protected $fileSystem;

    /**
     * @var ShippingHelper $shippingHelper
     */
    protected $shippingHelper;

    /**
     * @var DirectoryList $directoryList
     */
    protected $directoryList;

    /**
     * @var PriceRepositoryInterface
     */
    protected $priceRepository;

    /**
     * @var Price $price
     */
    protected $price;

    /**
     * @var Csv $csv
     */
    protected $csv;

    /**
     * @var File $file
     */
    protected $file;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Filesystem $fileSystem
     * @param ShippingHelper $shippingHelper
     * @param DirectoryList $directoryList
     * @param PriceRepositoryInterface $priceRepository
     * @param Price $price
     * @param Csv $csv
     * @param File $file
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Filesystem $fileSystem,
        ShippingHelper $shippingHelper,
        DirectoryList $directoryList,
        PriceRepositoryInterface $priceRepository,
        Price $price,
        Csv $csv,
        File $file
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->fileSystem        = $fileSystem;
        $this->shippingHelper    = $shippingHelper;
        $this->directoryList     = $directoryList;
        $this->csv               = $csv;
        $this->priceRepository   = $priceRepository;
        $this->price             = $price;
        $this->file              = $file;
    }

    /**
     * Action
     */
    public function execute()
    {
        $file = $this->getRequest()->getParam('file');
        $mode = $this->getRequest()->getParam('mode');

        $filePath = $this->shippingHelper->getImportUploadDir() . '/' . $file;

        if ($this->file->isFile($filePath)) {
            $data = $this->csv->setEnclosure('"')->setDelimiter(';')->getData($filePath);

            $first = current($data);
            $field = $this->shippingHelper->getCsvPriceFields();

            unset($data[0]);

            if ($first === $field) {
                $line = 1;
                try {
                    if ($mode == ShippingHelper::COLISSIMO_IMPORT_MODE_ERASE) {
                        $this->priceRepository->truncate();
                    }

                    $values = [];
                    foreach ($data as $price) {
                        foreach ($price as $key => $insert) {
                            $values[$field[$key]] = $insert;
                        }
                        $this->priceRepository->save($this->price->setData($values));
                        $line++;
                    }

                    $this->file->deleteFile($filePath);

                    $result = [
                        'status' => 'success',
                        'message' => __('%1 lines was successfully imported', count($data))
                    ];
                } catch (Exception $e) {
                    $result = [
                        'status' => 'error',
                        'message' => __('Error at line %1 - %2', $line, $e->getMessage())
                    ];
                }
            } else {
                $result = [
                    'status' => 'error',
                    'message' => __(
                        'Error in columns: %1 instead of %2',
                        join(';', $first),
                        join(';', $field)
                    )
                ];
            }
        } else {
            $result = [
                'status' => 'error',
                'message' => __('File %1 does not exist', $file)
            ];
        }

        $resultJson = $this->resultJsonFactory->create();

        return $resultJson->setData($result);
    }
}
