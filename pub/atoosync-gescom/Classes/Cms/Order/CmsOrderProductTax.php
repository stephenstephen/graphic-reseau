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
 * Class CmsOrderProduct
 */
class CmsOrderProductTax
{
    /** @var string  La clé unique de la taxe */
    public $tax_key = "";

    /** @var float Total HT du produit avant la remise */
    public $total_product_before_discount = 0.00;

    /** @var float Total HT du produit apres la remise */
    public $total_product_after_discount = 0.00;

    /** @var float   Total HT de remise */
    public $total_product_discount = 0.00;

    /** @var float   Total de l'ecart (entre le total HT et la somme des taxes) */
    public $total_product_difference = 0.00;

    /** @var CmsOrderTax[]   La liste des taxes des articles des la commande */
    public $taxes = array();

    /** @var CmsOrderProductTaxAnalytic[]  La liste des écritures analytique associé à la taxe de l'article */
    public $analytics = array();

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
        $this->total_product_before_discount = number_format(round($this->total_product_before_discount, $this->price_precision), $this->price_precision, '.', '');
        $this->total_product_after_discount = number_format(round($this->total_product_after_discount, $this->price_precision), $this->price_precision, '.', '');
        $this->total_product_discount = number_format(round($this->total_product_discount, $this->price_precision), $this->price_precision, '.', '');
        $this->total_product_difference = number_format(round($this->total_product_difference, $this->price_precision), $this->price_precision, '.', '');

        $xml = '';
        $xml .= '<product_tax>';
        $xml .= '<tax_key><![CDATA[' . $this->tax_key . ']]></tax_key>';
        $xml .= '<total_product_before_discount><![CDATA[' . $this->total_product_before_discount . ']]></total_product_before_discount>';
        $xml .= '<total_product_after_discount><![CDATA[' . $this->total_product_after_discount . ']]></total_product_after_discount>';
        $xml .= '<total_product_discount><![CDATA[' . $this->total_product_discount . ']]></total_product_discount>';
        $xml .= '<total_product_difference><![CDATA[' . $this->total_product_difference . ']]></total_product_difference>';
        $xml .= '<taxes>';
        if (is_array($this->taxes)) {
            foreach ($this->taxes as $tax) {
                $xml .= $tax->getXML();
            }
        }
        $xml .= '</taxes>';

        $xml .= '<analytics>';
        if (is_array($this->analytics)) {
            foreach ($this->analytics as $analytic) {
                $xml .= $analytic->getXML();
            }
        }
        $xml .= '</analytics>';
        $xml .= '</product_tax>';

        return $xml;
    }

    /**
     * Créé un objet CmsOrderProductTax à partir du XML des commandes
     *
     * @param \SimpleXMLElement $orderProductTaxXml XML de la configuration
     * @return CmsOrderProductTax
     */
    public static function createFromXml(\SimpleXMLElement $orderProductTaxXml)
    {
        $cmsOrderProductTax = new CmsOrderProductTax();
        if ($orderProductTaxXml) {
            $cmsOrderProductTax->tax_key = (string)$orderProductTaxXml->tax_key;
            $cmsOrderProductTax->total_product_before_discount = (float)$orderProductTaxXml->total_product_before_discount;
            $cmsOrderProductTax->total_product_after_discount = (float)$orderProductTaxXml->total_product_after_discount;
            $cmsOrderProductTax->total_product_discount = (float)$orderProductTaxXml->total_product_discount;
            $cmsOrderProductTax->total_product_difference = (float)$orderProductTaxXml->total_product_difference;
            if ($orderProductTaxXml->taxes) {
                $cmsOrderProductTax->taxes = array();
                foreach ($orderProductTaxXml->taxes->tax as $tax) {
                    $cmsOrderProductTax->taxes[] = CmsOrderTax::createFromXML($tax);
                }
            }
            if ($orderProductTaxXml->analytics) {
                $cmsOrderProductTax->analytics = array();
                foreach ($orderProductTaxXml->analytics->analytic as $analytic) {
                    $cmsOrderProductTax->analytics[] = CmsOrderProductTaxAnalytic::createFromXML($analytic);
                }
            }
            $cmsOrderProductTax->price_precision = 2;
        }
        return $cmsOrderProductTax;
    }
}
