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
use Colissimo\Shipping\Model\ResourceModel\Price\Collection;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Csv;

/**
 * Class Export
 */
class Export extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Colissimo_Shipping::shipping_price';

    /**
     * @var Collection $collection
     */
    protected $collection;

    /**
     * @var FileFactory $fileFactory
     */
    protected $fileFactory;

    /**
     * @var DirectoryList $directoryList
     */
    protected $directoryList;

    /**
     * @var Csv $csv
     */
    protected $csv;

    /**
     * @var ShippingHelper $shippingHelper
     */
    protected $shippingHelper;

    /**
     * @param Context $context
     * @param Collection $collection
     * @param FileFactory $fileFactory
     * @param DirectoryList $directoryList
     * @param Csv $csv
     * @param ShippingHelper $shippingHelper
     */
    public function __construct(
        Context $context,
        Collection $collection,
        FileFactory $fileFactory,
        DirectoryList $directoryList,
        Csv $csv,
        ShippingHelper $shippingHelper
    ) {
        parent::__construct($context);
        $this->collection     = $collection;
        $this->fileFactory    = $fileFactory;
        $this->directoryList  = $directoryList;
        $this->csv            = $csv;
        $this->shippingHelper = $shippingHelper;
    }

    /**
     * Action
     */
    public function execute()
    {
        $fileName = $this->shippingHelper->getExportFileName();

        $filePath = $this->directoryList->getPath(DirectoryList::TMP) . '/' . $fileName;

        $field = $this->shippingHelper->getCsvPriceFields();

        $data = $this->collection->load()->toArray($field);
        $data = array_merge([$field], $data['items']);

        $this->csv->setEnclosure('"')->setDelimiter(';')->saveData($filePath, $data);

        $content = array(
            'type'  => 'filename',
            'value' => $fileName,
            'rm'    => true,
        );

        $this->fileFactory->create($fileName, $content, DirectoryList::TMP);
    }
}
