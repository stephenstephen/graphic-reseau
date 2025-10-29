<?php

namespace Gone\Contact\Controller\Index;

use Exception;
use Gone\Contact\Api\ContactRepositoryInterface;
use Gone\Contact\Api\Data\ContactInterfaceFactory;
use Magento\Contact\Model\ConfigInterface;
use Magento\Contact\Model\MailInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Newsletter\Model\Subscriber;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\Phrase;
use function strpos;

class Post extends \Magento\Contact\Controller\Index\Post
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var Context
     */
    private $context;

    /**
     * @var MailInterface
     */
    private $mail;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ContactInterfaceFactory
     */
    private $_contactsFactory;

    /**
     * @var ContactRepositoryInterface
     */
    private $_contactsRepository;

    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;

    /**
     * @var Session
     */
    private $_customerSession;

    /**
     * @var SubscriptionManagerInterface
     */
    private $_subscriptionManager;

    /**
     * @var SubscriberFactory
     */
    private $_subscriberFactory;

    /**
     * @var RequestInterface
     */
    protected $_request;

    public function __construct(
        Context $context,
        ConfigInterface $contactsConfig,
        MailInterface $mail,
        DataPersistorInterface $dataPersistor,
        ContactInterfaceFactory $contactsFactory,
        ContactRepositoryInterface $contactsRepository,
        SubscriberFactory $subscriberFactory,
        SubscriptionManagerInterface $subscriptionManager,
        StoreManagerInterface $storeManager,
        Session $customerSession,
        LoggerInterface $logger = null
    ) {
        parent::__construct(
            $context,
            $contactsConfig,
            $mail,
            $dataPersistor,
            $logger
        );
        $this->context = $context;
        $this->mail = $mail;
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger ?: ObjectManager::getInstance()->get(LoggerInterface::class);
        $this->_contactsFactory = $contactsFactory;
        $this->_contactsRepository = $contactsRepository;
        $this->_storeManager = $storeManager;
        $this->_subscriberFactory = $subscriberFactory;
        $this->_subscriptionManager = $subscriptionManager;
        $this->_customerSession = $customerSession;
        $this->_request = $this->getRequest();
    }

    /**
     * Post user question
     *
     * @return Redirect
     */
    public function execute()
    {
        if (!$this->_request->isPost()) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }
        try {
            //record request
            $this->saveContactRequest();

            //subscribe to newsletter
            if ($this->_request->getParam('subscribe')) {
                $this->subscribeNewsLetterFromContactForm($this->_request->getParam('email'));
            }
            $this->sendEmail($this->validatedParams());
            $this->messageManager->addSuccessMessage(
                __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
            );
            $this->dataPersistor->clear('contact_us');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->dataPersistor->set('contact_us', $this->_request->getParams());
        } catch (Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(
                __('An error occurred while processing your form. Please try again later.')
            );
            $this->dataPersistor->set('contact_us', $this->_request->getParams());
        }
        return $this->resultRedirectFactory->create()->setPath('contact/index');
    }

    /**
     * Record contact request into bdd
     */
    private function saveContactRequest()
    {
        $contact = $this->_contactsFactory->create();
        $contact->setContactName($this->_request->getParam('name'));
        $contact->setContactStoreId($this->_storeManager->getStore()->getId());
        $contact->setContactEmail($this->_request->getParam('email'));
        $contact->setContactCompany($this->_request->getParam('company'));
        $contact->setContactPhone($this->_request->getParam('telephone'));
        $contact->setContactRequest($this->_request->getParam('comment'));

        try {
            $this->_contactsRepository->save($contact);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }

    /**
     * New newsletter subscription action
     *
     * @return Redirect
     */
    public function subscribeNewsLetterFromContactForm($email)
    {
        try {
            $websiteId = (int)$this->_storeManager->getStore()->getWebsiteId();
            /** @var Subscriber $subscriber */
            $subscriber = $this->_subscriberFactory->create()->loadBySubscriberEmail($email, $websiteId);
            if ($subscriber->getId()
                && (int)$subscriber->getSubscriberStatus() === Subscriber::STATUS_SUBSCRIBED) {
                throw new LocalizedException(
                    __('This email address is already subscribed.')
                );
            }

            $storeId = (int)$this->_storeManager->getStore()->getId();
            $currentCustomerId = $this->getSessionCustomerId($email);
            $subscriber = $currentCustomerId
                ? $this->_subscriptionManager->subscribeCustomer($currentCustomerId, $storeId)
                : $this->_subscriptionManager->subscribe($email, $storeId);
            $message = $this->getSuccessMessage((int)$subscriber->getSubscriberStatus());
            $this->messageManager->addSuccessMessage($message);
        } catch (LocalizedException $e) {
            $this->messageManager->addComplexErrorMessage(
                'localizedSubscriptionErrorMessage',
                ['message' => $e->getMessage()]
            );
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong with the subscription.'));
        }
    }

    /**
     * Get customer id from session if he is owner of the email
     *
     * @param string $email
     * @return int|null
     */
    private function getSessionCustomerId(string $email): ?int
    {
        if (!$this->_customerSession->isLoggedIn()) {
            return null;
        }

        $customer = $this->_customerSession->getCustomerDataObject();
        if ($customer->getEmail() !== $email) {
            return null;
        }

        return (int)$this->_customerSession->getId();
    }

    /**
     * @param array $post Post data from contact form
     * @return void
     */
    private function sendEmail($post)
    {
        $this->mail->send(
            $post['email'],
            ['data' => new DataObject($post)]
        );
    }

    /**
     * @return array
     * @throws Exception
     */
    private function validatedParams()
    {
        if (trim($this->_request->getParam('name')) === '') {
            throw new LocalizedException(__('Enter the Name and try again.'));
        }
        if (trim($this->_request->getParam('comment')) === '') {
            throw new LocalizedException(__('Enter the comment and try again.'));
        }
        if (false === strpos($this->_request->getParam('email'), '@')) {
            throw new LocalizedException(__('The email address is invalid. Verify the email address and try again.'));
        }
        if (trim($this->_request->getParam('hideit')) !== '') {
            throw new Exception();
        }

        return $this->_request->getParams();
    }

    /**
     * Get success message
     *
     * @param int $status
     * @return Phrase
     */
    private function getSuccessMessage(int $status): Phrase
    {
        if ($status === Subscriber::STATUS_NOT_ACTIVE) {
            return __('The confirmation request has been sent.');
        }

        return __('Thank you for your subscription.');
    }
}
