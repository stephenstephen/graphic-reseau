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
 * Class ErpProductSpecificPrice
 */
class ErpProductSpecificPrice
{
    const REDUCTION_TYPE_AMOUNT = 'amount';
    const REDUCTION_TYPE_PERCENT = 'percentage';

    /** @var string La clé du groupe de client dans l'ERP */
    public $erp_customer_group_key = "";

    /** @var string La clé du client dans l'ERP */
    public $erp_customer_key = "";

    /** @var string La clé de la variation de l'article */
    public $erp_product_attribute_key = "";

    /** @var string La clé de la boutique du CMS */
    public $shop_key = "";

    /**
     * @var float Le prix de vente
     * @deprecated Utiliser price_tax_exclude à la place
     */
    public $price = 0.00;

    /** @var float Le prix de vente HT de l'article */
    public $price_tax_exclude = 0.00;

    /** @var float La quantité minimum */
    public $from_quantity = 0.00;

    /** @var float Le montant de la réduction */
    public $reduction = 0.00;

    /** @var string Le type de la réduction (amount|percent) */
    public $reduction_type = self::REDUCTION_TYPE_AMOUNT;

    /** @var string La date de début du prix spécifique */
    public $from = "0000-00-00 00:00:00";

    /** @var string La date de fin du prix spécifique */
    public $to = "0000-00-00 00:00:00";

    /** @var float Le seuil bas du tarif par quantité dans l'ERP */
    public $lower_bound = 0.00;

    /** @var float Le seuil haut du tarif par quantité dans l'ERP */
    public $upper_bound = 0.00;

    /** @var boolean Inqiue que le prix spécifique est issue d'une promotion */
    public $is_sale = false;

    /**
     * Créé un objet ErpProductSpecificPrice à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $productSpecificPriceXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpProductSpecificPrice
     */
    public static function createFromXML($productSpecificPriceXml)
    {
        $ErpProductSpecificPrice = new ErpProductSpecificPrice();
        if ($productSpecificPriceXml) {
            $ErpProductSpecificPrice->erp_customer_group_key = (string)$productSpecificPriceXml->erp_customer_group_key;
            $ErpProductSpecificPrice->erp_customer_key = (string)$productSpecificPriceXml->erp_customer_key;
            $ErpProductSpecificPrice->erp_product_attribute_key = (string)$productSpecificPriceXml->erp_product_attribute_key;
            $ErpProductSpecificPrice->shop_key = (float)$productSpecificPriceXml->shop_key;
            $ErpProductSpecificPrice->price = (float)$productSpecificPriceXml->price;
            $ErpProductSpecificPrice->price_tax_exclude = (float)$productSpecificPriceXml->price_tax_exclude;
            $ErpProductSpecificPrice->from_quantity = (float)$productSpecificPriceXml->from_quantity;
            $ErpProductSpecificPrice->reduction = (float)$productSpecificPriceXml->reduction;
            if ((string)$productSpecificPriceXml->reduction_type == ErpProductSpecificPrice::REDUCTION_TYPE_AMOUNT) {
                $ErpProductSpecificPrice->reduction_type = ErpProductSpecificPrice::REDUCTION_TYPE_AMOUNT;
            } else {
                $ErpProductSpecificPrice->reduction_type = ErpProductSpecificPrice::REDUCTION_TYPE_PERCENT;
            }
            $ErpProductSpecificPrice->from = (string)$productSpecificPriceXml->from;
            $ErpProductSpecificPrice->to = (string)$productSpecificPriceXml->to;
            $ErpProductSpecificPrice->is_sale = ((int)$productSpecificPriceXml->is_sale == 1);
        }
        return $ErpProductSpecificPrice;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<specific_price>';

        $xml .= '<erp_customer_group_key><![CDATA[' . $this->erp_customer_group_key . ']]></erp_customer_group_key>';
        $xml .= '<erp_customer_key><![CDATA[' . $this->erp_customer_key . ']]></erp_customer_key>';
        $xml .= '<erp_product_attribute_key><![CDATA[' . $this->erp_product_attribute_key . ']]></erp_product_attribute_key>';
        $xml .= '<shop_key><![CDATA[' . $this->shop_key . ']]></shop_key>';
        $xml .= '<price_tax_exclude><![CDATA[' . $this->price_tax_exclude . ']]></price_tax_exclude>';
        $xml .= '<from_quantity><![CDATA[' . $this->from_quantity . ']]></from_quantity>';
        $xml .= '<reduction><![CDATA[' . $this->reduction . ']]></reduction>';
        $xml .= '<reduction_type><![CDATA[' . $this->reduction_type . ']]></reduction_type>';
        $xml .= '<from><![CDATA[' . $this->from . ']]></from>';
        $xml .= '<to><![CDATA[' . $this->to . ']]></to>';
        if ($this->is_sale) {
            $xml .= '<is_sale><![CDATA[' . '1' . ']]></is_sale>';
        } else {
            $xml .= '<is_sale><![CDATA[' . '0' . ']]></is_sale>';
        }

        $xml .= '</specific_price>';
        return $xml;
    }
}
