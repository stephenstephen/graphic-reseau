<?php
/**
 * 2007-2021 Atoo Next
 *
 * --------------------------------------------------------------------------------
 *  /!\ Ne peut être utilisé en dehors du programme Atoo-Sync GesCom /!\
 * --------------------------------------------------------------------------------
 *
 *  Ce fichier fait partie du logiciel Atoo-Sync .
 *  Vous n'êtes pas autorisé à le modifier, à le recopier, à le vendre ou le redistribuer.
 *  Cet en-tête ne doit pas être retiré.
 *
 * @author    Atoo Next SARL (contact@atoo-next.net)
 * @copyright 2009-2021 Atoo Next SARL
 * @license   Commercial
 * @script    atoosync-gescom-webservice.php
 *
 * --------------------------------------------------------------------------------
 *  /!\ Ne peut être utilisé en dehors du programme Atoo-Sync GesCom /!\
 * --------------------------------------------------------------------------------
 */

namespace AtooNext\AtooSync\Erp\Order;

/**
 * Class ErpOrderDeliveryDetail
 */
class ErpOrderDeliveryDetail
{
    /** @var string La numéro de la livraison dans l'ERP */
    public $delivery_number = "";

    /** @var string La date de livraison dans l'ERP */
    public $delivery_date = "";

    /** @var string Le nom du transporteur dans l'ERP */
    public $delivery_method = "";

    /** @var string La numéro de suivi transporteur dans l'ERP */
    public $tracking_number = "";

    /** @var string La clé du transporteur correspondant au mode d'expédition dans l'ERP */
    public $carrier_key = "";

    /** @var ErpOrderDeliveryDetailProduct[] Les articles qui compose la livraison dans l'ERP */
    public $products = array();

    /**
     * ErpOrderDeliveryDetail constructor.
     */
    public function __construct()
    {
        $this->products = array();
    }

    /**
     * Créé un objet ErpOrderDeliveryDetail à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $erpOrderDeliveryDetailXML L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpOrderDeliveryDetail
     */
    public static function createFromXML($erpOrderDeliveryDetailXML)
    {
        $erpOrderDeliveryDetail = new ErpOrderDeliveryDetail();
        if ($erpOrderDeliveryDetail) {
            $erpOrderDeliveryDetail->delivery_number = (string)$erpOrderDeliveryDetailXML->delivery_number;
            $erpOrderDeliveryDetail->delivery_date = (string)$erpOrderDeliveryDetailXML->delivery_date;
            $erpOrderDeliveryDetail->delivery_method = (string)$erpOrderDeliveryDetailXML->delivery_method;
            $erpOrderDeliveryDetail->tracking_number = (string)$erpOrderDeliveryDetailXML->tracking_number;
            $erpOrderDeliveryDetail->carrier_key = (string)$erpOrderDeliveryDetailXML->carrier_key;
            $erpOrderDeliveryDetail->products = array();

            // les détails des livraisons de la commande
            if ($erpOrderDeliveryDetailXML->products) {
                foreach ($erpOrderDeliveryDetailXML->products->product as $productXML) {
                    $erpOrderDeliveryDetailProduct = new ErpOrderDeliveryDetailProduct();
                    $erpOrderDeliveryDetailProduct->reference = (string)$productXML->reference;
                    $erpOrderDeliveryDetailProduct->quantity = (float)$productXML->quantity;
                    $erpOrderDeliveryDetailProduct->tracking_number = (string)$productXML->tracking_number;
                    $erpOrderDeliveryDetailProduct->delivery_date = (string)$productXML->delivery_date;
                    $erpOrderDeliveryDetailProduct->variation_key = (string)$productXML->variation_key;

                    $erpOrderDeliveryDetail->products[] = $erpOrderDeliveryDetailProduct;
                }
            }
        }
        return $erpOrderDeliveryDetail;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<delivery>';
        $xml .= '<delivery_number><![CDATA[' . $this->delivery_number . ']]></delivery_number>';
        $xml .= '<delivery_date><![CDATA[' . $this->delivery_date . ']]></delivery_date>';
        $xml .= '<delivery_method><![CDATA[' . $this->delivery_method . ']]></delivery_method>';
        $xml .= '<tracking_number><![CDATA[' . $this->tracking_number . ']]></tracking_number>';
        $xml .= '<carrier_key><![CDATA[' . $this->carrier_key . ']]></carrier_key>';

        $xml .= '<products>';
        if (count($this->products) > 0) {
            foreach ($this->products as $product) {
                $xml .= $product->getXML();
            }
        }
        $xml .= '</products>';
        $xml .= '</delivery>';
        return $xml;
    }
}
