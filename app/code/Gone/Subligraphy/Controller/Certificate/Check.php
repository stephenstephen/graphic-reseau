<?php

namespace Gone\Subligraphy\Controller\Certificate;

use Gone\Subligraphy\Api\Data\CertificateSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;
use Gone\Subligraphy\Api\CertificateRepositoryInterface;

class Check implements ActionInterface
{
    private RequestInterface $request;
    private PageFactory $pageFactory;
    protected CertificateRepositoryInterface $_certificateRepository;
    protected SearchCriteriaBuilder $_searchCriteriaBuilder;
    protected MessageManagerInterface $_messageManager;
    protected Validator $_formKeyValidator;

    public function __construct(
        PageFactory $pageFactory,
        RequestInterface $request,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CertificateRepositoryInterface $certificateRepository,
        MessageManagerInterface $messageManager,
        Validator $formKeyValidator
    ) {
        $this->pageFactory = $pageFactory;
        $this->request=$request;
        $this->_certificateRepository = $certificateRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_messageManager = $messageManager;
        $this->_formKeyValidator=$formKeyValidator;
    }

    /**
     * @return false|string
     */
    public function getCertificateReferenceFromUrl()
    {
        $b64ref = $this->request->getParam('n');
        if ($b64ref) {
            return base64_decode(strip_tags($b64ref));
        }
        return false;
    }

    /**
     * @return false|CertificateSearchResultsInterface|false
     * @throws LocalizedException
     */
    public function checkCertificate()
    {
        $reference = $this->getCertificateReferenceFromUrl();
        if ($reference) {
            $searchCriteria = $this->_searchCriteriaBuilder
                ->addFilter('number', $reference, 'eq')
                ->setPageSize(1)
                ->create();
            $certificate = $this->_certificateRepository->getList($searchCriteria);

            if ($certificate->getTotalCount() === 0) {
                $this->_messageManager->addErrorMessage(
                    __('This certificate is not valid.')
                );
                return false;
            }

            $this->_messageManager->addSuccessMessage(
                __('This certificate is valid.')
            );
            return $certificate;
        }
    }

    public function execute()
    {

        $validFormKey = $this->_formKeyValidator->validate($this->request);
        if ($this->request->isPost() && !$validFormKey) {
            $this->_messageManager->addErrorMessage(
                __('Request is not valid. Please Try again.')
            );
            return $this->_resultFactory->create()->setPath('*/*/');
        }

        $resultPage =  $this->pageFactory->create();
        $block = $resultPage->getLayout()->getBlock('graphicreseau_check_certificate');
        $block->setData('certificate', $this->checkCertificate());
        return $resultPage;
    }
}
