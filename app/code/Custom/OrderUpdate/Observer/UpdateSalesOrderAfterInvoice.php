<?php

namespace Custom\OrderUpdate\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order\Invoice;
use Psr\Log\LoggerInterface;

class UpdateSalesOrderAfterInvoice implements ObserverInterface
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        try {
            /** @var Invoice $invoice */
            $invoice = $observer->getEvent()->getInvoice();
            $order = $invoice->getOrder();

            if (!$order || !$invoice) {
                $this->logger->info('Observer déclenché mais pas de commande ou de facture.');
                return;
            }

            // Log des valeurs initiales
            $this->logger->info('Commande #' . $order->getId() . ' - Mise à jour après facturation');

            // Mise à jour des valeurs invoiced dans sales_order
            $order->setBaseDiscountInvoiced($order->getBaseDiscountInvoiced() + $invoice->getBaseDiscountAmount());
            $order->setBaseShippingInvoiced($order->getBaseShippingInvoiced() + $invoice->getBaseShippingAmount());
            $order->setDiscountInvoiced($order->getDiscountInvoiced() + $invoice->getDiscountAmount());
            $order->setShippingInvoiced($order->getShippingInvoiced() + $invoice->getShippingAmount());
            $order->setDiscountTaxCompensationInvoiced(
                $order->getDiscountTaxCompensationInvoiced() + $invoice->getDiscountTaxCompensationAmount()
            );
            $order->setBaseDiscountTaxCompensationInvoiced(
                $order->getBaseDiscountTaxCompensationInvoiced() + $invoice->getBaseDiscountTaxCompensationAmount()
            );

            // Log des valeurs mises à jour
            $this->logger->info('Base Discount Invoiced: ' . $order->getBaseDiscountInvoiced());
            $this->logger->info('Shipping Invoiced: ' . $order->getShippingInvoiced());

            // Mise à jour des autres champs
            $order->setBaseSubtotalInvoiced($order->getBaseSubtotal());
            $order->setBaseTaxInvoiced($order->getBaseTaxAmount());
            $order->setBaseTotalInvoiced($order->getBaseGrandTotal());
            $order->setBaseTotalInvoicedCost($order->getBaseCost());
            // $order->setBaseTotalPaid($order->getBaseGrandTotal());

            $order->setSubtotalInvoiced($order->getSubtotal());
            $order->setTaxInvoiced($order->getTaxAmount());
            $order->setTotalInvoiced($order->getGrandTotal());
            // $order->setTotalPaid($order->getGrandTotal());


            // Mise à jour des montants payés et dus
            $order->setBaseTotalPaid($order->getBaseTotalPaid() + $invoice->getBaseGrandTotal());
            $order->setTotalPaid($order->getTotalPaid() + $invoice->getGrandTotal());
            $order->setBaseTotalDue(0);
            $order->setTotalDue(0);

            // Sauvegarde
            $order->save();

            $this->logger->info('Mise à jour réussie pour la commande #' . $order->getId());

        } catch (\Exception $e) {
            $this->logger->error('Erreur mise à jour sales_order après facturation: ' . $e->getMessage());
        }
    }
}
