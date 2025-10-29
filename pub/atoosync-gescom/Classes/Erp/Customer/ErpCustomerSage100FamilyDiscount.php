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

namespace AtooNext\AtooSync\Erp\Customer;

/**
 * Class ErpCustomerSage100FamilyDiscount
 */
class ErpCustomerSage100FamilyDiscount
{
    /** @var string Le code famille d'articles dans Sage 100 */
    public $family_key = "";

    /** @var string Le nom de la famille d'articles dans Sage 100 */
    public $family_name = "";

    /** @var float Le taux de remise */
    public $percent_discount = 0.00;

    /**
     * Créé un objet ErpCustomerSage100FamilyDiscount à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $sage100FamilyDiscountXML L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpCustomerSage100FamilyDiscount
     */
    public static function createFromXML($sage100FamilyDiscountXML)
    {
        $sage100FamilyDiscount = new ErpCustomerSage100FamilyDiscount();
        if ($sage100FamilyDiscountXML) {
            $sage100FamilyDiscount->family_key = (string)$sage100FamilyDiscountXML->family_key;
            $sage100FamilyDiscount->family_name = (string)$sage100FamilyDiscountXML->family_name;
            $sage100FamilyDiscount->percent_discount = (float)$sage100FamilyDiscountXML->percent_discount;
        }
        return $sage100FamilyDiscount;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<family_discount>';
        $xml .= '<family_key><![CDATA[' . $this->family_key . ']]></family_key>';
        $xml .= '<family_name><![CDATA[' . $this->family_name . ']]></family_name>';
        $xml .= '<percent_discount><![CDATA[' . $this->percent_discount . ']]></percent_discount>';
        $xml .= '</family_discount>';
        return $xml;
    }
}
