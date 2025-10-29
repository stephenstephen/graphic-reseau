<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_ProductAttachments
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductAttachments\Helper;

use Magento\Backend\Block\Template;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Url;
use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\ObjectManagerInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Core\Helper\AbstractData as CoreHelper;
use Mageplaza\ProductAttachments\Helper\File as HelperFile;
use Mageplaza\ProductAttachments\Model\Config\Source\System\ShowOn;
use Mageplaza\ProductAttachments\Model\FileFactory;
use Mageplaza\ProductAttachments\Model\ResourceModel\File\Collection;
use Mageplaza\ProductAttachments\Model\ResourceModel\File\CollectionFactory as FileCollection;

/**
 * Class Data
 * @package Mageplaza\ProductAttachments\Helper
 */
class Data extends CoreHelper
{
    const CONFIG_MODULE_PATH = 'productattachments';
    const ATTACHMENTS_LOCATION_ATTRIBUTE_CODE = 'mp_attachments_location';

    /**
     * @var File
     */
    protected $_helperFile;

    /**
     * @var FileFactory
     */
    protected $_fileFactory;

    /**
     * @var FileCollection
     */
    protected $_fileCollection;

    /**
     * @var Url
     */
    protected $_customerUrl;

    /**
     * @var ShowOn
     */
    protected $_showOn;

    /**
     * @var OrderCollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param Url $customerUrl
     * @param File $helperFile
     * @param FileFactory $fileFactory
     * @param OrderCollectionFactory $orderCollectionFactory
     * @param FileCollection $fileCollection
     * @param ShowOn $showOn
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        Url $customerUrl,
        HelperFile $helperFile,
        FileFactory $fileFactory,
        OrderCollectionFactory $orderCollectionFactory,
        FileCollection $fileCollection,
        ShowOn $showOn
    ) {
        $this->_customerUrl = $customerUrl;
        $this->_helperFile = $helperFile;
        $this->_fileFactory = $fileFactory;
        $this->_fileCollection = $fileCollection;
        $this->_showOn = $showOn;
        $this->orderCollectionFactory = $orderCollectionFactory;

        parent::__construct($context, $objectManager, $storeManager);
    }

    /**
     * @param $file
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImageUrl($file)
    {
        return $this->_helperFile->getBaseMediaUrl() . '/' . $this->_helperFile->getMediaPath(
            $file,
            HelperFile::TEMPLATE_MEDIA_TYPE_ICON
        );
    }

    /**
     * @param $file
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getFileUrl($file)
    {
        return $this->_helperFile->getBaseMediaUrl() . '/' . $this->_helperFile->getMediaPath(
            $file,
            HelperFile::TEMPLATE_MEDIA_TYPE_FILE
        );
    }

    /**
     * Get file list in each product ( in admin )
     *
     * @param $productId
     *
     * @return AbstractCollection
     */
    public function getFilesByProductId($productId)
    {
        $fileCollection = $this->_fileFactory->create()->getCollection();
        $fileCollection->join(
            ['product' => $fileCollection->getTable('mageplaza_productattachments_file_product')],
            'main_table.file_id=product.file_id AND product.entity_id=' . $productId
        )->setOrder('position', 'asc');

        return $fileCollection;
    }

    /**
     * @param Collection $collection
     * @param null $storeId
     *
     * @return mixed
     */
    public function addStoreFilter($collection, $storeId = null)
    {
        if ($storeId === null) {
            try {
                $storeId = $this->storeManager->getStore()->getId();
            } catch (NoSuchEntityException $e) {
                $this->_logger->critical($e);
            }
        }

        $collection->addFieldToFilter('store_ids', [
            ['finset' => Store::DEFAULT_STORE_ID],
            ['finset' => $storeId]
        ]);

        return $collection;
    }

