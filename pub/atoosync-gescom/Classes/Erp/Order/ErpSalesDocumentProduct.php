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
 * Class ErpSalesDocumentProduct
 */
class ErpSalesDocumentProduct
{
    /** @var string La référence unique de l'article dans l'ERP */
    public $reference = "";

    /** @var string Code barre de l'article dans l'ERP */
    public $ean13 = "";

    /** @var string Code Upc de l'article dans l'ERP */
    public $upc = "";

    /** @var string Code ISBN de l'article dans l'ERP */
    public $isbn = "";

    /** @var string Le nom du d'article de la ligne du document dans l'ERP */
    public $name = "";

    /** @var float La quantité de d'article de la ligne du document dans l'ERP */
    public $quantity = 0.00;

    /** @var float La quantité colisée de d'article de la ligne du document dans l'ERP */
    public $packaging_quantity = 0.00;

    /** @var string Le nom de l'unité de vente de la ligne d'article du document dans l'ERP */
    public $unit = "";

    /** @var string La date de livraison de la ligne d'article du le document dans l'ERP */
    public $delivery_date = "";

    /** @var string Le nom de dépôt de la ligne d'article du document dans l'ERP */
    public $warehouse = "";

    /** @var string La clé de la variation de l'article dans l'ERP */
    public $variation_key = "";

    /** @var string La référence de regroupement de l'article */
    public $variation_reference = "";

    /** @var string Le nom de la variation 1 */
    public $variation_1 = "";

    /** @var string La valeur de la variation 1 */
    public $variation_value_1 = "";

    /** @var string Le nom de la variation 2 */
    public $variation_2 = "";

    /** @var string La valeur de la variation 2 */
    public $variation_value_2 = "";

    /** @var string Le nom de la variation 3 */
    public $variation_3 = "";

    /** @var string La valeur de la variation 3 */
    public $variation_value_3 = "";

    /** @var string Le nom de la variation 4 */
    public $variation_4 = "";

    /** @var string La valeur de la variation 4 */
    public $variation_value_4 = "";

    /** @var string Le nom de la variation 5 */
    public $variation_5 = "";

    /** @var string La valeur de la variation 5 */
    public $variation_value_5 = "";

    /** @var string Le nom de la variation 6 */
    public $variation_6 = "";

    /** @var string La valeur de la variation 6 */
    public $variation_value_6 = "";

    /** @var string Le nom de la variation 7 */
    public $variation_7 = "";

    /** @var string La valeur de la variation 7 */
    public $variation_value_7 = "";

    /** @var string Le nom de la variation 8 */
    public $variation_8 = "";

    /** @var string La valeur de la variation 8 */
    public $variation_value_8 = "";

    /** @var string Le nom de la variation 9 */
    public $variation_9 = "";

    /** @var string La valeur de la variation 9 */
    public $variation_value_9 = "";

    /** @var string Le nom de la variation 10 */
    public $variation_10 = "";

    /** @var string La valeur de la variation 10 */
    public $variation_value_10 = "";

    /** @var float Le prix de vente unitaire HT de d'article de la ligne du document dans l'ERP */
    public $unit_price_tax_excl = 0.00;

    /** @var float Le prix de vente unitaire TTC de d'article de la ligne du document dans l'ERP */
    public $unit_price_tax_incl = 0.00;

    /** @var float Le montant de la remise de la ligne du document dans l'ERP */
    public $discount_amount = 0.00;

    /** @var float Le taux de remise de la ligne du document dans l'ERP */
    public $discount_percent = 0.00;

    /** @var float Le prix de vente unitaire net HT de d'article de la ligne du document dans l'ERP */
    public $unit_net_price_tax_excl = 0.00;

    /** @var float Le prix de vente unitaire net TTC de d'article de la ligne du document dans l'ERP */
    public $unit_net_price_tax_incl = 0.00;

    /** @var float Le total HT de la ligne du document dans l'ERP */
    public $total_tax_excl = 0.00;

    /** @var float Le total TTC de la ligne du document dans l'ERP */
    public $total_tax_incl = 0.00;

    /** @var float Le total des taxtes de la ligne du document dans l'ERP */
    public $total_taxes = 0.00;

    /** @var float Le taux de taxe 1 de la ligne du document dans l'ERP */
    public $tax_rate_1 = 0.00;

    /** @var float Le nom de la taxe 1 de la ligne du document dans l'ERP */
    public $tax_name_1 = 0.00;

