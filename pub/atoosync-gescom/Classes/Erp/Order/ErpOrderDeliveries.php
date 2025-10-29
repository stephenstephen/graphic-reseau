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
 * Class ErpOrderDeliveries
 */
class ErpOrderDeliveries
{
    /** @var string La clé de la commande dans le CMS */
    public $order_key = "";

    /** @var array Les détails des livraisons de la commande dans l'ERP */
    public $details = array();

    /**
     * ErpOrderDeliveries constructor.
     */
    public function __construct()
    {
        $this->details = array();
    }

    /**
     * Créé un objet ErpOrderDeliveries à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $erpOrderDeliveriesXML L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpOrderDeliveries
     */
    public static function createFromXML($erpOrderDeliveriesXML)
    {
        $erpOrderDeliveries = new ErpOrderDeliveries();
        if ($erpOrderDeliveries) {
            $erpOrderDeliveries->order_key = (string)$erpOrderDeliveriesXML->order_key;

            // les détails des livraisons de la commande
            if ($erpOrderDeliveriesXML->deliveries) {
                $erpOrderDeliveries->details = array();

                foreach ($erpOrderDeliveriesXML->deliveries->delivery as $deliveryXML) {
                    $erpOrderDeliveries->details[] = ErpOrderDeliveryDetail::createFromXML($deliveryXML);
                }
            }
        }
        return $erpOrderDeliveries;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<order_deliveries>';
        $xml .= '<order_key><![CDATA[' . $this->order_key . ']]></order_key>';
        $xml .= '<deliveries>';
        if (count($this->details) > 0) {
            foreach ($this->details as $delivery) {
                $xml .= $delivery->getXML();
            }
        }
        $xml .= '</deliveries>';
        $xml .= '</order_deliveries>';
        return $xml;
    }
}
