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
 * Class ErpProductVariationGroupPrice
 */
class ErpProductVariationGroupPrice
{
    const REDUCTION_TYPE_AMOUNT = 'amount';
    const REDUCTION_TYPE_PERCENT = 'percentage';

    /** @var string La clé du groupe de client dans l'ERP */
    public $erp_customer_group_key = "";

    /** @var string La clé du client dans l'ERP */
    public $erp_customer_key = "";

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

    /** @var float Le seuil bas du tarif par quantité dans l'ERP */
    public $lower_bound = 0.00;

    /** @var float Le seuil haut du tarif par quantité dans l'ERP */
    public $upper_bound = 0.00;

    /**
     * Créé un objet ErpProductVariationGroupPrice à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $productGroupPriceXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpProductVariationGroupPrice
     */
    public static function createFromXML($productGroupPriceXml)
    {
        $ErpProductGroupPrice = new ErpProductVariationGroupPrice();
        if ($productGroupPriceXml) {
            $ErpProductGroupPrice->erp_customer_group_key = (string)$productGroupPriceXml->erp_customer_group_key;
            $ErpProductGroupPrice->erp_customer_key = (string)$productGroupPriceXml->erp_customer_key;
            $ErpProductGroupPrice->price = (float)$productGroupPriceXml->price;
            $ErpProductGroupPrice->price_tax_exclude = (float)$productGroupPriceXml->price_tax_exclude;
            $ErpProductGroupPrice->from_quantity = (float)$productGroupPriceXml->from_quantity;
            $ErpProductGroupPrice->reduction = (float)$productGroupPriceXml->reduction;
            if ((string)$productGroupPriceXml->reduction_type == ErpProductVariationGroupPrice::REDUCTION_TYPE_AMOUNT) {
                $ErpProductGroupPrice->reduction_type = ErpProductVariationGroupPrice::REDUCTION_TYPE_AMOUNT;
            } else {
                $ErpProductGroupPrice->reduction_type = ErpProductVariationGroupPrice::REDUCTION_TYPE_PERCENT;
            }
            $ErpProductGroupPrice->lower_bound = (float)$productGroupPriceXml->lower_bound;
            $ErpProductGroupPrice->upper_bound = (float)$productGroupPriceXml->upper_bound;
        }
        return $ErpProductGroupPrice;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<group_price>';
        $xml .= '<erp_customer_group_key><![CDATA[' . $this->erp_customer_group_key . ']]></erp_customer_group_key>';
        $xml .= '<erp_customer_key><![CDATA[' . $this->erp_customer_key . ']]></erp_customer_key>';
        $xml .= '<price_tax_exclude><![CDATA[' . $this->price_tax_exclude . ']]></price_tax_exclude>';
        $xml .= '<from_quantity><![CDATA[' . $this->from_quantity . ']]></from_quantity>';
        $xml .= '<reduction><![CDATA[' . $this->reduction . ']]></reduction>';
        $xml .= '<reduction_type><![CDATA[' . $this->reduction_type . ']]></reduction_type>';
        $xml .= '<lower_bound><![CDATA[' . $this->lower_bound . ']]></lower_bound>';
        $xml .= '<upper_bound><![CDATA[' . $this->upper_bound . ']]></upper_bound>';
        $xml .= '</group_price>';
        return $xml;
    }
}
