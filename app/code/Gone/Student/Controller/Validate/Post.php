<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Student\Controller\Validate;

use Exception;
use Gone\Base\Helper\CoreConfigData;
use Gone\Base\Helper\FileUploader;
use Gone\Student\Block\Student;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Mirasvit\Report\Model\Mail\Template\TransportBuilderInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Store\Model\StoreManagerInterface;
use Zend_Mime;

class Post implements HttpPostActionInterface
{

    public const TEMPORARY_UPLOAD_FOLDER = 'student';
    public const XML_PATH_EMAIL_TEMPLATE = 'student/general/email_template';
    public const XML_PATH_EMAIL_RECIPIENT = 'student/general/mail';

    protected Validator $_formKeyValidator;
    protected RequestInterface $_request;
    protected ManagerInterface $_messageManager;
    protected RedirectFactory $_redirect;
    protected FileUploader $_fileUploader;
    protected Filesystem $_filesystem;
    protected CoreConfigData $_configHelper;
    protected StoreManagerInterface $_storeManager;
    protected TransportBuilderInterface $_transportBuilder;
    protected Session $_customerSession;

    // store data
    protected ?string $_emailTemplate;

    public function __construct(
        Validator $formKeyValidator,
        RequestInterface $request,
        ManagerInterface $messageManager,
        RedirectFactory $redirect,
        FileUploader $fileUploader,
        Filesystem $filesystem,
        CoreConfigData $configHelper,
        StoreManagerInterface $storeManager,
        TransportBuilderInterface $transportBuilder,
        Session $customerSession
    ) {
        $this->_formKeyValidator = $formKeyValidator;
        $this->_request = $request;
        $this->_messageManager = $messageManager;
        $this->_redirect = $redirect;
        $this->_fileUploader = $fileUploader;
        $this->_filesystem = $filesystem;
        $this->_configHelper = $configHelper;
        $this->_storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        $this->_customerSession = $customerSession;
    }


    public function execute()
    {
        if (!$this->_formKeyValidator->validate($this->_request) || !$this->_request->isPost()) {
            $this->_messageManager->addErrorMessage(
                __('Request is not valid. Please Try again.')
            );
            return $this->_redirect->create()->setPath('*/*/');
        }

        try {
            if (empty($this->_request->getFiles())) {
                throw new LocalizedException(__('Please select a file.'));
            }

            $fileName = $this->_fileUploader->upload(
                $this->_request->getFiles(),
                'file',
                self::TEMPORARY_UPLOAD_FOLDER,
                ['jpg', 'pdf', 'png'],
                Student::MAX_UPLOAD
            );

            if ($fileName) {
                $filePath = $this->_filesystem->getDirectoryRead(DirectoryList::VAR_DIR)
                        ->getAbsolutePath(self::TEMPORARY_UPLOAD_FOLDER) . DS . $fileName;
                if (file_exists($filePath)) {
                    $customer = $this->_customerSession->getCustomer();
                    $params = [
                        'customerName' => $customer->getName(),
                        'customerId' => $customer->getId()
                    ];
                    $this->_sendMail($params, $filePath, $fileName);
                    unlink($filePath);
                }
            }

            $this->_messageManager->addSuccessMessage(
                __('Your request has been send and will be processed as soon as possible')
            );
        } catch (Exception $e) {
            $this->_messageManager->addErrorMessage(
                __('Something went wrong while sending email(s).')
            );
        }

        return $this->_redirect->create()->setPath('customer/account/');
    }

    /**
     * @param $emailParams
     * @param $filePath
     * @throws Exception
     */
    protected function _sendMail($emailParams, $filePath, $fileName)
    {
        $storeId = $this->_storeManager->getStore()->getId();

        if (!isset($this->_emailTemplate)) {
            $this->_emailTemplate = $this->_configHelper->getValueFromCoreConfig(
                self::XML_PATH_EMAIL_TEMPLATE,
                $storeId
            );
            $this->_emailTemplate = $this->_emailTemplate != null
                ? $this->_emailTemplate
                : 'student_general_email_template';
        }

        $transportBuilder = $this->_transportBuilder
            ->setTemplateIdentifier($this->_emailTemplate)
            ->setTemplateOptions(
                [
                    'area' => Area::AREA_FRONTEND,
                    'store' => $storeId,
                ]
            )
            ->setTemplateVars($emailParams)
            ->setFrom(
                [
                    'name' => $this->_configHelper->getValueFromCoreConfig('trans_email/ident_general/name'),
                    'email' => $this->_configHelper->getValueFromCoreConfig('trans_email/ident_general/email')
                ]
            )
            ->addTo($this->_configHelper->getValueFromCoreConfig(self::XML_PATH_EMAIL_RECIPIENT), $storeId)
            ->addAttachment(
                file_get_contents($filePath),
                Zend_Mime::TYPE_OCTETSTREAM,
                Zend_Mime::DISPOSITION_ATTACHMENT,
                Zend_Mime::ENCODING_BASE64,
                $fileName
            )
        ;
        $transport = $transportBuilder->getTransport();
        $transport->sendMessage();
    }
}
