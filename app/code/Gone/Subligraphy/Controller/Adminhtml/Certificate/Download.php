<?php
/**
 * Created By : Rohan Hapani
 */
namespace Gone\Subligraphy\Controller\Adminhtml\Certificate;

use Exception;
use Gone\Subligraphy\Controller\Adminhtml\Certificate;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\Registry;

class Download extends Certificate
{
    protected FileFactory $_fileFactory;
    protected MessageManagerInterface $_messageManager;
    protected Filesystem $_filesystem;

    /**
     * Download constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param FileFactory $fileFactory
     * @param Filesystem $filesystem
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        FileFactory $fileFactory,
        Filesystem $filesystem
    ) {
        parent::__construct(
            $context,
            $coreRegistry
        );
        $this->_fileFactory=$fileFactory;
        $this->_filesystem=$filesystem;
        $this->_request = $context->getRequest();
        $this->_messageManager = $context->getMessageManager();
    }

    /**
     * @return array|false
     */
    private function getFileUrl()
    {
        try {
            $file = base64_decode($this->_request->getParam('file'));
            $path = $this->_filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
            $fullPath = $path->getAbsolutePath($file);
            if (file_exists($fullPath)) {
                $details = explode('/', $fullPath);
                $filename = end($details);
                return [
                    'filepath' => $fullPath,
                    'filename' => $filename
                ];
            }
            return false;

        } catch (NoSuchEntityException $e) {
            $this->_messageManager->addSuccessMessage(
                __('Error catching file.')
            );
        }
    }

    /**
     * @return ResponseInterface|ResultInterface
     * @throws Exception
     */
    public function execute()
    {
        if ($file = $this->getFileUrl()) {
            $content['type'] = 'filename';
            $content['value'] =  $file['filepath'];
            $content['rm'] = 0; // If you will set here 1 then, it will remove file from location.
            return $this->_fileFactory->create($file['filename'], $content);
        }
        $this->_messageManager->addErrorMessage(
            __('Error catching file.')
        );
    }
}
