<?php

namespace Vendor\Honeypot\Controller\Account;

use Magento\Customer\Controller\Account\CreatePost as BaseCreatePost;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;

class CreatePost extends BaseCreatePost
{
    public function execute()
    {
        // Get request parameters
        $request = $this->getRequest();
        $taxvat = $request->getParam('taxvat'); // Retrieves the first name input
        $firstName = $request->getParam('firstname'); // Retrieves the first name input
        $honeypotValue = $request->getParam('honeypot_field'); // Honeypot field

        // Check if the honeypot field is filled
        if (!empty($honeypotValue)) {
            $this->messageManager->addErrorMessage(__('Suspicious activity detected. Please try again.'));
            return $this->redirectToRegister();
        }

        // Check if the first name contains numeric characters
        if (preg_match('/\d/', $firstName)) {
            $this->messageManager->addErrorMessage(__('The first name cannot contain numeric characters.'));
            return $this->redirectToRegister();
        }

        // Check if the taxvat contains unwanted strings
        if (strpos($taxvat, 'file_links') !== false || strpos($taxvat, 'Frazes.txt') !== false) {
            $this->messageManager->addErrorMessage(__('Invalid input detected in the taxvat.'));
            return $this->redirectToRegister();
        }

        // Proceed with the normal account creation process
        return parent::execute();
    }

    /**
     * Redirect to the registration page
     *
     * @return Redirect
     */
    private function redirectToRegister()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/create');
    }
}
