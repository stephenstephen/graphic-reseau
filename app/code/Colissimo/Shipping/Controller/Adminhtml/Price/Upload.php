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
use Magento\Backend\App\Action;
use Magento\Framework\App\RequestInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Controller\Result\Raw;
use Exception;

/**
 * Class Upload
 */
class Upload extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Colissimo_Shipping::shipping_price';

    /**
     * @var RawFactory $resultRawFactory
     */
    protected $resultRawFactory;

    /**
     * @var FormKey $formKey
     */
    protected $formKey;

    /**
     * @var ShippingHelper $shippingHelper
     */
    protected $shippingHelper;

    /**
     * @param Context $context
     * @param RawFactory $resultRawFactory
     * @param FormKey $formKey
     * @param ShippingHelper $shippingHelper
     */
    public function __construct(
        Context $context,
        RawFactory $resultRawFactory,
        FormKey $formKey,
        ShippingHelper $shippingHelper
    ) {
        parent::__construct($context);
        $this->shippingHelper = $shippingHelper;
        $this->resultRawFactory = $resultRawFactory;
        $this->getRequest()->setParams(
            ['form_key' => $formKey->getFormKey()]
        );
    }

    /**
     * @return Raw
     */
    public function execute()
    {
        try {
            $uploader = $this->_objectManager->create(
                'Magento\MediaStorage\Model\File\Uploader',
                ['fileId' => 'file']
            );
            $uploader->setAllowedExtensions(['txt', 'csv']);
            $uploader->setAllowRenameFiles(true);

            $result = $uploader->save($this->shippingHelper->getImportUploadDir());

            unset($result['tmp_name']);
            unset($result['path']);
        } catch (Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        /** @var Raw $response */
        $response = $this->resultRawFactory->create();
        $response->setHeader('Content-type', 'text/plain');
        $response->setContents(json_encode($result));

        return $response;
    }
}
