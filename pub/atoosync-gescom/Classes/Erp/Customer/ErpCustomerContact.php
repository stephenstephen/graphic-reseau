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
 * Class ErpCustomerContact
 */
class ErpCustomerContact
{
    /** @var string La clé du contact dans l'ERP */
    public $atoosync_key = "";

    /** @var string La clé du contact dans Sage 100 ODBC */
    public $atoosync_odbc_key = "";

    /** @var string La civilité du contact dans l'ERP */
    public $civility = "";

    /** @var string Le prénom du contact dans l'ERP */
    public $firstname = "";

    /** @var string Le nom du contact dans l'ERP */
    public $lastname = "";

    /** @var string La fonction du contact dans l'ERP */
    public $office = "";

    /** @var string L'adresse email du client dans l'ERP */
    public $email = "";

    /** @var string Le numéro de téléphone du contact dans l'ERP */
    public $phone = "";

    /** @var string Le numéro de téléphone mobile du contact dans l'ERP */
    public $phone_mobile = "";

    /** @var ErpCustomerAddress[] Les adresses du client dans l'ERP */
    public $addresses = array();

    /**
     * ErpCustomerContact constructor.
     */
    public function __construct()
    {
        $this->addresses = array();
    }

    /**
     * Créé un objet ErpCustomerContact à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $customerContactXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpCustomerContact
     */
    public static function createFromXML($customerContactXml)
    {
        $ErpCustomerContact = new ErpCustomerContact();
        if ($customerContactXml) {
            $ErpCustomerContact->atoosync_key = (string)$customerContactXml->atoosync_key;
            $ErpCustomerContact->atoosync_odbc_key = (string)$customerContactXml->atoosync_odbc_key;
            $ErpCustomerContact->civility = (string)$customerContactXml->civility;
            $ErpCustomerContact->firstname = (string)$customerContactXml->firstname;
            $ErpCustomerContact->lastname = (string)$customerContactXml->lastname;
            $ErpCustomerContact->email = (string)$customerContactXml->email;
            $ErpCustomerContact->office = (string)$customerContactXml->office;
            $ErpCustomerContact->phone = (string)$customerContactXml->phone;
            $ErpCustomerContact->phone_mobile = (string)$customerContactXml->phone_mobile;

            // les adresses du contact
            if ($customerContactXml->addresses) {
                $ErpCustomerContact->addresses = array();
                foreach ($customerContactXml->addresses->address as $addressXml) {
                    $ErpCustomerContact->addresses[] = ErpCustomerAddress::createFromXML($addressXml);
                }
            }
        }
        return $ErpCustomerContact;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<contact>';
        $xml .= '<atoosync_key><![CDATA[' . $this->atoosync_key . ']]></atoosync_key>';
        $xml .= '<atoosync_odbc_key><![CDATA[' . $this->atoosync_odbc_key . ']]></atoosync_odbc_key>';
        $xml .= '<civility><![CDATA[' . $this->civility . ']]></civility>';
        $xml .= '<firstname><![CDATA[' . $this->firstname . ']]></firstname>';
        $xml .= '<lastname><![CDATA[' . $this->lastname . ']]></lastname>';
        $xml .= '<email><![CDATA[' . $this->email . ']]></email>';
        $xml .= '<office><![CDATA[' . $this->office . ']]></office>';
        $xml .= '<phone><![CDATA[' . $this->phone . ']]></phone>';
        $xml .= '<phone_mobile><![CDATA[' . $this->phone_mobile . ']]></phone_mobile>';

        $xml .= '<addresses>';
        if (count($this->addresses) > 0) {
            foreach ($this->addresses as $address) {
                $xml .= $address->getXML();
            }
        }
        $xml .= '</addresses>';

        $xml .= '</contact>';

        return $xml;
    }
}
