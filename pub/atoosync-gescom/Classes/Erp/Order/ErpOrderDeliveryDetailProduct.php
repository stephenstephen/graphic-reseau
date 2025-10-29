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
 * Class ErpOrderDeliveryDetailProduct
 */
class ErpOrderDeliveryDetailProduct
{
    /** @var string La référence de l'article dans l'ERP */
    public $reference = "";

    /** @var float La quantité de l'article livré dans l'ERP */
    public $quantity = 0.0;

    /** @var string La date de livraison de la ligne d'article dans l'ERP */
    public $delivery_date = "";

    /** @var string La numéro de suivi transporteur dans l'ERP */
    public $tracking_number = "";

    /** @var string La clé Atoo-Sync de la variation */
    public $variation_key = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<product>';
        $xml .= '<reference><![CDATA[' . $this->reference . ']]></reference>';
        $xml .= '<quantity><![CDATA[' . $this->quantity . ']]></quantity>';
        $xml .= '<delivery_date><![CDATA[' . $this->delivery_date . ']]></delivery_date>';
        $xml .= '<tracking_number><![CDATA[' . $this->tracking_number . ']]></tracking_number>';
        $xml .= '<variation_key><![CDATA[' . $this->variation_key . ']]></variation_key>';
        $xml .= '</product>';
        return $xml;
    }
}