    /** @var float Le taux de taxe 2 de la ligne du document dans l'ERP */
    public $tax_rate_2 = 0.00;

    /** @var float Le nom de la taxe 2 de la ligne du document dans l'ERP */
    public $tax_name_2 = 0.00;

    /** @var float Le taux de taxe 3 de la ligne du document dans l'ERP */
    public $tax_rate_3 = 0.00;

    /** @var float Le nom de la taxe 3 de la ligne du document dans l'ERP */
    public $tax_name_3 = 0.00;

    /** @var float Le taux de taxe 4 de la ligne du document dans l'ERP */
    public $tax_rate_4 = 0.00;

    /** @var float Le nom de la taxe 4 de la ligne du document dans l'ERP */
    public $tax_name_4 = 0.00;

    /** @var float Le taux de taxe 5 de la ligne du document dans l'ERP */
    public $tax_rate_5 = 0.00;

    /** @var float Le nom de la taxe 5 de la ligne du document dans l'ERP */
    public $tax_name_5 = 0.00;

    /**
     * Créé un objet ErpSalesDocumentProduct à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $erpSalesDocumentProductXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpSalesDocumentProduct
     */
    public static function createFromXML($erpSalesDocumentProductXml)
    {
        $erpSalesDocumentProduct = new ErpSalesDocumentProduct();
        if ($erpSalesDocumentProductXml) {
            $erpSalesDocumentProduct->reference = (string)$erpSalesDocumentProductXml->reference;
            $erpSalesDocumentProduct->ean13 = (string)$erpSalesDocumentProductXml->ean13;
            $erpSalesDocumentProduct->upc = (string)$erpSalesDocumentProductXml->upc;
            $erpSalesDocumentProduct->isbn = (string)$erpSalesDocumentProductXml->isbn;
            $erpSalesDocumentProduct->name = (string)$erpSalesDocumentProductXml->name;

            $erpSalesDocumentProduct->quantity = (float)$erpSalesDocumentProductXml->quantity;
            $erpSalesDocumentProduct->packaging_quantity = (float)$erpSalesDocumentProductXml->packaging_quantity;
            $erpSalesDocumentProduct->unit = (string)$erpSalesDocumentProductXml->unit;
            $erpSalesDocumentProduct->delivery_date = (string)$erpSalesDocumentProductXml->delivery_date;

            $erpSalesDocumentProduct->warehouse = (string)$erpSalesDocumentProductXml->warehouse;

            $erpSalesDocumentProduct->variation_key = (string)$erpSalesDocumentProductXml->variation_key;
            $erpSalesDocumentProduct->variation_reference = (string)$erpSalesDocumentProductXml->variation_reference;
            $erpSalesDocumentProduct->variation_1 = (string)$erpSalesDocumentProductXml->variation_1;
            $erpSalesDocumentProduct->variation_value_1 = (string)$erpSalesDocumentProductXml->variation_value_1;
            $erpSalesDocumentProduct->variation_2 = (string)$erpSalesDocumentProductXml->variation_2;
            $erpSalesDocumentProduct->variation_value_2 = (string)$erpSalesDocumentProductXml->variation_value_2;
            $erpSalesDocumentProduct->variation_3 = (string)$erpSalesDocumentProductXml->variation_3;
            $erpSalesDocumentProduct->variation_value_3 = (string)$erpSalesDocumentProductXml->variation_value_3;
            $erpSalesDocumentProduct->variation_4 = (string)$erpSalesDocumentProductXml->variation_4;
            $erpSalesDocumentProduct->variation_value_4 = (string)$erpSalesDocumentProductXml->variation_value_4;
            $erpSalesDocumentProduct->variation_5 = (string)$erpSalesDocumentProductXml->variation_5;
            $erpSalesDocumentProduct->variation_value_5 = (string)$erpSalesDocumentProductXml->variation_value_5;
            $erpSalesDocumentProduct->variation_6 = (string)$erpSalesDocumentProductXml->variation_6;
            $erpSalesDocumentProduct->variation_value_6 = (string)$erpSalesDocumentProductXml->variation_value_6;
            $erpSalesDocumentProduct->variation_7 = (string)$erpSalesDocumentProductXml->variation_7;
            $erpSalesDocumentProduct->variation_value_7 = (string)$erpSalesDocumentProductXml->variation_value_7;
            $erpSalesDocumentProduct->variation_8 = (string)$erpSalesDocumentProductXml->variation_8;
            $erpSalesDocumentProduct->variation_value_8 = (string)$erpSalesDocumentProductXml->variation_value_8;
            $erpSalesDocumentProduct->variation_9 = (string)$erpSalesDocumentProductXml->variation_9;
            $erpSalesDocumentProduct->variation_value_9 = (string)$erpSalesDocumentProductXml->variation_value_9;
            $erpSalesDocumentProduct->variation_10 = (string)$erpSalesDocumentProductXml->variation_10;
            $erpSalesDocumentProduct->variation_value_10 = (string)$erpSalesDocumentProductXml->variation_value_10;

            $erpSalesDocumentProduct->unit_price_tax_excl = (float)$erpSalesDocumentProductXml->unit_price_tax_excl;
            $erpSalesDocumentProduct->unit_price_tax_incl = (float)$erpSalesDocumentProductXml->unit_price_tax_incl;
            $erpSalesDocumentProduct->discount_amount = (float)$erpSalesDocumentProductXml->discount_amount;
            $erpSalesDocumentProduct->discount_percent = (float)$erpSalesDocumentProductXml->discount_percent;
            $erpSalesDocumentProduct->unit_net_price_tax_excl = (float)$erpSalesDocumentProductXml->unit_net_price_tax_excl;
            $erpSalesDocumentProduct->unit_net_price_tax_incl = (float)$erpSalesDocumentProductXml->unit_net_price_tax_incl;
            $erpSalesDocumentProduct->total_tax_excl = (float)$erpSalesDocumentProductXml->total_tax_excl;
            $erpSalesDocumentProduct->total_tax_incl = (float)$erpSalesDocumentProductXml->total_tax_incl;
            $erpSalesDocumentProduct->total_taxes = (float)$erpSalesDocumentProductXml->total_taxes;

            $erpSalesDocumentProduct->tax_rate_1 = (float)$erpSalesDocumentProductXml->tax_rate_1;
            $erpSalesDocumentProduct->tax_name_1 = (string)$erpSalesDocumentProductXml->tax_name_1;
            $erpSalesDocumentProduct->tax_rate_2 = (float)$erpSalesDocumentProductXml->tax_rate_2;
            $erpSalesDocumentProduct->tax_name_2 = (string)$erpSalesDocumentProductXml->tax_name_2;
            $erpSalesDocumentProduct->tax_rate_3 = (float)$erpSalesDocumentProductXml->tax_rate_3;
            $erpSalesDocumentProduct->tax_name_3 = (string)$erpSalesDocumentProductXml->tax_name_3;
            $erpSalesDocumentProduct->tax_rate_4 = (float)$erpSalesDocumentProductXml->tax_rate_4;
            $erpSalesDocumentProduct->tax_name_4 = (string)$erpSalesDocumentProductXml->tax_name_4;
            $erpSalesDocumentProduct->tax_rate_5 = (float)$erpSalesDocumentProductXml->tax_rate_5;
            $erpSalesDocumentProduct->tax_name_5 = (string)$erpSalesDocumentProductXml->tax_name_5;
        }
        return $erpSalesDocumentProduct;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<product>';
        $xml .= '<reference><![CDATA[' . $this->reference . ']]></reference>';
        $xml .= '<ean13><![CDATA[' . $this->ean13 . ']]></ean13>';
        $xml .= '<upc><![CDATA[' . $this->upc . ']]></upc>';
        $xml .= '<isbn><![CDATA[' . $this->isbn . ']]></isbn>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '<quantity><![CDATA[' . $this->quantity . ']]></quantity>';
        $xml .= '<packaging_quantity><![CDATA[' . $this->packaging_quantity . ']]></packaging_quantity>';
        $xml .= '<unit><![CDATA[' . $this->unit . ']]></unit>';
        $xml .= '<delivery_date><![CDATA[' . $this->delivery_date . ']]></delivery_date>';
        $xml .= '<warehouse><![CDATA[' . $this->warehouse . ']]></warehouse>';
        $xml .= '<variation_key><![CDATA[' . $this->variation_key . ']]></variation_key>';
        $xml .= '<variation_reference><![CDATA[' . $this->variation_reference . ']]></variation_reference>';
        $xml .= '<variation_1><![CDATA[' . $this->variation_1 . ']]></variation_1>';
        $xml .= '<variation_value_1><![CDATA[' . $this->variation_value_1 . ']]></variation_value_1>';
        $xml .= '<variation_2><![CDATA[' . $this->variation_2 . ']]></variation_2>';
        $xml .= '<variation_value_2><![CDATA[' . $this->variation_value_2 . ']]></variation_value_2>';
        $xml .= '<variation_3><![CDATA[' . $this->variation_3 . ']]></variation_3>';
        $xml .= '<variation_value_3><![CDATA[' . $this->variation_value_3 . ']]></variation_value_3>';
        $xml .= '<variation_4><![CDATA[' . $this->variation_4 . ']]></variation_4>';
        $xml .= '<variation_value_4><![CDATA[' . $this->variation_value_4 . ']]></variation_value_4>';
        $xml .= '<variation_5><![CDATA[' . $this->variation_5 . ']]></variation_5>';
        $xml .= '<variation_value_5><![CDATA[' . $this->variation_value_5 . ']]></variation_value_5>';
        $xml .= '<variation_6><![CDATA[' . $this->variation_6 . ']]></variation_6>';
        $xml .= '<variation_value_6><![CDATA[' . $this->variation_value_6 . ']]></variation_value_6>';
        $xml .= '<variation_7><![CDATA[' . $this->variation_7 . ']]></variation_7>';
        $xml .= '<variation_value_7><![CDATA[' . $this->variation_value_7 . ']]></variation_value_7>';
        $xml .= '<variation_8><![CDATA[' . $this->variation_8 . ']]></variation_8>';
        $xml .= '<variation_value_8><![CDATA[' . $this->variation_value_8 . ']]></variation_value_8>';
        $xml .= '<variation_9><![CDATA[' . $this->variation_9 . ']]></variation_9>';
        $xml .= '<variation_value_9><![CDATA[' . $this->variation_value_9 . ']]></variation_value_9>';
        $xml .= '<variation_10><![CDATA[' . $this->variation_10 . ']]></variation_10>';
        $xml .= '<variation_value_10><![CDATA[' . $this->variation_value_10 . ']]></variation_value_10>';
        $xml .= '<unit_price_tax_excl><![CDATA[' . $this->unit_price_tax_excl . ']]></unit_price_tax_excl>';
        $xml .= '<unit_price_tax_incl><![CDATA[' . $this->unit_price_tax_incl . ']]></unit_price_tax_incl>';
        $xml .= '<discount_amount><![CDATA[' . $this->discount_amount . ']]></discount_amount>';
        $xml .= '<discount_percent><![CDATA[' . $this->discount_percent . ']]></discount_percent>';
        $xml .= '<unit_net_price_tax_excl><![CDATA[' . $this->unit_net_price_tax_excl . ']]></unit_net_price_tax_excl>';
        $xml .= '<unit_net_price_tax_incl><![CDATA[' . $this->unit_net_price_tax_incl . ']]></unit_net_price_tax_incl>';
        $xml .= '<total_tax_excl><![CDATA[' . $this->total_tax_excl . ']]></total_tax_excl>';
        $xml .= '<total_tax_incl><![CDATA[' . $this->total_tax_incl . ']]></total_tax_incl>';
        $xml .= '<total_taxes><![CDATA[' . $this->total_taxes . ']]></total_taxes>';
        $xml .= '<tax_rate_1><![CDATA[' . $this->tax_rate_1 . ']]></tax_rate_1>';
        $xml .= '<tax_name_1><![CDATA[' . $this->tax_name_1 . ']]></tax_name_1>';
        $xml .= '<tax_rate_2><![CDATA[' . $this->tax_rate_2 . ']]></tax_rate_2>';
        $xml .= '<tax_name_2><![CDATA[' . $this->tax_name_2 . ']]></tax_name_2>';
        $xml .= '<tax_rate_3><![CDATA[' . $this->tax_rate_3 . ']]></tax_rate_3>';
        $xml .= '<tax_name_3><![CDATA[' . $this->tax_name_3 . ']]></tax_name_3>';
        $xml .= '<tax_rate_4><![CDATA[' . $this->tax_rate_4 . ']]></tax_rate_4>';
        $xml .= '<tax_name_4><![CDATA[' . $this->tax_name_4 . ']]></tax_name_4>';
        $xml .= '<tax_rate_5><![CDATA[' . $this->tax_rate_5 . ']]></tax_rate_5>';
        $xml .= '<tax_name_5><![CDATA[' . $this->tax_name_5 . ']]></tax_name_5>';
        $xml .= '</product>';
        return $xml;
    }
}
