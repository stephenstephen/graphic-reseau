<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\DataFlux\Cron;

use Gone\Catalog\Helper\Product as ProductHelper;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductsCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\State;
use Magento\Framework\Filesystem;

class CansonFlux
{

    public const BRAND_ATTRIBUTE = "brands";
    public const BRAND = "Canson";
    protected const EXPORT_FILE_NAME = "cansonpixel.csv";
    protected const SAVE_TO_DIR = '/pixel/';

    protected ProductCollectionFactory $_productCollectionFactory;
    protected State $_state;
    protected DirectoryList $_dir;
    protected Filesystem $_filesystem;
    protected ProductHelper $_productHelper;

    public function __construct(
        ProductHelper            $productHelper,
        DirectoryList            $dir,
        ProductCollectionFactory $productCollectionFactory,
        Filesystem               $filesystem
    )
    {
        $this->_dir = $dir;
        $this->_productHelper = $productHelper;
        $this->_filesystem = $filesystem;
        $this->_productCollectionFactory = $productCollectionFactory;
    }

    public function execute()
    {
        $productsToExport = $this->getFluxProductCollection();

        $dir = $this->_filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $dir->create(self::SAVE_TO_DIR);

        $stream = $dir->openFile(self::SAVE_TO_DIR . self::EXPORT_FILE_NAME, 'w+');
        $stream->lock();

        $header = [
            'Ean',
            'Deeplink',
            'Product Title',
            'Price excl. VAT',
            'Stock availability',
            'Brand',
            'SKU',
            'Dealer item number',
            'Last update'
        ];
        $stream->writeCsv($header);

        $loop = 0;
        /** @var  Product $product */
        foreach ($productsToExport as $product) {

            $data = [];
            $data[] = $product->getBarcode();
            $data[] = $product->getProductUrl();
            $data[] = $product->getName();
            $data[] = $product->getPrice();
            $data[] = $this->_productHelper->getStock($product);
            $data[] = self::BRAND;
            $data[] = $product->getSku();
            $data[] = $product->getId();
            $data[] = date('d/m/Y H:m');
            $stream->writeCsv($data, ';');

        }
    }

    /**
     * @return ProductsCollection $productsColl
     */

    protected function getFluxProductCollection(): ProductsCollection
    {
        return $this->_productCollectionFactory->create()
            ->addAttributeToFilter(
                self::BRAND_ATTRIBUTE,
                [
                    'notnull' => true,
                    'eq' => self::BRAND
                ]
            )
            ->addAttributeToFilter(
                'status',
                [
                    'eq' => true
                ]
            )
            ->addAttributeToSelect('barcode')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('price');
    }
}
