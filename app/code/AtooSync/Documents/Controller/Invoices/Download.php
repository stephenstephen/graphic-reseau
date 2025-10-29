<?php
namespace AtooSync\Documents\Controller\Invoices;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Filesystem\Directory\Read;
use Magento\Framework\Filesystem\Directory\Write;
use Magento\Framework\Filesystem\Io\File;
use Zend\Db\Adapter\Driver\Pdo\Connection;

class Download extends \Magento\Framework\App\Action\Action
{
    
    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $token = $this->getRequest()->getParam('token', false);
        if (!empty($token)) {
           
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            
            $customerSession = $objectManager->create('Magento\Customer\Model\Session');
            /** @var ResourceConnection $resource */
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            
            if ($customerSession->isLoggedIn()) {
                $customerTableName = $resource->getTableName('customer_entity');
                $customerQuery = 'Select `atoosync_account` FROM ' . $customerTableName .' WHERE  `entity_id` = '.$customerSession->getCustomerId();
                $customerAccountRequest = $connection->fetchone($customerQuery);
                if (!empty($customerAccountRequest)) {
                    $tableName = $resource->getTableName('atoosync_orders_documents');
                    $sql = 'Select * FROM ' . $tableName .' WHERE  `token` = "'.$token.'" AND `customer_account` = "'.$customerAccountRequest.'"';
                    $row = $connection->fetchRow($sql);
                    $filesystem = $objectManager->get('Magento\Framework\Filesystem');
                    $directoryList = $objectManager->get('Magento\Framework\App\Filesystem\DirectoryList');
                    /** @var Write $vardirectory */
                    $vardirectory = $filesystem->getDirectoryWrite($directoryList::VAR_DIR);

                    $filename = $vardirectory->getAbsolutePath($row['filename']);
                    if (file_exists($filename)) {
                        $file = $row['document_number'].' - '.$row['document_date'].'.pdf';
                        header("Content-Type: ". mime_content_type($filename));
                        header("Content-Type: application/force-download");
                        header("Content-Length: ".filesize($filename));
                        header("Content-Disposition: attachment; filename=\"".$file."\"");
                        
                        flush();
                        readfile($filename);
                        exit();
                    }
                }
            }
        }
        exit;
    }
}
