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

namespace AtooNext\AtooSync\Erp\Product;

/**
 * Class ErpProductVariation
 */
class ErpProductVariation
{
    /** @var string La clé unique de la variation de l'article */
    public $atoosync_key = "";

    /** @var string La clé unique dans l'ancienne version */
    public $atoosync_odbc_key = "";

    /** @var string La référence de la variation de l'article */
    public $reference = "";

    /** @var string La référence fournisseur de la variation de l'article */
    public $supplier_reference = "";

    /** @var string Le Code barre du fournisseur de la variation de l'article */
    public $supplier_ean13 = "";

    /** @var string Le Code upc du fournisseur de la variation de l'article */
    public $supplier_upc = "";

    /** @var string L'emplacement de la variation de l'article dans le dépôt */
    public $location = "";

    /** @var string Le code EAN 13 de la variation de l'article */
    public $ean13 = "";

    /** @var string Le Code UPC de la variation de l'article */
    public $upc = "";

    /** @var string Code ISBN de la variation de l'article dans l'ERP */
    public $isbn = "";

    /** @var float Le prix d'achat de la variation de l'article */
    public $wholesale_price = 0;

    /**
     * @var float Le prix de vente HT de la variation de l'article
     * @deprecated Utiliser price_tax_exclude à la place
     */
    public $price = 0.00;

    /** @var float Le prix de vente HT de la variation de l'article */
    public $price_tax_exclude = 0.00;

    /** @var float Le prix de vente TTC de l'article */
    public $price_tax_include = 0.00;

    /** @var float Le montant de la TVA de l'article */
    public $price_tax = 0.00;

    /** @var float Le taux de TVA de l'article */
    public $tax_rate = 0.00;

    /** @var float L'ecotax de la variation de l'article */
    public $ecotax = 0;

    /** @var float La quantité en stock de la variation de l'article */
    public $quantity = 0;

    /** @var float Le poids de la variation de l'article */
    public $weight = 0.00;

    /** @var float L'impact de prix de la variation de l'article */
    public $unit_price_impact = 0.00;

    /** @var bool Variation par défaut */
    public $default_on = false;

    /** @var float La quantité minimal de vente */
    public $minimal_quantity = 0;

    /** @var string La date de disponibilité de la variation */
    public $available_date = "0000-00-00 00:00:00";

    /** @var string La valeur de la variation 1 */
    public $variation_value_1 = "";

    /** @var string La valeur de la variation 2 */
    public $variation_value_2 = "";

    /** @var string La valeur de la variation 3 */
    public $variation_value_3 = "";

    /** @var string La valeur de la variation 4 */
    public $variation_value_4 = "";

    /** @var string La valeur de la variation 5 */
    public $variation_value_5 = "";

    /** @var string La valeur de la variation 6 */
    public $variation_value_6 = "";

    /** @var string La valeur de la variation 7 */
    public $variation_value_7 = "";

    /** @var string La valeur de la variation 8 */
    public $variation_value_8 = "";

    /** @var string La valeur de la variation 9 */
    public $variation_value_9 = "";

    /** @var string La valeur de la variation 10 */
    public $variation_value_10 = "";

    /** @var string La nom du conditionnment de la variation */
    public $packaging_name = "";

    /** @var float La quantité de conditionnment de la variation */
    public $packaging_quantity = 0.00;

    /** @var float La quantité de stock réel de l'article dans l'ERP */
    public $stock_real = 0.00;

    /** @var float La quantité de stock virtuel de l'article dans l'ERP */
    public $stock_virtual = 0.00;

    /** @var float La quantité de stock disponible de l'article dans l'ERP */
    public $stock_available = 0.00;

    /** @var float La quantité de stock à terme de l'article dans l'ERP */
    public $stock_target = 0.00;

    /** @var float La quantité de stock réel mois les commandes clients de l'article dans l'ERP */
    public $stock_real_minus_orders = 0.00;

    /** @var float La quantité de stock à terme moins les commandes d'achats de l'article dans l'ERP */
    public $stock_target_minus_purchase_orders = 0.00;

    /** @var string La prochaine date de livraison de l'article */
    public $next_delivery_date = "";

    /** @var float La prochaine quantité livrée de l'article */
    public $next_delivery_quantity = 0.0;

    /** @var ErpProductVariationGroupPrice[] Les prix des groupes de client de la variation */
    public $groupsPrices = array();

    /** @var ErpProductFeature[] Les dépôts de l'article dans l'ERP */
    public $warehouses = array();

    /**
     * ATSCProductStock constructor.
     */
    public function __construct()
    {
        $this->groupsPrices = array();
        $this->warehouses = array();
    }

