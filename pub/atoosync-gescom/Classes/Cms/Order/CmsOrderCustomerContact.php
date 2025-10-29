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

use AtooNext\AtooSync\Commons\CustomField;

/**
 * Class CmsOrderCustomerContact
 */
class CmsOrderCustomerContact
{
    /** @var string La clé du contact dans le CMS */
    public $contact_key = "";

    /** @var string La société du contact */
    public $company = "";

    /** @var string La civilité du contact */
    public $civility = "";

    /** @var string Le prénom du contact */
    public $firstname = "";

    /** @var string  Le nom de famille du contact */
    public $lastname = "";

    /** @var string  L'email du contact */
    public $email = "";

    /** @var string Le numéro de téléphone du contact */
    public $phone = "";

    /** @var string Le numéro de téléphone modile du contact */
    public $mobile = "";

    /** @var string Le numéro de télécopie du contact */
    public $fax = "";

    /** @var string  Les notes du contact */
    public $notes = "";

    /** @var string  La date de naissance du contact */
    public $birthday = "0000-00-00";

    /** Champs Sage 100c */
    public $sage_contact_function = "";                 // Fonction du contact du client
    public $sage_contact_type = "";                     // Type du contact du client
    public $sage_contact_service = "";                  // Service du contact du client

    /** @var CustomField[] Les champs personnalisé de la ligne de commande */
    public $custom_fields = array();

    /**
     * CmsOrderCustomerContact constructor.
     */
    public function __construct()
    {
        $this->custom_fields = array();
    }

    /**
     * Ajoute un champ personnalisé au client
     *
     * @param string $name Le nom du champ personnalisé
     * @param string $value La valeur du champ personnalisé
     */
    public function addCustomField($name, $value)
    {
        if (!is_array($this->custom_fields)) {
            $this->custom_fields = array();
        }

        $this->custom_fields[] = new CustomField($name, $value);
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<contact>';
        $xml .= '<contact_key><![CDATA[' . $this->contact_key . ']]></contact_key>';
        $xml .= '<company><![CDATA[' . $this->company . ']]></company>';
        $xml .= '<civility><![CDATA[' . $this->civility . ']]></civility>';
        $xml .= '<firstname><![CDATA[' . $this->firstname . ']]></firstname>';
        $xml .= '<lastname><![CDATA[' . $this->lastname . ']]></lastname>';
        $xml .= '<email><![CDATA[' . $this->email . ']]></email>';
        $xml .= '<phone><![CDATA[' . $this->phone . ']]></phone>';
        $xml .= '<mobile><![CDATA[' . $this->mobile . ']]></mobile>';
        $xml .= '<fax><![CDATA[' . $this->fax . ']]></fax>';
        $xml .= '<notes><![CDATA[' . $this->notes . ']]></notes>';
        $xml .= '<birthday><![CDATA[' . $this->birthday . ']]></birthday>';

        /** Champs Sage 100c */
        $xml .= '<sage_contact_function><![CDATA[' . $this->sage_contact_function . ']]></sage_contact_function>';
        $xml .= '<sage_contact_type><![CDATA[' . $this->sage_contact_type . ']]></sage_contact_type>';
        $xml .= '<sage_contact_service><![CDATA[' . $this->sage_contact_service . ']]></sage_contact_service>';


        $xml .= '<custom_fields>';
        if (count($this->custom_fields) > 0) {
            foreach ($this->custom_fields as $custom_field) {
                $xml .= $custom_field->getXML();
            }
        }
        $xml .= '</custom_fields>';

        $xml .= '</contact>';

        return $xml;
    }

    /**
     * Créé un objet CmsOrderCustomerContact à partir du XML des commandes
     *
     * @param \SimpleXMLElement $orderCustomerContactXml XML de la configuration
     * @return CmsOrderCustomerContact
     */
    public static function createFromXML(\SimpleXMLElement $orderCustomerContactXml)
    {
        $cmsOrderCustomerContact = new CmsOrderCustomerContact();
        if ($orderCustomerContactXml) {
            $cmsOrderCustomerContact->contact_key = (string)$orderCustomerContactXml->contact_key;
            $cmsOrderCustomerContact->company = (string)$orderCustomerContactXml->company;
            $cmsOrderCustomerContact->civility = (string)$orderCustomerContactXml->civility;
            $cmsOrderCustomerContact->firstname = (string)$orderCustomerContactXml->firstname;
            $cmsOrderCustomerContact->lastname = (string)$orderCustomerContactXml->lastname;
            $cmsOrderCustomerContact->email = (string)$orderCustomerContactXml->email;
            $cmsOrderCustomerContact->phone = (string)$orderCustomerContactXml->phone;
            $cmsOrderCustomerContact->mobile = (string)$orderCustomerContactXml->mobile;
            $cmsOrderCustomerContact->fax = (string)$orderCustomerContactXml->fax;
            $cmsOrderCustomerContact->notes = (string)$orderCustomerContactXml->notes;
            $cmsOrderCustomerContact->birthday = (string)$orderCustomerContactXml->birthday;
            $cmsOrderCustomerContact->sage_contact_function = (string)$orderCustomerContactXml->sage_contact_function;
            $cmsOrderCustomerContact->sage_contact_type = (string)$orderCustomerContactXml->sage_contact_type;
            $cmsOrderCustomerContact->sage_contact_service = (string)$orderCustomerContactXml->sage_contact_service;

            if ($orderCustomerContactXml->custom_fields) {
                $cmsOrderCustomerContact->custom_fields = array();
                foreach ($orderCustomerContactXml->custom_fields->custom_field as $custom_field) {
                    $cmsOrderCustomerContact->custom_fields[] = CustomField::createFromXml($custom_field);
                }
            }
        }
        return $cmsOrderCustomerContact;
    }
}
