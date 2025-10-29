<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Controller\Chat;

use Amasty\Rma\Utils\FileUpload;
use Amasty\Rma\Model\ConfigProvider;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class UploadTemp extends \Magento\Framework\App\Action\Action
{
    /**
     * @var FileUpload
     */
    private $fileUpload;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    public function __construct(
        Context $context,
        FileUpload $fileUpload,
        ConfigProvider $configProvider
    ) {
        parent::__construct($context);
        $this->fileUpload = $fileUpload;
        $this->configProvider = $configProvider;
    }

    public function execute()
    {
        $files = $this->getRequest()->getFiles()->toArray();

        if (!$files) {
            return null;
        }
        $maxFileSize = (int)$this->configProvider->getMaxFileSize();
        list($result, $errors) = $this->fileUpload->uploadFile($files, $maxFileSize);

        if ($errors) {
            $result['error'] = __(
                'Files %1 have exceeded the maximum file size limit of %2 KB.',
                implode(',', $errors),
                $maxFileSize
            );
        }
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($result);

        return $resultJson;
    }
}
