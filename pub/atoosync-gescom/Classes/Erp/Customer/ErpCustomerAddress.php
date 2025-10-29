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
 * Class ErpCustomerAddress
 */
class ErpCustomerAddress
{
    const ADDRESS_TYPE_OTHER = 'other';
    const ADDRESS_TYPE_INVOICING = 'invoicing';
    const ADDRESS_TYPE_DELIVERY = 'delivery';

    /** @var string Le type d'adresse dans l'ERP */
    public $address_type = self::ADDRESS_TYPE_DELIVERY;

    /** @var string La clé de l'adresse dans l'ERP */
    public $atoosync_key = "";

    /** @var string La clé de l'adresse dans sage 100 ODBC */
    public $atoosync_odbc_key = "";

    /** @var string Le nom de l'adresse dans l'ERP */
    public $name = "";

    /** @var string La société de l'adressedans l'ERP */
    public $company = "";

    /** @var string Le prénom du contact de l'adresse dans l'ERP */
    public $firstname = "";

    /** @var string Le nom du contact de l'adresse dans l'ERP */
    public $lastname = "";

    /** @var string L'adresse 1 de l'adresse dans l'ERP */
    public $address1 = "";

    /** @var string L'adresse 2 de l'adresse dans l'ERP */
    public $address2 = "";

    /** @var string L'adresse 3 de l'adresse dans l'ERP */
    public $address3 = "";

    /** @var string Le code postal de l'adresse dans l'ERP */
    public $postcode = "";

    /** @var string La ville de l'adresse dans l'ERP */
    public $city = "";

    /** @var string L'état ou le département de l'adresse dans l'ERP */
    public $state = "";

    /** @var string Le pays de l'adresse dans l'ERP */
    public $country = "";

    /** @var string Autre champ de l'adresse dans l'ERP */
    public $other = "";

    /** @var string Le numéro de téléphone de l'adresse dans l'ERP */
    public $phone = "";

    /** @var string Le numéro de téléphone mobile de l'adresse dans l'ERP */
    public $phone_mobile = "";

    /** @var string Le numéro de TVA de l'adresse dans l'ERP */
    public $vat_number = "";

    /** @var string Le numéro d'identifiant de l'adresse dans l'ERP */
    public $dni = "";

    /**
     * Créé un objet ErpCustomerAddress à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $customerAddressXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpCustomerAddress
     */
    public static function createFromXML($customerAddressXml)
    {
        $ErpCustomerAddress = new ErpCustomerAddress();
        if ($customerAddressXml) {
            $ErpCustomerAddress->address_type = (string)$customerAddressXml->address_type;
            $ErpCustomerAddress->atoosync_key = (string)$customerAddressXml->atoosync_key;
            $ErpCustomerAddress->atoosync_odbc_key = (string)$customerAddressXml->atoosync_odbc_key;
            $ErpCustomerAddress->name = (string)$customerAddressXml->name;
            $ErpCustomerAddress->company = (string)$customerAddressXml->company;
            $ErpCustomerAddress->lastname = (string)$customerAddressXml->lastname;
            $ErpCustomerAddress->firstname = (string)$customerAddressXml->firstname;
            $ErpCustomerAddress->address1 = (string)$customerAddressXml->address1;
            $ErpCustomerAddress->address2 = (string)$customerAddressXml->address2;
            $ErpCustomerAddress->address3 = (string)$customerAddressXml->address3;
            $ErpCustomerAddress->postcode = (string)$customerAddressXml->postcode;
            $ErpCustomerAddress->city = (string)$customerAddressXml->city;
            $ErpCustomerAddress->state = (string)$customerAddressXml->state;
            $ErpCustomerAddress->country = (string)$customerAddressXml->country;
            $ErpCustomerAddress->other = (string)$customerAddressXml->other;
            $ErpCustomerAddress->phone = (string)$customerAddressXml->phone;
            $ErpCustomerAddress->phone_mobile = (string)$customerAddressXml->phone_mobile;
            $ErpCustomerAddress->vat_number = (string)$customerAddressXml->vat_number;
            $ErpCustomerAddress->dni = (string)$customerAddressXml->dni;
        }
        return $ErpCustomerAddress;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<address>';

        $xml .= '<address_type><![CDATA[' . $this->address_type . ']]></address_type>';
        $xml .= '<atoosync_key><![CDATA[' . $this->atoosync_key . ']]></atoosync_key>';
        $xml .= '<atoosync_odbc_key><![CDATA[' . $this->atoosync_odbc_key . ']]></atoosync_odbc_key>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '<company><![CDATA[' . $this->company . ']]></company>';
        $xml .= '<lastname><![CDATA[' . $this->lastname . ']]></lastname>';
        $xml .= '<firstname><![CDATA[' . $this->firstname . ']]></firstname>';
        $xml .= '<address1><![CDATA[' . $this->address1 . ']]></address1>';
        $xml .= '<address2><![CDATA[' . $this->address2 . ']]></address2>';
        $xml .= '<address3><![CDATA[' . $this->address3 . ']]></address3>';
        $xml .= '<postcode><![CDATA[' . $this->postcode . ']]></postcode>';
        $xml .= '<city><![CDATA[' . $this->city . ']]></city>';
        $xml .= '<state><![CDATA[' . $this->state . ']]></state>';
        $xml .= '<country><![CDATA[' . $this->country . ']]></country>';
        $xml .= '<other><![CDATA[' . $this->other . ']]></other>';
        $xml .= '<phone><![CDATA[' . $this->phone . ']]></phone>';
        $xml .= '<phone_mobile><![CDATA[' . $this->phone_mobile . ']]></phone_mobile>';
        $xml .= '<vat_number><![CDATA[' . $this->vat_number . ']]></vat_number>';
        $xml .= '<dni><![CDATA[' . $this->dni . ']]></dni>';

        $xml .= '</address>';
        return $xml;
    }
}
