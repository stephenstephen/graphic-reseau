<?php
/**
 * Created By : Rohan Hapani
 */
namespace Gone\Subligraphy\Controller\Certificate;

use Exception;
use Gone\Subligraphy\Api\CertificateRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;

class Download implements HttpGetActionInterface
{
    protected CertificateRepositoryInterface $_certificateRepository;
    protected ResultFactory $resultFactory;
    private SearchCriteriaBuilder $_searchCriteria;
    protected FileFactory $_fileFactory;
    protected MessageManagerInterface $_messageManager;
    protected Filesystem $_filesystem;
    protected RequestInterface $_request;
    private Session $_customerSession;

    /**
     * Download constructor.
     * @param FileFactory $fileFactory
     * @param Filesystem $filesystem
     * @param Session $customerSession
     * @param MessageManagerInterface $messageManager
     * @param CertificateRepositoryInterface $certificateRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     */
    public function __construct(
        FileFactory $fileFactory,
        Filesystem $filesystem,
        ResultFactory $resultFactory,
        Session $customerSession,
        MessageManagerInterface $messageManager,
        CertificateRepositoryInterface $certificateRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request
    ) {
        $this->_certificateRepository = $certificateRepository;
        $this->_searchCriteria = $searchCriteriaBuilder;
        $this->_customerSession = $customerSession;
        $this->_fileFactory = $fileFactory;
        $this->_request = $request;
        $this->_filesystem = $filesystem;
        $this->_messageManager = $messageManager;
        $this->_resultFactory = $resultFactory;
    }

    /**
     * @return array|bool
     */
    private function getFileUrl()
    {

        $certificateReqId = $this->_request->getParam('id');
        $searchCriteria = $this->_searchCriteria
            ->addFilter('certificate_id', $certificateReqId, 'eq')
            ->addFilter('customer_id', $this->_customerSession->getCustomerId(), 'eq');

        $certificate = $this->_certificateRepository->getList($searchCriteria->create());

        if ($certificate->getTotalCount() == 0) {
            throw new LocalizedException(__('This certificate is not accessible'));
        }

        $certificate= current($certificate->getItems());
        $path = $this->_filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
        $fullPath = $path->getAbsolutePath($certificate->getFilename());

        if (file_exists($fullPath)) {
            $details = explode('/', $fullPath);
            $filename = end($details);

            return [
                'filepath' => $fullPath,
                'filename' => $filename
            ];
        }

        return false;
    }

    /**
     * @return ResponseInterface|ResultInterface
     * @throws \LocalizedException
     */
    public function execute()
    {
        try {

            $file = $this->getFileUrl();
            if ($file) {
                $content['type'] = 'filename';
                $content['value'] = $file['filepath'];
                $content['rm'] = 0; // If you will set here 1 then, it will remove file from location.
                return $this->_fileFactory->create($file['filename'], $content);
            }
            $resultRedirect = $this->_resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setRefererUrl();
            return $resultRedirect;
        } catch (LocalizedException $e) {
            $this->_messageManager->addErrorMessage($e->getMessage());
        } catch (Exception $e) {
            $this->_messageManager->addErrorMessage($e->getMessage());
        }
    }
}
