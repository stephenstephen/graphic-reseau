<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 24/06/2020
 * Time: 15:41
 */

namespace AtooSync\Documents\Block;

use Magento\Framework\View\Element\Template;

class InvoicesList extends \Magento\Framework\View\Element\Template
{
    public function __construct(Template\Context $context, array $data = array())
    {
        parent::__construct($context, $data);
    }
    
    /**
     * Lit les factures du client
     * @return array
     */
    public function getDocuments()
    {
        $invoices = array();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        if ($customerSession->isLoggedIn()) { 
        
            $customerTableName = $resource->getTableName('customer_entity');
            $customerQuery = 'Select `atoosync_account` FROM ' . $customerTableName .' WHERE  `entity_id` = '.$customerSession->getCustomerId();
            $customerAccountRequest = (string)$connection->fetchone($customerQuery);
            if (!empty($customerAccountRequest)) {
                $tableName = $resource->getTableName('atoosync_orders_documents');
                //Select Data from table
                $sql = 'Select * FROM ' . $tableName .' WHERE  `customer_account` = "'.$customerAccountRequest.'"';
                $rows = $connection->fetchAll($sql);
                foreach ($rows as $row) {
                    $link = $this->getBaseUrl().'documents/invoices/download/token/'.$row['token'];
                    $item = array();
                    $item['order_id'] = $row['order_id'];
                    $item['document_number'] = $row['document_number'];
                    $item['document_date'] = $row['document_date'];
                    $item['document_total_tax_excl'] = $row['document_total_tax_excl'];
                    $item['document_total_tax_incl'] = $row['document_total_tax_incl'];
                    $item['link'] = $link;
                    $invoices[] = $item;
                }
            }
        }
        return $invoices;
    }
}
