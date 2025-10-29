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
 * Class CmsOrderAddress
 */
class CmsOrderAddress
{
    /** @var string La clé unique de l'adresse */
    public $key = "";

    /** @var string Le nom de l'adresse */
    public $name = "";

    /** @var string La société de l'adresse */
    public $company = "";

    /** @var string La civilté du contact de l'adresse */
    public $civility = "";

    /** @var string Le prénom du contact de l'adresse */
    public $lastname = "";

    /** @var string Le nom du contact de l'adresse */
    public $firstname = "";

    /** @var string La première ligne de l'adresse */
    public $address1 = "";

    /** @var string La seconde ligne de l'adresse */
    public $address2 = "";

    /** @var string La troisième ligne de l'adresse */
    public $address3 = "";

    /** @var string La quatrième ligne de l'adresse */
    public $address4 = "";

    /** @var string Le code postal de l'adresse */
    public $postcode = "";

    /** @var string Le nom de la ville de l'adresse */
    public $city = "";

    /** @var string Le nom de l'état de l'adresse */
    public $state = "";

    /** @var string Le nom du pays de l'adresse */
    public $country = "";

    /** @var string Le code iso du pays de l'adresse */
    public $country_iso_code = "";

    /** @var string Le numéro de téléphone de l'adresse */
    public $phone = "";

    /** @var string Le numéro de téléphone modile de l'adresse */
    public $mobile = "";

    /** @var string Autre champ texte de l'adresse */
    public $other = "";

    /** @var string Le numéro de TVA associé à l'adresse */
    public $vat_number = "";

    /** @var string Le numéro d'identifiant associé à l'adresse */
    public $dni = "";

    /** @var string La civilité du contact de l'adresse */
    public $contact_civility = "";

    /** @var string La fonction du contact de l'adresse */
    public $contact_function = "";

    /** @var string L'url du site web du contact de l'adresse */
    public $website = "";

    /** @var string Le numéro de fax de l'adresse */
    public $fax = "";

    /** @var string L'email de l'adresse */
    public $email = "";

    /** @var string Le numéro de téléphone du bureau de l'adresse */
    public $office = "";

    /** @var string Le numéro de telex de l'adresse */
    public $telex = "";

    /**
     * Formate l'objet en XML
     *
     * @param string $node_name
     * @return string
     */
    public function getXML($node_name)
    {
        $xml = '';
        $xml .= '<' . (string)$node_name . '>';
        $xml .= '<address_key><![CDATA[' . $this->key . ']]></address_key>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '<company><![CDATA[' . $this->company . ']]></company>';
        $xml .= '<civility><![CDATA[' . $this->civility . ']]></civility>';
        $xml .= '<lastname><![CDATA[' . $this->lastname . ']]></lastname>';
        $xml .= '<firstname><![CDATA[' . $this->firstname . ']]></firstname>';
        $xml .= '<address1><![CDATA[' . $this->address1 . ']]></address1>';
        $xml .= '<address2><![CDATA[' . $this->address2 . ']]></address2>';
        $xml .= '<address3><![CDATA[' . $this->address3 . ']]></address3>';
        $xml .= '<address4><![CDATA[' . $this->address4 . ']]></address4>';
        $xml .= '<postcode><![CDATA[' . $this->postcode . ']]></postcode>';
        $xml .= '<city><![CDATA[' . $this->city . ']]></city>';
        $xml .= '<state><![CDATA[' . $this->state . ']]></state>';
        $xml .= '<country><![CDATA[' . $this->country . ']]></country>';
        $xml .= '<country_iso_code><![CDATA[' . $this->country_iso_code . ']]></country_iso_code>';
        $xml .= '<phone><![CDATA[' . $this->phone . ']]></phone>';
        $xml .= '<mobile><![CDATA[' . $this->mobile . ']]></mobile>';
        $xml .= '<other><![CDATA[' . $this->other . ']]></other>';
        $xml .= '<vat_number><![CDATA[' . $this->vat_number . ']]></vat_number>';
        $xml .= '<dni><![CDATA[' . $this->dni . ']]></dni>';

        $xml .= '<contact_civility><![CDATA[' . $this->contact_civility . ']]></contact_civility>';
        $xml .= '<contact_function><![CDATA[' . $this->contact_function . ']]></contact_function>';
        $xml .= '<website><![CDATA[' . $this->website . ']]></website>';
        $xml .= '<fax><![CDATA[' . $this->fax . ']]></fax>';
        $xml .= '<email><![CDATA[' . $this->email . ']]></email>';
        $xml .= '<office><![CDATA[' . $this->office . ']]></office>';
        $xml .= '<telex><![CDATA[' . $this->telex . ']]></telex>';

        $xml .= '</' . (string)$node_name . '>';

        return $xml;
    }

    /**
     * Créé un objet CmsOrderAddress à partir du XML des commandes
     *
     * @param \SimpleXMLElement $orderAddressXml XML de la configuration
     * @return CmsOrderAddress
     */
    public static function createFromXml(\SimpleXMLElement $orderAddressXml)
    {
        $cmsOrderAddress = new CmsOrderAddress();
        if ($orderAddressXml) {
            $cmsOrderAddress->key = $orderAddressXml->key;
            $cmsOrderAddress->name = $orderAddressXml->name;
            $cmsOrderAddress->company = $orderAddressXml->company;
            $cmsOrderAddress->civility = $orderAddressXml->civility;
            $cmsOrderAddress->lastname = $orderAddressXml->lastname;
            $cmsOrderAddress->firstname = $orderAddressXml->firstname;
            $cmsOrderAddress->address1 = $orderAddressXml->address1;
            $cmsOrderAddress->address2 = $orderAddressXml->address2;
            $cmsOrderAddress->address3 = $orderAddressXml->address3;
            $cmsOrderAddress->address4 = $orderAddressXml->address4;
            $cmsOrderAddress->postcode = $orderAddressXml->postcode;
            $cmsOrderAddress->city = $orderAddressXml->city;
            $cmsOrderAddress->state = $orderAddressXml->state;
            $cmsOrderAddress->country = $orderAddressXml->country;
            $cmsOrderAddress->country_iso_code = $orderAddressXml->country_iso_code;
            $cmsOrderAddress->phone = $orderAddressXml->phone;
            $cmsOrderAddress->mobile = $orderAddressXml->mobile;
            $cmsOrderAddress->other = $orderAddressXml->other;
            $cmsOrderAddress->vat_number = $orderAddressXml->vat_number;
            $cmsOrderAddress->dni = $orderAddressXml->dni;
            $cmsOrderAddress->contact_civility = $orderAddressXml->contact_civility;
            $cmsOrderAddress->contact_function = $orderAddressXml->contact_function;
            $cmsOrderAddress->website = $orderAddressXml->website;
            $cmsOrderAddress->fax = $orderAddressXml->fax;
            $cmsOrderAddress->email = $orderAddressXml->email;
            $cmsOrderAddress->office = $orderAddressXml->office;
            $cmsOrderAddress->telex = $orderAddressXml->telex;
        }
        return $cmsOrderAddress;
    }
}
