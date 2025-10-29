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
 * Class ErpCustomerSage100Term
 */
class ErpCustomerSage100Term
{
    /** @var string Le numéro de compte tiers dans Sage 100 */
    public $account_number = "";

    /** @var string Le nom du mode de paiement dans Sage 100 */
    public $settlement = "";

    /** @var integer Le type de condition (0=Jour, 1=Mois Civil, 2=Mois) */
    public $condition = 0;

    /** @var integer Le nombre de jour */
    public $days = 0;

    /** @var integer Le type de répartition (0=Pourcentage, 1=Equilibre, 2=Montant) */
    public $division = 0;

    /**
     * Créé un objet ErpCustomerSage100Term à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $sage100TermsXML L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpCustomerSage100Term
     */
    public static function createFromXML($sage100TermsXML)
    {
        $sage100Term = new ErpCustomerSage100Term();
        if ($sage100TermsXML) {
            $sage100Term->account_number = (string)$sage100TermsXML->account_number;
            $sage100Term->settlement = (string)$sage100TermsXML->settlement;
            $sage100Term->condition = (int)$sage100TermsXML->condition;
            $sage100Term->days = (int)$sage100TermsXML->days;
            $sage100Term->division = (int)$sage100TermsXML->division;
        }
        return $sage100Term;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<term>';
        $xml .= '<account_number><![CDATA[' . $this->account_number . ']]></account_number>';
        $xml .= '<settlement><![CDATA[' . $this->settlement . ']]></settlement>';
        $xml .= '<condition><![CDATA[' . $this->condition . ']]></condition>';
        $xml .= '<days><![CDATA[' . $this->days . ']]></days>';
        $xml .= '<division><![CDATA[' . $this->division . ']]></division>';
        $xml .= '</term>';
        return $xml;
    }
}
