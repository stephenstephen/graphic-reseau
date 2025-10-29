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

namespace AtooNext\AtooSync\Cms\Order;

/**
 * Class CmsOrderTaxDetail
 */
class CmsOrderTax
{
    /** @var string  La clé unique de la taxe */
    public $tax_key = "";

    /** @var string Le nom de la taxe */
    public $tax_name = "";

    /** @var float  Le taux de taxe */
    public $tax_rate = 0.00;

    /** @var float  Le montant avant la remise */
    public $tax_before_discount = 0.00;

    /** @var float  Le montant après la remise */
    public $tax_after_discount = 0.00;

    /** @var float  Le montant de la remise */
    public $tax_discount = 0.00;

    /** @var int La précision des montants */
    public $price_precision = 2;

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        // arrondi les montants
        $this->tax_rate = number_format(round($this->tax_rate, $this->price_precision), $this->price_precision, '.', '');
        $this->tax_before_discount = number_format(round($this->tax_before_discount, $this->price_precision), $this->price_precision, '.', '');
        $this->tax_after_discount = number_format(round($this->tax_after_discount, $this->price_precision), $this->price_precision, '.', '');
        $this->tax_discount = number_format(round($this->tax_discount, $this->price_precision), $this->price_precision, '.', '');

        $xml = '';
        $xml .= '<tax>';
        $xml .= '<tax_key><![CDATA[' . $this->tax_key . ']]></tax_key>';
        $xml .= '<tax_name><![CDATA[' . $this->tax_name . ']]></tax_name>';
        $xml .= '<tax_rate><![CDATA[' . $this->tax_rate . ']]></tax_rate>';
        $xml .= '<tax_before_discount><![CDATA[' . $this->tax_before_discount . ']]></tax_before_discount>';
        $xml .= '<tax_after_discount><![CDATA[' . $this->tax_after_discount . ']]></tax_after_discount>';
        $xml .= '<tax_discount><![CDATA[' . $this->tax_discount . ']]></tax_discount>';
        $xml .= '</tax>';

        return $xml;
    }

    /**
     * Créé un objet CmsOrderTax à partir du XML des commandes
     *
     * @param \SimpleXMLElement $orderTaxXml XML de la configuration
     * @return CmsOrderTax
     */
    public static function createFromXML(\SimpleXMLElement $orderTaxXml)
    {
        $cmsOrderTax = new CmsOrderTax();
        if ($orderTaxXml) {
            $cmsOrderTax->tax_key = (string)$orderTaxXml->tax_key;
            $cmsOrderTax->tax_name = (string)$orderTaxXml->tax_name;
            $cmsOrderTax->tax_rate = (float)$orderTaxXml->tax_rate;
            $cmsOrderTax->tax_before_discount = (float)$orderTaxXml->tax_before_discount;
            $cmsOrderTax->tax_after_discount = (float)$orderTaxXml->tax_after_discount;
            $cmsOrderTax->tax_discount = (float)$orderTaxXml->tax_discount;
            $cmsOrderTax->price_precision = 2;
        }
        return $cmsOrderTax;
    }
}
