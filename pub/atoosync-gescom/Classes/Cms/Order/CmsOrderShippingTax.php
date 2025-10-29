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
 * Class CmsOrderShippingTax
 */
class CmsOrderShippingTax
{
    /** @var string  La clé unique de la taxe */
    public $tax_key = "";

    /** @var float   Total HT des frais de port avant la remise */
    public $total_shipping_before_discount = 0.00;

    /** @var float   Total HT des frais de port apres la remise */
    public $total_shipping_after_discount = 0.00;

    /** @var float   Total HT de remise */
    public $total_shipping_discount = 0.00;

    /** @var float   Total de l'ecart (entre le total HT et la somme des taxes) */
    public $total_shipping_difference = 0.00;

    /** @var CmsOrderTax[]  Le détails des taxes */
    public $taxes = array();

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
        $this->total_shipping_before_discount = number_format(round($this->total_shipping_before_discount, $this->price_precision), $this->price_precision, '.', '');
        $this->total_shipping_after_discount = number_format(round($this->total_shipping_after_discount, $this->price_precision), $this->price_precision, '.', '');
        $this->total_shipping_discount = number_format(round($this->total_shipping_discount, $this->price_precision), $this->price_precision, '.', '');
        $this->total_shipping_difference = number_format(round($this->total_shipping_difference, $this->price_precision), $this->price_precision, '.', '');

        $xml = '';
        $xml .= '<shipping_tax>';
        $xml .= '<tax_key><![CDATA[' . $this->tax_key . ']]></tax_key>';
        $xml .= '<total_shipping_before_discount><![CDATA[' . $this->total_shipping_before_discount . ']]></total_shipping_before_discount>';
        $xml .= '<total_shipping_after_discount><![CDATA[' . $this->total_shipping_after_discount . ']]></total_shipping_after_discount>';
        $xml .= '<total_shipping_discount><![CDATA[' . $this->total_shipping_discount . ']]></total_shipping_discount>';
        $xml .= '<total_shipping_difference><![CDATA[' . $this->total_shipping_difference . ']]></total_shipping_difference>';
        $xml .= '<taxes>';
        if (is_array($this->taxes)) {
            foreach ($this->taxes as $tax) {
                $xml .= $tax->getXML();
            }
        }
        $xml .= '</taxes>';
        $xml .= '</shipping_tax>';

        return $xml;
    }

    /**
     * Créé un objet CmsOrderShippingTax à partir du XML des commandes
     *
     * @param \SimpleXMLElement $orderShippingTaxXml XML de la configuration
     * @return CmsOrderShippingTax
     */
    public static function createFromXml(\SimpleXMLElement $orderShippingTaxXml)
    {
        $cmsOrderShippingTax = new CmsOrderShippingTax();
        if ($orderShippingTaxXml) {
            $cmsOrderShippingTax->tax_key = (string)$orderShippingTaxXml->tax_key;
            $cmsOrderShippingTax->total_shipping_before_discount = (float)$orderShippingTaxXml->total_shipping_before_discount;
            $cmsOrderShippingTax->total_shipping_after_discount = (float)$orderShippingTaxXml->total_shipping_after_discount;
            $cmsOrderShippingTax->total_shipping_discount = (float)$orderShippingTaxXml->total_shipping_discount;
            $cmsOrderShippingTax->total_shipping_difference = (float)$orderShippingTaxXml->total_shipping_difference;
            if ($orderShippingTaxXml->taxes) {
                $cmsOrderShippingTax->taxes = array();
                foreach ($cmsOrderShippingTax->taxes->tax as $tax) {
                    $cmsOrderShippingTax[] = CmsOrderTax::createFromXML($tax);
                }
            }
            $cmsOrderShippingTax->price_precision = 2;
        }
        return $cmsOrderShippingTax;
    }
}
