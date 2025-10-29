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

namespace AtooNext\AtooSync\Erp\Customer;

/**
 * Class ErpCustomerPendingProducts
 */
class ErpCustomerPendingProducts
{
    /** @var string La clé du client dans l'ERP */
    public $customer_key = "";


    /** @var ErpPendingProduct[] Les articles en attente de livraison */
    public $pendingProducts = array();

    /**
     * ErpCustomerPendingProducts constructor.
     */
    public function __construct()
    {
        $this->pendingProducts = array();
    }

    /**
     * Créé un objet ErpCustomerPendingProducts à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $erpCustomerPendingProductsXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpCustomerPendingProducts
     */
    public static function createFromXML($erpCustomerPendingProductsXml)
    {
        $erpCustomerPendingProducts = new ErpCustomerPendingProducts();
        if ($erpCustomerPendingProductsXml) {
            $erpCustomerPendingProducts->customer_key = (string)$erpCustomerPendingProductsXml->customer_key;

            // les articles en attente
            if ($erpCustomerPendingProductsXml->pending_products) {
                $erpCustomerPendingProducts->pendingProducts = array();
                foreach ($erpCustomerPendingProductsXml->pending_products->pending_product as $pendingProductXml) {
                    $erpCustomerPendingProducts->pendingProducts[] = ErpPendingProduct::createFromXML($pendingProductXml);
                }
            }
        }
        return $erpCustomerPendingProducts;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<customer_pending_products>';
        $xml .= '<customer_key><![CDATA[' . $this->customer_key . ']]></customer_key>';
        $xml .= '<pending_products>';
        if (count($this->pendingProducts) > 0) {
            foreach ($this->pendingProducts as $pendingProduct) {
                $xml .= $pendingProduct->getXML();
            }
        }
        $xml .= '</pending_products>';
        $xml .= '</customer_pending_products>';
        return $xml;
    }
}
