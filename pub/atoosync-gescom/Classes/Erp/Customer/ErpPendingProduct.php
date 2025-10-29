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
 * Class ErpPendingProduct
 */
class ErpPendingProduct
{
    /** @var string La clé de la commande dans le CMS */
    public $order_key = "";

    /** @var string Le numéro de la commande dans l'ERP */
    public $order_number = "";

    /** @var string Le date de la commande dans l'ERP */
    public $order_date = "";

    /** @var string La référence de la commande dans l'ERP */
    public $order_reference = "";

    /** @var string La référence de la ligne d'article du document dans l'ERP */
    public $line_reference = "";

    /** @var string Le code de l'article dans l'ERP */
    public $product_key = "";

    /** @var string Le nom de l'article dans l'ERP */
    public $product_name = "";

    /** @var string La quantité de la ligne d'article du document dans l'ERP */
    public $product_quantity = "";

    /** @var float Le prix unitaire HT de la ligne d'article du document dans l'ERP */
    public $product_price_tax_excl = 0.00;

    /** @var float Le prix unitaire TTC de la ligne d'article du document dans l'ERP */
    public $product_price_tax_incl = 0.00;

    /** @var float Le total HT de la ligne d'article du document dans l'ERP */
    public $product_total_price_tax_excl = 0.00;

    /** @var float Le total TTC de la ligne d'article du document dans l'ERP */
    public $product_total_price_tax_incl = 0.00;

    /** @var float Le montant total des taxes de la ligne d'article du document dans l'ERP */
    public $product_total_tax = 0.00;

    /** @var string La remise de la ligne d'article du document dans l'ERP */
    public $product_discount = "";

    /** @var float Le conditionnement de la ligne d'article du document dans l'ERP */
    public $product_packaging = 0.00;

    /** @var string L'unité de vente de la ligne d'article du document  dans l'ERP */
    public $product_unity = "";

    /** @var string La date de livraison de la ligne d'article du document dans l'ERP */
    public $product_delivery_date = "";

    /** @var float Le stock réel de l'article lors de l'export */
    public $product_stock_real = 0.00;

    /** @var float Le stock virtuel de l'article lors de l'export */
    public $product_stock_virtual = 0.00;

    /** @var float Le stock disponible de l'article lors de l'export */
    public $product_stock_available = 0.00;

    /** @var float Le stock à terme de l'article lors de l'export */
    public $product_stock_target = 0.00;

    /** @var float Le stock réel moins les commandes clients de l'article lors de l'export */
    public $product_stock_real_minus_orders = 0.00;

    /** @var float Le stock à terme moins les commandes fournisseurs de l'article lors de l'export */
    public $product_stock_target_minus_purchase_orders = 0.00;

    /** @var string Code barre de l'article dans l'ERP */
    public $ean13 = "";

