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
 * Class CmsOrderAdjustmentTax
 */
class CmsOrderAdjustmentTax
{
    /**  @var string  La clé unique de la taxe */
    public $tax_key = "";

    /** @var float   Montant de l'ajustment de l'avoir */
    public $total_adjustment = 0.00;

    /** @var CmsOrderTax[]  Le détails des taxes de l'ajustement de l'avoir */
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
        $this->total_adjustment = number_format(round($this->total_adjustment, $this->price_precision), $this->price_precision, '.', '');

        $xml = '';
        $xml .= '<adjustment_tax>';
        $xml .= '<tax_key><![CDATA[' . $this->tax_key . ']]></tax_key>';
        $xml .= '<total_adjustment><![CDATA[' . $this->total_adjustment . ']]></total_adjustment>';
        $xml .= '<taxes>';
        if (is_array($this->taxes)) {
            foreach ($this->taxes as $tax) {
                $xml .= $tax->getXML();
            }
        }
        $xml .= '</taxes>';
        $xml .= '</adjustment_tax>';

        return $xml;
    }

    /**
     * Créé un objet CmsOrderAdjustmentTax à partir du XML des commandes
     *
     * @param \SimpleXMLElement $orderAdjustmentTaxXml XML de la configuration
     * @return CmsOrderAdjustmentTax
     */
    public static function createFromXML(\SimpleXMLElement $orderAdjustmentTaxXml)
    {
        $cmsOrderAdjustmentTax = new CmsOrderAdjustmentTax();
        if ($orderAdjustmentTaxXml) {
            $cmsOrderAdjustmentTax->tax_key = (string)$orderAdjustmentTaxXml->tax_key;
            $cmsOrderAdjustmentTax->total_adjustment = (float)$orderAdjustmentTaxXml->total_adjustment;
            $cmsOrderAdjustmentTax->price_precision = 2;
            if ($orderAdjustmentTaxXml->taxes) {
                $cmsOrderAdjustmentTax->taxes = array();
                foreach ($orderAdjustmentTaxXml->taxes->tax as $tax) {
                    $cmsOrderAdjustmentTax->taxes[] = CmsOrderTax::createFromXml($tax);
                }
            }
        }
        return $cmsOrderAdjustmentTax;
    }
}
