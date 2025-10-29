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

class CmsOrderProductTaxAnalytic
{
    /** @var string  Le nom du plan de la section analytique */
    public $plan = "";

    /** @var string  Le code de la section analytique */
    public $code = "";

    /** @var float  Le montant de l'écriture de la section analytique */
    public $amount  = 0.00;

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
        $this->amount = number_format(round($this->amount, $this->price_precision), $this->price_precision, '.', '');

        $xml = '';
        $xml .= '<analytic>';
        $xml .= '<plan><![CDATA[' . $this->plan . ']]></plan>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<amount><![CDATA[' . $this->amount . ']]></amount>';
        $xml .= '</analytic>';

        return $xml;
    }

    /**
     * Créé un objet CmsOrderProductTaxAnalytic à partir du XML de l'écriture analytique
     *
     * @param \SimpleXMLElement $orderProductTaxAnalyticXML XML de l'écriture analytique
     * @return CmsOrderProductTaxAnalytic
     */
    public static function createFromXML(\SimpleXMLElement $orderProductTaxAnalyticXML)
    {
        $cmsOrderProductTaxAnalytic = new CmsOrderProductTaxAnalytic();
        if($orderProductTaxAnalyticXML){
            $cmsOrderProductTaxAnalytic->plan = (string)$orderProductTaxAnalyticXML->plan;
            $cmsOrderProductTaxAnalytic->code = (string)$orderProductTaxAnalyticXML->code;
            $cmsOrderProductTaxAnalytic->amount = (float)$orderProductTaxAnalyticXML->amount;
            $cmsOrderProductTaxAnalytic->price_precision = 2;
        }
        return $cmsOrderProductTaxAnalytic;
    }
}