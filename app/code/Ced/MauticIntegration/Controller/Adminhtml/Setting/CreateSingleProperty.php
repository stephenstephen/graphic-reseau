<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 6/9/18
 * Time: 11:11 AM
 */

namespace Ced\MauticIntegration\Controller\Adminhtml\Setting;

use Magento\Framework\App\Action\Context;

class CreateSingleProperty extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;

    public $exportPropertiesAndSegments;

    /**
     * CreateSingleProperty constructor.
     * @param Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Ced\MauticIntegration\Helper\ExportPropertiesAndSegments $exportPropertiesAndSegments
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->exportPropertiesAndSegments =$exportPropertiesAndSegments;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $response = $this->resultJsonFactory->create();
        $propertyCode = $this->getRequest()->getParam('alias');
        $type = $this->getRequest()->getParam('type');
        if ($type != null && $propertyCode != null) {
            if ($type == \Ced\MauticIntegration\Model\CedMautic::TYPE_PROPERTY) {
                $propertyResponse = $this->exportPropertiesAndSegments->createSingleProperty($propertyCode);
                if (isset($propertyResponse['errors'])) {
                    $this->messageManager->addErrorMessage('Some problem occured.');
                } elseif (isset($propertyResponse['field'])) {
                    $this->messageManager->addSuccessMessage('Property created successfully.');
                } else {
                    $this->messageManager->addErrorMessage('Some problem occured.');
                }
            } elseif ($type == \Ced\MauticIntegration\Model\CedMautic::TYPE_SEGMENT) {
                $segnmentResponse = $this->exportPropertiesAndSegments->createSingleSegment($propertyCode);
                if (isset($segnmentResponse['errors'])) {
                    $this->messageManager->addErrorMessage('Some problem occured.');
                } elseif (isset($segnmentResponse['list'])) {
                    $this->messageManager->addSuccessMessage('Segment created successfully.');
                } else {
                     $this->messageManager->addErrorMessage('Some problem occured.');
                }
            }
        }
        $response->setData(['status' => 'true']);
        return $response;
    }
}
