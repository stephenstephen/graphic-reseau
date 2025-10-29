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
 * Class CmsOrderDiscount
 */
class CmsOrderDiscount
{
    /** @var string  Le code de la remise */
    public $name = "";

    /** @var float   Le montant de la rmeise en HT */
    public $value_tax_excl = 0.00;

    /** @var float   Le montant de la remise en TTC */
    public $value_tax_incl = 0.00;

    /** @var float   Le montant des taxes de la remise */
    public $value_tax = 0.00;

    /** @var string  Le code de l'article à utiliser  dans l'ERP pour créer la remise */
    public $product_key = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<discount>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '<value_tax_excl><![CDATA[' . $this->value_tax_excl . ']]></value_tax_excl>';
        $xml .= '<value_tax_incl><![CDATA[' . $this->value_tax_incl . ']]></value_tax_incl>';
        $xml .= '<value_tax><![CDATA[' . $this->value_tax . ']]></value_tax>';
        $xml .= '<product_key><![CDATA[' . $this->product_key . ']]></product_key>';
        $xml .= '</discount>';

        return $xml;
    }

    /**
     * Créé un objet CmsOrderDiscount à partir du XML des commandes
     *
     * @param \SimpleXMLElement $orderDiscountXml XML de la configuration
     * @return CmsOrderDiscount
     */
    public static function createFromXml(\SimpleXMLElement $orderDiscountXml)
    {
        $cmsOrderDiscount = new CmsOrderDiscount();
        if ($orderDiscountXml) {
            $cmsOrderDiscount->name = (string)$orderDiscountXml->name;
            $cmsOrderDiscount->value_tax_excl = (float)$orderDiscountXml->value_tax_excl;
            $cmsOrderDiscount->value_tax_incl = (float)$orderDiscountXml->value_tax_incl;
            $cmsOrderDiscount->value_tax = (float)$orderDiscountXml->value_tax;
            $cmsOrderDiscount->product_key = (string)$orderDiscountXml->product_key;
        }
        return $cmsOrderDiscount;
    }
}
