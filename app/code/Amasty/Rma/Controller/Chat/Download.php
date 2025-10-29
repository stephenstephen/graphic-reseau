<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Controller\Chat;

use Amasty\Rma\Api\Data\MessageFileInterface;
use Amasty\Rma\Model\Chat\ResourceModel\MessageFileCollectionFactory;
use Amasty\Rma\Controller\Adminhtml\RegistryConstants;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Filesystem;

class Download extends Action
{
    /**
     * @var MessageFileCollectionFactory
     */
    private $messageFileCollectionFactory;

    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(
        MessageFileCollectionFactory $messageFileCollectionFactory,
        FileFactory $fileFactory,
        Filesystem $filesystem,
        Context $context
    ) {
        parent::__construct($context);
        $this->messageFileCollectionFactory = $messageFileCollectionFactory;
        $this->fileFactory = $fileFactory;
        $this->filesystem = $filesystem;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $hash = $this->getRequest()->getParam('hash');
        $requestId = (int)$this->getRequest()->getParam(RegistryConstants::REQUEST_ID);

        if ($hash && $requestId) {
            /** @var \Amasty\Rma\Api\Data\MessageFileInterface $messageFile */
            $messageFile = $this->messageFileCollectionFactory->create()
                ->addFieldToFilter(MessageFileInterface::URL_HASH, $hash)
                ->getFirstItem();

            if (!$messageFile->getMessageFileId()) {
                return $this->resultFactory->create(ResultFactory::TYPE_FORWARD)->forward('noroute');
            }
            $filePath = $messageFile->getFilepath();
            //phpcs:ignore
            $fileName = $messageFile->getFileName() . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
            $relativePath = $this->filesystem->getDirectoryRead(
                DirectoryList::MEDIA
            )->getRelativePath(
                \Amasty\Rma\Utils\FileUpload::MEDIA_PATH . $requestId . DIRECTORY_SEPARATOR . $filePath
            );

            try {
                return $this->fileFactory->create(
                    $fileName,
                    [
                        'type' => 'filename',
                        'value' => $relativePath
                    ],
                    DirectoryList::MEDIA
                );
            } catch (\Exception $e) {
                null;
            }
        }

        return $this->resultFactory->create(ResultFactory::TYPE_FORWARD)->forward('noroute');
    }
}