    /**
     * Créé un objet ErpPendingProduct à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $pendingProductXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpPendingProduct
     */
    public static function createFromXML($pendingProductXml)
    {
        $erpPendingProduct = new ErpPendingProduct();
        if ($pendingProductXml) {
            $erpPendingProduct->order_key = (string)$pendingProductXml->order_key;
            $erpPendingProduct->order_number = (string)$pendingProductXml->order_number;
            $erpPendingProduct->order_date = (string)$pendingProductXml->order_date;
            $erpPendingProduct->order_reference = (string)$pendingProductXml->order_reference;
            $erpPendingProduct->line_reference = (string)$pendingProductXml->line_reference;
            $erpPendingProduct->product_key = (string)$pendingProductXml->product_key;
            $erpPendingProduct->product_name = (string)$pendingProductXml->product_name;
            $erpPendingProduct->product_quantity = (float)$pendingProductXml->product_quantity;
            $erpPendingProduct->product_price_tax_excl = (float)$pendingProductXml->product_price_tax_excl;
            $erpPendingProduct->product_price_tax_incl = (float)$pendingProductXml->product_price_tax_incl;
            $erpPendingProduct->product_total_price_tax_excl = (float)$pendingProductXml->product_total_price_tax_excl;
            $erpPendingProduct->product_total_price_tax_incl = (float)$pendingProductXml->product_total_price_tax_incl;
            $erpPendingProduct->product_total_tax = (float)$pendingProductXml->product_total_tax;
            $erpPendingProduct->product_discount = (string)$pendingProductXml->product_discount;
            $erpPendingProduct->product_packaging = (float)$pendingProductXml->product_packaging;
            $erpPendingProduct->product_unity = (string)$pendingProductXml->product_unity;
            $erpPendingProduct->product_delivery_date = (string)$pendingProductXml->product_delivery_date;
            $erpPendingProduct->product_stock_real = (float)$pendingProductXml->product_stock_real;
            $erpPendingProduct->product_stock_virtual = (float)$pendingProductXml->product_stock_virtual;
            $erpPendingProduct->product_stock_available = (float)$pendingProductXml->product_stock_available;
            $erpPendingProduct->product_stock_target = (float)$pendingProductXml->product_stock_target;
            $erpPendingProduct->product_stock_real_minus_orders = (float)$pendingProductXml->product_stock_real_minus_orders;
            $erpPendingProduct->product_stock_target_minus_purchase_orders = (float)$pendingProductXml->product_stock_target_minus_purchase_orders;
        }
        return $erpPendingProduct;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<pending_product>';

        $xml .= '<order_key><![CDATA[' . $this->order_key . ']]></order_key>';
        $xml .= '<order_number><![CDATA[' . $this->order_number . ']]></order_number>';
        $xml .= '<order_date><![CDATA[' . $this->order_date . ']]></order_date>';
        $xml .= '<order_reference><![CDATA[' . $this->order_reference . ']]></order_reference>';
        $xml .= '<line_reference><![CDATA[' . $this->line_reference . ']]></line_reference>';
        $xml .= '<product_key><![CDATA[' . $this->product_key . ']]></product_key>';
        $xml .= '<product_name><![CDATA[' . $this->product_name . ']]></product_name>';
        $xml .= '<product_quantity><![CDATA[' . $this->product_quantity . ']]></product_quantity>';
        $xml .= '<product_price_tax_excl><![CDATA[' . $this->product_price_tax_excl . ']]></product_price_tax_excl>';
        $xml .= '<product_price_tax_incl><![CDATA[' . $this->product_price_tax_incl . ']]></product_price_tax_incl>';
        $xml .= '<product_total_price_tax_excl><![CDATA[' . $this->product_total_price_tax_excl . ']]></product_total_price_tax_excl>';
        $xml .= '<product_total_price_tax_incl><![CDATA[' . $this->product_total_price_tax_incl . ']]></product_total_price_tax_incl>';
        $xml .= '<product_total_tax><![CDATA[' . $this->product_total_tax . ']]></product_total_tax>';
        $xml .= '<product_discount><![CDATA[' . $this->product_discount . ']]></product_discount>';
        $xml .= '<product_packaging><![CDATA[' . $this->product_packaging . ']]></product_packaging>';
        $xml .= '<product_unity><![CDATA[' . $this->product_unity . ']]></product_unity>';
        $xml .= '<product_delivery_date><![CDATA[' . $this->product_delivery_date . ']]></product_delivery_date>';
        $xml .= '<product_stock_real><![CDATA[' . $this->product_stock_real . ']]></product_stock_real>';
        $xml .= '<product_stock_virtual><![CDATA[' . $this->product_stock_virtual . ']]></product_stock_virtual>';
        $xml .= '<product_stock_available><![CDATA[' . $this->product_stock_available . ']]></product_stock_available>';
        $xml .= '<product_stock_target><![CDATA[' . $this->product_stock_target . ']]></product_stock_target>';
        $xml .= '<product_stock_real_minus_orders><![CDATA[' . $this->product_stock_real_minus_orders . ']]></product_stock_real_minus_orders>';
        $xml .= '<product_stock_target_minus_purchase_orders><![CDATA[' . $this->product_stock_target_minus_purchase_orders . ']]></product_stock_target_minus_purchase_orders>';

        $xml .= '</pending_product>';
        return $xml;
    }
}
