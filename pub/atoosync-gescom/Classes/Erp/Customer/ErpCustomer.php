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

use AtooNext\AtooSync\Commons\CustomField;

/**
 * Class ErpCustomer
 */
class ErpCustomer
{
    /** @var string La clé du client dans l'ERP */
    public $atoosync_key = "";

    /** @var string La clé du groupe client du client dans l'ERP */
    public $customer_group_key = "";

    /** @var string La société du client dans l'ERP */
    public $company = "";

    /** @var string La numéro de siret du client dans l'ERP */
    public $siret = "";

    /** @var string Le code APE du client dans l'ERP */
    public $ape = "";

    /** @var string Le prénom du client dans l'ERP */
    public $firstname = "";

    /** @var string Le nom du client dans l'ERP */
    public $lastname = "";

    /** @var string L'adresse email du client dans l'ERP */
    public $email = "";

    /** @var string L'Url du site web du client dans l'ERP */
    public $website = "";

    /** @var float L'encours du client dans l'ERP */
    public $outstanding_allow_amount = 0.00;

    /** @var int Nombre de jour de délais de paiement du client dans l'ERP */
    public $max_payment_days = 0;

    /** @var string Les notes du client dans l'ERP */
    public $note = "";

    /** @var string Le code/nom du représentant du client dans l'ERP */
    public $sales_representative = "";

    /** @var string Le code/nom du mode de règlement du client dans l'ERP */
    public $settlement_mode = "";

    /** @var ErpCustomerAddress[] Les adresses du client dans l'ERP */
    public $addresses = array();

    /** @var ErpCustomerContact[] Les contacts du client dans l'ERP */
    public $contacts = array();

    /** @var CustomField[] Les champs personnalisés de l'article dans l'ERP */
    public $customFields = array();

    /** @var ErpCustomerSage100Informations Les informations de la fiche client dans Sage 100 */
    public $sage100Informations = null;

    /**
     * ErpCustomer constructor.
     */
    public function __construct()
    {
        $this->addresses = array();
        $this->customFields = array();
    }

    /**
     * Créé un objet ErpCustomer à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $customerXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpCustomer
     */
    public static function createFromXML($customerXml)
    {
        $ErpCustomer = new ErpCustomer();
        if ($customerXml) {
            $ErpCustomer->atoosync_key = (string)$customerXml->atoosync_key;
            $ErpCustomer->customer_group_key = (string)$customerXml->customer_group_key;
            $ErpCustomer->company = (string)$customerXml->company;
            $ErpCustomer->siret = (string)$customerXml->siret;
            $ErpCustomer->ape = (string)$customerXml->ape;
            $ErpCustomer->firstname = (string)$customerXml->firstname;
            $ErpCustomer->lastname = (string)$customerXml->lastname;
            $ErpCustomer->email = (string)$customerXml->email;
            $ErpCustomer->website = (string)$customerXml->website;
            $ErpCustomer->outstanding_allow_amount = (float)$customerXml->outstanding_allow_amount;
            $ErpCustomer->max_payment_days = (int)$customerXml->max_payment_days;
            $ErpCustomer->sales_representative = (string)$customerXml->sales_representative;
            $ErpCustomer->settlement_mode = (string)$customerXml->settlement_mode;
            $ErpCustomer->note = (string)$customerXml->note;

            // les adresses du client
            if ($customerXml->addresses) {
                $ErpCustomer->addresses = array();
                foreach ($customerXml->addresses->address as $addressXml) {
                    $ErpCustomer->addresses[] = ErpCustomerAddress::createFromXML($addressXml);
                }
            }

            // les contacts du client
            if ($customerXml->contacts) {
                $ErpCustomer->contacts = array();
                foreach ($customerXml->contacts->contact as $contactXml) {
                    $ErpCustomer->contacts[] = ErpCustomerContact::createFromXML($contactXml);
                }
            }

            // les champs custom du client
            if ($customerXml->custom_fields) {
                $ErpCustomer->customFields = array();
                foreach ($customerXml->custom_fields->custom_field as $custom_field) {
                    $ErpCustomer->customFields[] = new CustomField((string)$custom_field->name, (string)$custom_field->value);
                }
            }

            // Informations Sage 100 si présent
            if ($customerXml->sage100_informations) {
                $ErpCustomer->sage100Informations = ErpCustomerSage100Informations::createFromXML($customerXml->sage100_informations);
            }
        }
        return $ErpCustomer;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<customers>';
        $xml .= '<customer>';
        $xml .= '<atoosync_key><![CDATA[' . $this->atoosync_key . ']]></atoosync_key>';
        $xml .= '<customer_group_key><![CDATA[' . $this->customer_group_key . ']]></customer_group_key>';
        $xml .= '<company><![CDATA[' . $this->company . ']]></company>';
        $xml .= '<siret><![CDATA[' . $this->siret . ']]></siret>';
        $xml .= '<ape><![CDATA[' . $this->ape . ']]></ape>';
        $xml .= '<firstname><![CDATA[' . $this->firstname . ']]></firstname>';
        $xml .= '<lastname><![CDATA[' . $this->lastname . ']]></lastname>';
        $xml .= '<email><![CDATA[' . $this->email . ']]></email>';
        $xml .= '<website><![CDATA[' . $this->website . ']]></website>';
        $xml .= '<outstanding_allow_amount><![CDATA[' . $this->outstanding_allow_amount . ']]></outstanding_allow_amount>';
        $xml .= '<max_payment_days><![CDATA[' . $this->max_payment_days . ']]></max_payment_days>';
        $xml .= '<sales_representative><![CDATA[' . $this->sales_representative . ']]></sales_representative>';
        $xml .= '<settlement_mode><![CDATA[' . $this->settlement_mode . ']]></settlement_mode>';
        $xml .= '<note><![CDATA[' . $this->note . ']]></note>';

        $xml .= '<addresses>';
        if (count($this->addresses) > 0) {
            foreach ($this->addresses as $address) {
                $xml .= $address->getXML();
            }
        }
        $xml .= '</addresses>';

        $xml .= '<contacts>';
        if (count($this->contacts) > 0) {
            foreach ($this->contacts as $contact) {
                $xml .= $contact->getXML();
            }
        }
        $xml .= '</contacts>';

        $xml .= '<custom_fields>';
        if (count($this->customFields) > 0) {
            foreach ($this->customFields as $custom_field) {
                $xml .= $custom_field->getXML();
            }
        }
        $xml .= '</custom_fields>';

        if (!is_null($this->sage100Informations)) {
            $xml .= $this->sage100Informations->getXML();
        }
        $xml .= '</customer>';
        $xml .= '</customers>';

        return $xml;
    }
}
