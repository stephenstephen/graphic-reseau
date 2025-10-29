<?php
namespace Netreviews\Avisverifies\Observer;

use Magento\Framework\Event\ObserverInterface;

class SaveCustomFieldsInOrder implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer) {
        try {
            $order = $observer->getEvent()->getOrder();
            $quote = $observer->getEvent()->getQuote();
            $send_review = $quote->getAvSendReview();

            $order->setData('av_send_review', $send_review);


            if ($send_review == 0) {
                // Marcar como pedido ya recuperado.
                $order->setData('av_flag', NULL);
                $order->setData('av_horodate_get', time());
            } else {
                // Sí la recupero;
            }
            return $this;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}