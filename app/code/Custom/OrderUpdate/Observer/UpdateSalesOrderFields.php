<?php

namespace Custom\OrderUpdate\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;

class UpdateSalesOrderFields implements ObserverInterface
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        try {
            $order = $observer->getEvent()->getOrder();

            if (!$order instanceof Order) {
                $this->logger->info('Aucune commande trouvée dans l\'événement.');
                return;
            }

            // Empêcher l'exécution multiple
            if ($order->getData('custom_observer_executed')) {
                $this->logger->info('Observer déjà exécuté pour la commande #' . $order->getId());
                return;
            }
            $order->setData('custom_observer_executed', true);

            // Vérifie le mode de paiement
            $paymentMethod = $order->getPayment()->getMethod();
            $this->logger->info('Mode de paiement: ' . $paymentMethod);

            // Vérifie le statut de la commande
            $orderStatus = $order->getStatus();
            $this->logger->info('Statut de la commande: ' . $orderStatus);

            // Ne pas exécuter l'Observer si la commande est en attente de paiement (redirection)
            if ($paymentMethod === 'sogecommerce_standard' && $orderStatus === 'pending_payment') {
                $this->logger->info('Observer ignoré : La commande est en attente de paiement.');
                return;
            }

            $this->logger->info('Base Discount Invoiced: ' . $order->getBaseTaxAmount());
            $this->logger->info('Shipping Invoiced: ' . $order->getBaseSubtotal());

            // Mise à jour des autres champs
            $order->setBaseSubtotalInvoiced($order->getBaseSubtotal());
            $order->setBaseTaxInvoiced($order->getBaseTaxAmount());
            $order->setBaseTotalInvoiced($order->getBaseGrandTotal());
            $order->setBaseTotalInvoicedCost($order->getBaseCost());

            $order->setSubtotalInvoiced($order->getSubtotal());
            $order->setTaxInvoiced($order->getTaxAmount());
            $order->setTotalInvoiced($order->getGrandTotal());
            $order->setBaseTotalPaid($order->getBaseGrandTotal());
            $order->setTotalPaid($order->getGrandTotal());

            $order->setBaseTotalDue(0);
            $order->setTotalDue(0);

            // Sauvegarde
            $order->save();

            $this->logger->info('Mise à jour des champs sales_order pour la commande #' . $order->getId());

        } catch (\Exception $e) {
            $this->logger->error('Erreur mise à jour sales_order: ' . $e->getMessage());
        }
    }

}