    /**
     * Créé un objet ErpProductVariation à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $productVariationXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpProductVariation
     */
    public static function createFromXML($productVariationXml)
    {
        $ErpProductVariation = new ErpProductVariation();
        if ($productVariationXml) {
            $ErpProductVariation->atoosync_key = (string)$productVariationXml->atoosync_key;
            $ErpProductVariation->atoosync_odbc_key = (string)$productVariationXml->atoosync_odbc_key;
            $ErpProductVariation->reference = (string)$productVariationXml->reference;
            $ErpProductVariation->supplier_reference = (string)$productVariationXml->supplier_reference;
            $ErpProductVariation->supplier_ean13 = (string)$productVariationXml->supplier_ean13;
            $ErpProductVariation->supplier_upc = (string)$productVariationXml->supplier_upc;
            $ErpProductVariation->location = (string)$productVariationXml->location;
            $ErpProductVariation->ean13 = (string)$productVariationXml->ean13;
            $ErpProductVariation->upc = (string)$productVariationXml->upc;
            $ErpProductVariation->isbn = (string)$productVariationXml->isbn;
            $ErpProductVariation->wholesale_price = (float)$productVariationXml->wholesale_price;
            $ErpProductVariation->price = (float)$productVariationXml->price;
            $ErpProductVariation->price_tax_exclude = (float)$productVariationXml->price_tax_exclude;
            $ErpProductVariation->price_tax_include = (float)$productVariationXml->price_tax_include;
            $ErpProductVariation->price_tax = (float)$productVariationXml->price_tax;
            $ErpProductVariation->tax_rate = (float)$productVariationXml->tax_rate;
            $ErpProductVariation->ecotax = (float)$productVariationXml->ecotax;
            $ErpProductVariation->quantity = (float)$productVariationXml->quantity;
            $ErpProductVariation->weight = (float)$productVariationXml->weight;
            $ErpProductVariation->unit_price_impact = (float)$productVariationXml->unit_price_impact;
            $ErpProductVariation->default_on = ((int)$productVariationXml->default_on == 1);
            $ErpProductVariation->minimal_quantity = (float)$productVariationXml->minimal_quantity;
            $ErpProductVariation->available_date = (string)$productVariationXml->available_date;
            $ErpProductVariation->variation_value_1 = (string)$productVariationXml->variation_value_1;
            $ErpProductVariation->variation_value_2 = (string)$productVariationXml->variation_value_2;
            $ErpProductVariation->variation_value_3 = (string)$productVariationXml->variation_value_3;
            $ErpProductVariation->variation_value_4 = (string)$productVariationXml->variation_value_4;
            $ErpProductVariation->variation_value_5 = (string)$productVariationXml->variation_value_5;
            $ErpProductVariation->variation_value_6 = (string)$productVariationXml->variation_value_6;
            $ErpProductVariation->variation_value_7 = (string)$productVariationXml->variation_value_7;
            $ErpProductVariation->variation_value_8 = (string)$productVariationXml->variation_value_8;
            $ErpProductVariation->variation_value_9 = (string)$productVariationXml->variation_value_9;
            $ErpProductVariation->variation_value_10 = (string)$productVariationXml->variation_value_10;
            $ErpProductVariation->packaging_name = (string)$productVariationXml->packaging_name;
            $ErpProductVariation->packaging_quantity = (float)$productVariationXml->packaging_quantity;
            $ErpProductVariation->stock_real = (float)$productVariationXml->stock_real;
            $ErpProductVariation->stock_virtual = (float)$productVariationXml->stock_virtual;
            $ErpProductVariation->stock_available = (float)$productVariationXml->stock_available;
            $ErpProductVariation->stock_target = (float)$productVariationXml->stock_target;
            $ErpProductVariation->stock_real_minus_orders = (float)$productVariationXml->stock_real_minus_orders;
            $ErpProductVariation->stock_target_minus_purchase_orders = (float)$productVariationXml->stock_target_minus_purchase_orders;
            $ErpProductVariation->next_delivery_date = (float)$productVariationXml->next_delivery_date;
            $ErpProductVariation->next_delivery_quantity = (float)$productVariationXml->next_delivery_quantity;

            // les prix par groupe de clients de la variation
            if ($productVariationXml->groups_prices) {
                $ErpProductVariation->groupsPrices = array();

                foreach ($productVariationXml->groups_prices->group_price as $groupPriceXml) {
                    $ErpProductVariation->groupsPrices[] = ErpProductVariationGroupPrice::createFromXML($groupPriceXml);
                }
            }

            // les dépots de la variation
            if ($productVariationXml->warehouses) {
                $ErpProductVariation->warehouses = array();

                foreach ($productVariationXml->warehouses->productwarehouse as $productwarehouse) {
                    $ErpProductVariation->warehouses[] = ErpProductWarehouse::createFromXML($productwarehouse);
                }
            }
        }
        return $ErpProductVariation;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<variation>';
        $xml .= '<atoosync_key><![CDATA[' . $this->atoosync_key . ']]></atoosync_key>';
        $xml .= '<atoosync_odbc_key><![CDATA[' . $this->atoosync_odbc_key . ']]></atoosync_odbc_key>';
        $xml .= '<reference><![CDATA[' . $this->reference . ']]></reference>';
        $xml .= '<supplier_reference><![CDATA[' . $this->supplier_reference . ']]></supplier_reference>';
        $xml .= '<supplier_ean13><![CDATA[' . $this->supplier_ean13 . ']]></supplier_ean13>';
        $xml .= '<supplier_upc><![CDATA[' . $this->supplier_upc . ']]></supplier_upc>';
        $xml .= '<location><![CDATA[' . $this->location . ']]></location>';
        $xml .= '<ean13><![CDATA[' . $this->ean13 . ']]></ean13>';
        $xml .= '<upc><![CDATA[' . $this->upc . ']]></upc>';
        $xml .= '<isbn><![CDATA[' . $this->isbn . ']]></isbn>';
        $xml .= '<wholesale_price><![CDATA[' . $this->wholesale_price . ']]></wholesale_price>';
        $xml .= '<price_tax_exclude><![CDATA[' . $this->price_tax_exclude . ']]></price_tax_exclude>';
        $xml .= '<price_tax_include><![CDATA[' . $this->price_tax_include . ']]></price_tax_include>';
        $xml .= '<price_tax><![CDATA[' . $this->price_tax . ']]></price_tax>';
        $xml .= '<tax_rate><![CDATA[' . $this->tax_rate . ']]></tax_rate>';
        $xml .= '<ecotax><![CDATA[' . $this->ecotax . ']]></ecotax>';
        $xml .= '<quantity><![CDATA[' . $this->quantity . ']]></quantity>';
        $xml .= '<weight><![CDATA[' . $this->weight . ']]></weight>';
        $xml .= '<unit_price_impact><![CDATA[' . $this->unit_price_impact . ']]></unit_price_impact>';
        $xml .= '<default_on><![CDATA[' . $this->default_on . ']]></default_on>';
        $xml .= '<minimal_quantity><![CDATA[' . $this->minimal_quantity . ']]></minimal_quantity>';
        $xml .= '<available_date><![CDATA[' . $this->available_date . ']]></available_date>';
        $xml .= '<variation_value_1><![CDATA[' . $this->variation_value_1 . ']]></variation_value_1>';
        $xml .= '<variation_value_2><![CDATA[' . $this->variation_value_2 . ']]></variation_value_2>';
        $xml .= '<variation_value_3><![CDATA[' . $this->variation_value_3 . ']]></variation_value_3>';
        $xml .= '<variation_value_4><![CDATA[' . $this->variation_value_4 . ']]></variation_value_4>';
        $xml .= '<variation_value_5><![CDATA[' . $this->variation_value_5 . ']]></variation_value_5>';
        $xml .= '<variation_value_6><![CDATA[' . $this->variation_value_6 . ']]></variation_value_6>';
        $xml .= '<variation_value_7><![CDATA[' . $this->variation_value_7 . ']]></variation_value_7>';
        $xml .= '<variation_value_8><![CDATA[' . $this->variation_value_8 . ']]></variation_value_8>';
        $xml .= '<variation_value_9><![CDATA[' . $this->variation_value_9 . ']]></variation_value_9>';
        $xml .= '<variation_value_10><![CDATA[' . $this->variation_value_10 . ']]></variation_value_10>';
        $xml .= '<packaging_name><![CDATA[' . $this->packaging_name . ']]></packaging_name>';
        $xml .= '<packaging_quantity><![CDATA[' . $this->packaging_quantity . ']]></packaging_quantity>';
        $xml .= '<stock_real><![CDATA[' . $this->stock_real . ']]></stock_real>';
        $xml .= '<stock_virtual><![CDATA[' . $this->stock_virtual . ']]></stock_virtual>';
        $xml .= '<stock_available><![CDATA[' . $this->stock_available . ']]></stock_available>';
        $xml .= '<stock_target><![CDATA[' . $this->stock_target . ']]></stock_target>';
        $xml .= '<stock_real_minus_orders><![CDATA[' . $this->stock_real_minus_orders . ']]></stock_real_minus_orders>';
        $xml .= '<stock_target_minus_purchase_orders><![CDATA[' . $this->stock_target_minus_purchase_orders . ']]></stock_target_minus_purchase_orders>';
        $xml .= '<next_delivery_date><![CDATA[' . $this->next_delivery_date . ']]></next_delivery_date>';
        $xml .= '<next_delivery_quantity><![CDATA[' . $this->next_delivery_quantity . ']]></next_delivery_quantity>';
        $xml .= '<groups_prices>';
        if (count($this->groupsPrices) > 0) {
            foreach ($this->groupsPrices as $groups_price) {
                $xml .= $groups_price->getXML();
            }
        }
        $xml .= '</groups_prices>';
        $xml .= '<warehouses>';
        if (count($this->warehouses) > 0) {
            foreach ($this->warehouses as $productwarehouse) {
                $xml .= $productwarehouse->getXML();
            }
        }
        $xml .= '</warehouses>';

        $xml .= '</variation>';
        return $xml;
    }
}
