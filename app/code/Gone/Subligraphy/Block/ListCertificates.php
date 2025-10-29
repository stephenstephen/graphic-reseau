<?php

namespace Gone\Subligraphy\Block;

use Gone\Subligraphy\Api\Data\CertificateInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Customer\Model\Session;
use Gone\Subligraphy\Api\CertificateRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Gone\Subligraphy\Helper\SubligraphyConfig;
use Gone\Base\Block\Pager;

class ListCertificates extends Template
{

    protected CertificateRepositoryInterface $_certificateRepository;
    protected SubligraphyConfig $_subligraphyConfig;
    private SearchCriteriaBuilder $_searchCriteriaBuilder;
    private Session $_customerSession;

    public function __construct(
        Template\Context $context,
        Session $customerSession,
        SubligraphyConfig $subligraphyConfig,
        CertificateRepositoryInterface $certificateRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
        $this->_customerSession = $customerSession;
        $this->_subligraphyConfig = $subligraphyConfig;
        $this->_certificateRepository = $certificateRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_context=$context;
    }

    /**
     * Get Pager child block output
     *
     * @return Pager
     */
    public function getPager():Pager
    {
        return $this->getChildBlock('pager');
    }

    /**
     * @return string
     */
    public function getPagerHtml():string
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Return certificate create page url
     *
     * @return string
     */
    public function getFrontendCreateCertificateFormUrl():string
    {
        return $this->_context->getUrlBuilder()->getUrl('subligraphie/certificate/create');
    }

    /**
     * @param $url
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImage($url):string
    {
        return $this->_subligraphyConfig->getImage($url);
    }

    /**
     * @return false|CertificateInterface[]
     * @throws LocalizedException
     */
    public function getCertificatesForLoggedUser()
    {

        $searchCriteria = $this->_searchCriteriaBuilder->addFilter('customer_id', $this->_customerSession->getCustomerId(), 'eq');

        $this->getPager()->addCriteria($searchCriteria);
        $certificates = $this->_certificateRepository->getList($searchCriteria->create());
        $this->getPager()->setSearchResult($certificates);

        if ($certificates->getTotalCount() > 0) {
            return $certificates->getItems();
        } else {
            return false;
        }
    }
}
