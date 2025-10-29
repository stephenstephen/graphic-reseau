<?php
 
namespace AtooSync\GesCom\Newsletter\Model;
 
use Magento\Newsletter\Model\Subscriber as MageSubscriber;
 
/**
 * Don't send any newsletter-related emails.
 * These will all go out through our marketing platform.
 */
class Subscriber
{
    /**
     * @param MageSubscriber $oSubject
     * @param callable $proceed
     */
    public function aroundSendConfirmationRequestEmail(MageSubscriber $oSubject, callable $proceed) {}
 
    /**
     * @param MageSubscriber $oSubject
     * @param callable $proceed
     */
    public function aroundSendConfirmationSuccessEmail(MageSubscriber $oSubject, callable $proceed) {}
 
    /**
     * @param MageSubscriber $oSubject
     * @param callable $proceed
     */
    public function aroundSendUnsubscriptionEmail(MageSubscriber $oSubject, callable $proceed)      {}
}