    /**
     * Get default icon image url
     *
     * @return mixed
     */
    public function getDefaultIconUrl()
    {
        /** @var Template $blockTemplate */
        $blockTemplate = $this->objectManager->create(Template::class);

        return $blockTemplate->getViewFileUrl(
            'Mageplaza_ProductAttachments::media/icons/file-default.png',
            ['area' => Area::AREA_FRONTEND]
        );
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getIconUrl()
    {
        return $this->_helperFile->getBaseMediaUrl() . '/'
            . $this->_helperFile->getBaseMediaPath(HelperFile::TEMPLATE_MEDIA_TYPE_ICON);
    }

    /**
     * @param string $code
     * @param null $storeId
     *
     * @return mixed
     */
    public function getDefaultValueConfig($code = '', $storeId = null)
    {
        $code = ($code !== '') ? '/' . $code : '';

        return $this->getModuleConfig('default_value' . $code, $storeId);
    }

    /**
     * @return array
     */
    public function getShowOnLocation()
    {
        $locations = $this->_showOn->toOptionArray();
        array_shift($locations);

        return $locations;
    }

    /**
     * @param $size
     *
     * @return string
     */
    public function fileSizeFormat($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $power = $size > 0 ? (int)floor(log($size, 1024)) : 0;

        return number_format($size / (1024 ** $power), 2) . ' ' . $units[$power];
    }

    /**
     * Get file collection
     *
     * @param null $storeId
     *
     * @return Collection
     */
    public function getFileCollection($storeId = null)
    {
        /** @var Collection $collection */
        $collection = $this->_fileCollection->create()
            ->addFieldToFilter('status', 1)
            ->setOrder('priority', 'asc');
        $this->addStoreFilter($collection, $storeId);

        return $collection;
    }

    /**
     * @return Collection
     */
    public function getFileByRule()
    {
        return $this->getFileCollection()->addFieldToFilter('is_grid', 1);
    }

    /**
     * Get file list in each product ( in frontend )
     *
     * @param $productId
     *
     * @return Collection
     */
    public function getFileByProduct($productId)
    {
        $fileCollection = $this->getFileCollection();
        $fileCollection->join(
            ['product' => $fileCollection->getTable('mageplaza_productattachments_file_product')],
            'main_table.file_id=product.file_id AND product.entity_id=' . $productId
        );

        return $fileCollection;
    }

    /**
     * @return string
     */
    public function getLoginUrl()
    {
        return $this->_customerUrl->getLoginUrl();
    }

    /**
     * @return string
     */
    public function getRegisterUrl()
    {
        return $this->_customerUrl->getRegisterUrl();
    }

    /**
     * @param $resource
     * @param $object
     * @param $originName
     *
     * @return string
     * @throws LocalizedException
     */
    public function generateFileName($resource, $object, $originName)
    {
        $attempt = -1;
        do {
            if ($attempt++ >= 30) {
                throw new LocalizedException(__('Unable to generate file name. Please check the setting and try again.'));
            }
            $fileName = $originName;
            if ($fileName) {
                $withoutExt = pathinfo($fileName, PATHINFO_FILENAME);
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                $fileName = (!empty($ext) && $ext)
                    ? $withoutExt . ($attempt ?: '') . '.' . $ext : $withoutExt . ($attempt ?: '');
            }
        } while ($this->checkFileName($resource, $object, $fileName));

        return $fileName;
    }

    /**
     * @param $resource
     * @param $object
     * @param $fileName
     *
     * @return bool
     */
    public function checkFileName($resource, $object, $fileName)
    {
        if (empty($fileName)) {
            return true;
        }

        $adapter = $resource->getConnection();
        $select = $adapter->select()
            ->from($resource->getMainTable(), '*')
            ->where('name = :name');

        $binds = ['name' => (string)$fileName];

        if ($id = $object->getId()) {
            $select->where($resource->getIdFieldName() . ' != :object_id');
            $binds['object_id'] = (int)$id;
        }

        return $adapter->fetchOne($select, $binds);
    }

    /**
     * @param $fileIsBuyer
     * @param $productId
     *
     * @return bool
     */
    public function isPurchased($fileIsBuyer, $productId)
    {
        if (!$fileIsBuyer) {
            return true;
        }

        // fix bug get Customer with cache
        $customerSession = $this->objectManager->create(Session::class);

        if (!$customerSession->isLoggedIn()) {
            return false;
        }
        $customerId = $customerSession->getCustomerId();
        $orderCollection = $this->orderCollectionFactory->create()
            ->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('status', 'complete');

        $productIds = [];
        /** @var Order $order */
        foreach ($orderCollection as $order) {
            foreach ($order->getAllVisibleItems() as $item) {
                $productIds[] = $item->getProductId();
            }
        }
        $productIdList = array_unique($productIds);

        return in_array($productId, $productIdList);
    }

    /**
     * @param $fileIsBuyer
     * @param $productId
     * @param $customerId
     *
     * @return bool
     */
    public function isApiPurchased($fileIsBuyer, $productId, $customerId)
    {
        if (!$fileIsBuyer) {
            return true;
        }

        $orderCollection = $this->orderCollectionFactory->create()
            ->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('status', 'complete');

        $productIds = [];
        /** @var Order $order */
        foreach ($orderCollection as $order) {
            foreach ($order->getAllVisibleItems() as $item) {
                $productIds[] = $item->getProductId();
            }
        }
        $productIdList = array_unique($productIds);

        return in_array($productId, $productIdList);
    }
}
