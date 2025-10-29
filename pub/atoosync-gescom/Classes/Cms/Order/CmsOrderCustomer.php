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
 * Class CmsOrderCustomer
 */
class CmsOrderCustomer
{
    /** @var string  Le numéro du code client dans l'ERP */
    public $erp_customer_account = "";

    /** @var string  Le numéro de compte comptable du client dans l'ERP */
    public $erp_accounting_account = "";

    /** @var string  Le numéro de compte comptable auxiliaire du client dans l'ERP */
    public $erp_auxiliary_account = "";

    /** @var string La clé du groupe client du client dans l'ERP */
    public $customer_group_key = "";

    /** @var string La clé unique du client */
    public $customer_key = "";

    /** @var string La société du client */
    public $company = "";

    /** @var string La civilité du client */
    public $civility = "";

    /** @var string Le prénom du client */
    public $firstname = "";

    /** @var string  Le nom de famille du client */
    public $lastname = "";

    /** @var string  L'email du client */
    public $email = "";

    /** @var string  Les notes du client */
    public $notes = "";

    /** @var string  Le numéro de TVA du client */
    public $vat_number = "";

    /** @var string  Le numéro de siret du client */
    public $siret = "";

    /** @var string  Le code APE du client */
    public $ape = "";

    /** @var string  La date de naissance du client */
    public $birthday = "0000-00-00";

    /** @var string Site web du client */
    public $website = "";

    /** @var CmsOrderAddress L'adresse du client, utilisée uniquement pour les prospects */
    public $address = null;


    /** Champs EBP */
    public $ebp_family_code = "";
    public $ebp_subfamily_code = "";
    public $ebp_naturalperson = "1";
    public $ebp_activestate = "0";
    public $ebp_discount_rate = "0";
    public $ebp_second_discount_rate = "0";
    public $ebp_financial_discount_rate = "0";
    public $ebp_allowed_amount = "0";
    public $ebp_settlement_mode_code = "";
    public $ebp_groupe1 = "";
    public $ebp_groupe2 = "";
    public $ebp_colleague_code = "";
    public $ebp_price_list_category_code = "";
    public $ebp_price_with_taxe = "0";
    public $ebp_shipping_code = "";
    public $ebp_must_be_reminder = "0";
    public $ebp_is_customer_account = "0";
    public $ebp_bank_is_principal = "1";
    public $ebp_bank_name = "";
    public $ebp_bank_address1 = "";
    public $ebp_bank_address2 = "";
    public $ebp_bank_address3 = "";
    public $ebp_bank_country = "";
    public $ebp_bank_rib_bban = "";
    public $ebp_bank_iban = "";
    public $ebp_bank_bic = "";

    /** Champs CIEL/Sage50c */
    public $ciel_analytic_code = "";
    public $ciel_family_code = "";
    public $ciel_colleague_code = "";
    public $ciel_vat_type = "Local";
    public $ciel_discount_rate = "";
    public $ciel_miscellaneous_customer = "";
    public $ciel_do_not_follow_up = "1";
    public $ciel_statements = "1";
    public $ciel_charge_with_taxes = "0";
    public $ciel_price_code = "Aucun";
    public $ciel_business_code = "";
    public $ciel_risk = "Aucun";
    public $ciel_function = "";
    public $ciel_bank_name = "";
    public $ciel_bank_address1 = "";
    public $ciel_bank_address2 = "";
    public $ciel_bank_address3 = "";
    public $ciel_bank_postcode = "";
    public $ciel_bank_city = "";
    public $ciel_bank_country = "";
    public $ciel_bank_bank_code = "";
    public $ciel_bank_branch_code = "";
    public $ciel_bank_account_number = "";
    public $ciel_bank_rib_key = "";
    public $ciel_bank_iban = "";
    public $ciel_bank_bic = "";
    public $ciel_bank_on_radius = "1";

    /** Champs Sage 100c */
    public $sage_classifying = "";                      // Classement du client (17 caractères max.) si non renseigné l'intitulé est utilisé
    public $sage_quality = "";                          // Qualité du client (17 caractères max.) si renseigné remplace la configuration d'Atoo-Sync
    public $sage_currency = "";                         // Nom de la devise
    public $sage_accounting_category = "";              // Nom de la catégorie comptable si renseigné remplace la configuration d'Atoo-Sync
    public $sage_price_category = "";                   // Nom de la catégorie tarifaire si renseigné remplace la configuration d'Atoo-Sync
    public $sage_payer_account = "";                    // Numéro du compte payeur si non renseigné le code client est utilisé
    public $sage_central_buying = "";                   // Numéro du compte de la centrale d'achat
    public $sage_colleague = "";                        // Nom et prénom du représentant
    public $sage_analytic_code = "";                    // Numéro du code affaire de type détail
    public $sage_language = "0";                        // 0=Aucune, 1=langue 1, 2=Langue 2
    public $sage_warehouse = "";                        // Nom du dépôt associé au client, si renseigné remplace la configuration d'Atoo-Sync
    public $sage_invoice_number = "1";                  // Nombre de factures
    public $sage_invoice_format = "1";                  // 0 = Aucun, 1 = Défaut, 2 = Pdf, 3 = UBL/XML, 4 = Facturea
    public $sage_nif_type = "0";                        // 0 = NIF, 1 = NIF Intracommunautaire, 2 = Passeport, 3 = Document 4 = Certificat, 5 = Autre document
    public $sage_legal_representative_name = "";        // Intitulé représentant légal
    public $sage_legal_representative_nif = "";         // NIF représentant légal
    public $sage_outstanding_amount = "0";              // En cours du client
    public $sage_outstanding_control = "0";             // 0=Contrôle automatique, 1=Selon code risque, 2=Compte bloqué
    public $sage_line_break = "1";                      // 0=Saut de page sinon nombre de ligne
    public $sage_automatic_matching = "1";              // 0=Non, 1=Oui // Lettrage automatique
    public $sage_automatic_validation = "0";            // 0=Non, 1=Oui // Validation automatique des échéances
    public $sage_pointed_out = "0";                     // 0=Non, 1=Oui // hors rappel/relevé
    public $sage_no_penalty = "0";                      // 0=Non, 1=Oui // non soumis pénalité
    public $sage_contact_function = "";                 // Fonction du contact du client
    public $sage_contact_type = "";                     // Type du contact du client
    public $sage_contact_service = "";                  // Service du contact du client
    public $sage_statistic_01 = "";                     // Statistique n° 1
    public $sage_statistic_02 = "";                     // Statistique n° 2
    public $sage_statistic_03 = "";                     // Statistique n° 3
    public $sage_statistic_04 = "";                     // Statistique n° 4
    public $sage_statistic_05 = "";                     // Statistique n° 5
    public $sage_statistic_06 = "";                     // Statistique n° 6
    public $sage_statistic_07 = "";                     // Statistique n° 7
    public $sage_statistic_08 = "";                     // Statistique n° 8
    public $sage_statistic_09 = "";                     // Statistique n° 9
    public $sage_statistic_10 = "";                     // Statistique n° 10
    public $sage_discount_rate = "0";                   // Taux de remise
    public $sage_lending_rate = "0";                    // Taux d'escompte
    public $sage_raised_rate = "0";                     // Taux relevé
    public $sage_end_of_year_discount = "0";            // Taux R.F.A

    /** @var CmsOrderCustomerContact[] Les contacts du client */
    public $contacts = array();

    /** @var CustomField[] Les champs personnalisé de la ligne de commande */
    public $custom_fields = array();

    /**
     * CmsOrderCustomer constructor.
     */
    public function __construct()
    {
        $this->contacts = array();
        $this->custom_fields = array();
        $this->address = null;
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
        $xml .= '<customer>';
        $xml .= '<erp_customer_account><![CDATA[' . $this->erp_customer_account . ']]></erp_customer_account>';
        $xml .= '<erp_accounting_account><![CDATA[' . $this->erp_accounting_account . ']]></erp_accounting_account>';
        $xml .= '<erp_auxiliary_account><![CDATA[' . $this->erp_auxiliary_account . ']]></erp_auxiliary_account>';
        $xml .= '<customer_group_key><![CDATA[' . $this->customer_group_key . ']]></customer_group_key>';
        $xml .= '<customer_key><![CDATA[' . $this->customer_key . ']]></customer_key>';
        $xml .= '<company><![CDATA[' . $this->company . ']]></company>';
        $xml .= '<civility><![CDATA[' . $this->civility . ']]></civility>';
        $xml .= '<firstname><![CDATA[' . $this->firstname . ']]></firstname>';
        $xml .= '<lastname><![CDATA[' . $this->lastname . ']]></lastname>';
        $xml .= '<email><![CDATA[' . $this->email . ']]></email>';
        $xml .= '<notes><![CDATA[' . $this->notes . ']]></notes>';
        $xml .= '<vat_number><![CDATA[' . $this->vat_number . ']]></vat_number>';
        $xml .= '<siret><![CDATA[' . $this->siret . ']]></siret>';
        $xml .= '<ape><![CDATA[' . $this->ape . ']]></ape>';
        $xml .= '<birthday><![CDATA[' . $this->birthday . ']]></birthday>';
        $xml .= '<website><![CDATA[' . $this->website . ']]></website>';

        // l'adresse de facturation du prospect
        if ($this->address) {
            $xml .= $this->address->getXML('address');
        }

        // champs EBP
        $xml .= '<ebp_family_code><![CDATA[' . $this->ebp_family_code . ']]></ebp_family_code>';
        $xml .= '<ebp_subfamily_code><![CDATA[' . $this->ebp_subfamily_code . ']]></ebp_subfamily_code>';
        $xml .= '<ebp_naturalperson><![CDATA[' . $this->ebp_naturalperson . ']]></ebp_naturalperson>';
        $xml .= '<ebp_activestate><![CDATA[' . $this->ebp_activestate . ']]></ebp_activestate>';
        $xml .= '<ebp_discount_rate><![CDATA[' . $this->ebp_discount_rate . ']]></ebp_discount_rate>';
        $xml .= '<ebp_second_discount_rate><![CDATA[' . $this->ebp_second_discount_rate . ']]></ebp_second_discount_rate>';
        $xml .= '<ebp_financial_discount_rate><![CDATA[' . $this->ebp_financial_discount_rate . ']]></ebp_financial_discount_rate>';
        $xml .= '<ebp_allowed_amount><![CDATA[' . $this->ebp_allowed_amount . ']]></ebp_allowed_amount>';
        $xml .= '<ebp_settlement_mode_code><![CDATA[' . $this->ebp_settlement_mode_code . ']]></ebp_settlement_mode_code>';
        $xml .= '<ebp_groupe1><![CDATA[' . $this->ebp_groupe1 . ']]></ebp_groupe1>';
        $xml .= '<ebp_groupe2><![CDATA[' . $this->ebp_groupe2 . ']]></ebp_groupe2>';
        $xml .= '<ebp_colleague_code><![CDATA[' . $this->ebp_colleague_code . ']]></ebp_colleague_code>';
        $xml .= '<ebp_price_list_category_code><![CDATA[' . $this->ebp_price_list_category_code . ']]></ebp_price_list_category_code>';
        $xml .= '<ebp_price_with_taxe><![CDATA[' . $this->ebp_price_with_taxe . ']]></ebp_price_with_taxe>';
        $xml .= '<ebp_shipping_code><![CDATA[' . $this->ebp_shipping_code . ']]></ebp_shipping_code>';
        $xml .= '<ebp_must_be_reminder><![CDATA[' . $this->ebp_must_be_reminder . ']]></ebp_must_be_reminder>';
        $xml .= '<ebp_is_customer_account><![CDATA[' . $this->ebp_is_customer_account . ']]></ebp_is_customer_account>';
        $xml .= '<ebp_bank_is_principal><![CDATA[' . $this->ebp_bank_is_principal . ']]></ebp_bank_is_principal>';
        $xml .= '<ebp_bank_name><![CDATA[' . $this->ebp_bank_name . ']]></ebp_bank_name>';
        $xml .= '<ebp_bank_address1><![CDATA[' . $this->ebp_bank_address1 . ']]></ebp_bank_address1>';
        $xml .= '<ebp_bank_address2><![CDATA[' . $this->ebp_bank_address2 . ']]></ebp_bank_address2>';
        $xml .= '<ebp_bank_address3><![CDATA[' . $this->ebp_bank_address3 . ']]></ebp_bank_address3>';
        $xml .= '<ebp_bank_country><![CDATA[' . $this->ebp_bank_country . ']]></ebp_bank_country>';
        $xml .= '<ebp_bank_rib_bban><![CDATA[' . $this->ebp_bank_rib_bban . ']]></ebp_bank_rib_bban>';
        $xml .= '<ebp_bank_iban><![CDATA[' . $this->ebp_bank_iban . ']]></ebp_bank_iban>';
        $xml .= '<ebp_bank_bic><![CDATA[' . $this->ebp_bank_bic . ']]></ebp_bank_bic>';

        // champs Ciel / Sage 50c
        $xml .= '<ciel_analytic_code><![CDATA[' . $this->ciel_analytic_code . ']]></ciel_analytic_code>';
        $xml .= '<ciel_family_code><![CDATA[' . $this->ciel_family_code . ']]></ciel_family_code>';
        $xml .= '<ciel_colleague_code><![CDATA[' . $this->ciel_colleague_code . ']]></ciel_colleague_code>';
        $xml .= '<ciel_vat_type><![CDATA[' . $this->ciel_vat_type . ']]></ciel_vat_type>';
        $xml .= '<ciel_discount_rate><![CDATA[' . $this->ciel_discount_rate . ']]></ciel_discount_rate>';
        $xml .= '<ciel_miscellaneous_customer><![CDATA[' . $this->ciel_miscellaneous_customer . ']]></ciel_miscellaneous_customer>';
        $xml .= '<ciel_do_not_follow_up><![CDATA[' . $this->ciel_do_not_follow_up . ']]></ciel_do_not_follow_up>';
        $xml .= '<ciel_statements><![CDATA[' . $this->ciel_statements . ']]></ciel_statements>';
        $xml .= '<ciel_charge_with_taxes><![CDATA[' . $this->ciel_charge_with_taxes . ']]></ciel_charge_with_taxes>';
        $xml .= '<ciel_price_code><![CDATA[' . $this->ciel_price_code . ']]></ciel_price_code>';
        $xml .= '<ciel_business_code><![CDATA[' . $this->ciel_business_code . ']]></ciel_business_code>';
        $xml .= '<ciel_risk><![CDATA[' . $this->ciel_risk . ']]></ciel_risk>';
        $xml .= '<ciel_function><![CDATA[' . $this->ciel_function . ']]></ciel_function>';
        $xml .= '<ciel_bank_name><![CDATA[' . $this->ciel_bank_name . ']]></ciel_bank_name>';
        $xml .= '<ciel_bank_address1><![CDATA[' . $this->ciel_bank_address1 . ']]></ciel_bank_address1>';
        $xml .= '<ciel_bank_address2><![CDATA[' . $this->ciel_bank_address2 . ']]></ciel_bank_address2>';
        $xml .= '<ciel_bank_address3><![CDATA[' . $this->ciel_bank_address3 . ']]></ciel_bank_address3>';
        $xml .= '<ciel_bank_postcode><![CDATA[' . $this->ciel_bank_postcode . ']]></ciel_bank_postcode>';
        $xml .= '<ciel_bank_city><![CDATA[' . $this->ciel_bank_city . ']]></ciel_bank_city>';
        $xml .= '<ciel_bank_country><![CDATA[' . $this->ciel_bank_country . ']]></ciel_bank_country>';
        $xml .= '<ciel_bank_bank_code><![CDATA[' . $this->ciel_bank_bank_code . ']]></ciel_bank_bank_code>';
        $xml .= '<ciel_bank_branch_code><![CDATA[' . $this->ciel_bank_branch_code . ']]></ciel_bank_branch_code>';
        $xml .= '<ciel_bank_account_number><![CDATA[' . $this->ciel_bank_account_number . ']]></ciel_bank_account_number>';
        $xml .= '<ciel_bank_rib_key><![CDATA[' . $this->ciel_bank_rib_key . ']]></ciel_bank_rib_key>';
        $xml .= '<ciel_bank_iban><![CDATA[' . $this->ciel_bank_iban . ']]></ciel_bank_iban>';
        $xml .= '<ciel_bank_bic><![CDATA[' . $this->ciel_bank_bic . ']]></ciel_bank_bic>';
        $xml .= '<ciel_bank_on_radius><![CDATA[' . $this->ciel_bank_on_radius . ']]></ciel_bank_on_radius>';

        /** Champs Sage 100c */
        $xml .= '<sage_classifying><![CDATA[' . $this->sage_classifying . ']]></sage_classifying>';
        $xml .= '<sage_quality><![CDATA[' . $this->sage_quality . ']]></sage_quality>';
        $xml .= '<sage_currency><![CDATA[' . $this->sage_currency . ']]></sage_currency>';
        $xml .= '<sage_accounting_category><![CDATA[' . $this->sage_accounting_category . ']]></sage_accounting_category>';
        $xml .= '<sage_price_category><![CDATA[' . $this->sage_price_category . ']]></sage_price_category>';
        $xml .= '<sage_payer_account><![CDATA[' . $this->sage_payer_account . ']]></sage_payer_account>';
        $xml .= '<sage_central_buying><![CDATA[' . $this->sage_central_buying . ']]></sage_central_buying>';
        $xml .= '<sage_colleague><![CDATA[' . $this->sage_colleague . ']]></sage_colleague>';
        $xml .= '<sage_analytic_code><![CDATA[' . $this->sage_analytic_code . ']]></sage_analytic_code>';
        $xml .= '<sage_language><![CDATA[' . $this->sage_language . ']]></sage_language>';
        $xml .= '<sage_warehouse><![CDATA[' . $this->sage_warehouse . ']]></sage_warehouse>';
        $xml .= '<sage_invoice_number><![CDATA[' . $this->sage_invoice_number . ']]></sage_invoice_number>';
        $xml .= '<sage_invoice_format><![CDATA[' . $this->sage_invoice_format . ']]></sage_invoice_format>';
        $xml .= '<sage_nif_type><![CDATA[' . $this->sage_nif_type . ']]></sage_nif_type>';
        $xml .= '<sage_legal_representative_name><![CDATA[' . $this->sage_legal_representative_name . ']]></sage_legal_representative_name>';
        $xml .= '<sage_legal_representative_nif><![CDATA[' . $this->sage_legal_representative_nif . ']]></sage_legal_representative_nif>';
        $xml .= '<sage_outstanding_amount><![CDATA[' . $this->sage_outstanding_amount . ']]></sage_outstanding_amount>';
        $xml .= '<sage_outstanding_control><![CDATA[' . $this->sage_outstanding_control . ']]></sage_outstanding_control>';
        $xml .= '<sage_line_break><![CDATA[' . $this->sage_line_break . ']]></sage_line_break>';
        $xml .= '<sage_automatic_matching><![CDATA[' . $this->sage_automatic_matching . ']]></sage_automatic_matching>';
        $xml .= '<sage_automatic_validation><![CDATA[' . $this->sage_automatic_validation . ']]></sage_automatic_validation>';
        $xml .= '<sage_pointed_out><![CDATA[' . $this->sage_pointed_out . ']]></sage_pointed_out>';
        $xml .= '<sage_no_penalty><![CDATA[' . $this->sage_no_penalty . ']]></sage_no_penalty>';
        $xml .= '<sage_contact_function><![CDATA[' . $this->sage_contact_function . ']]></sage_contact_function>';
        $xml .= '<sage_contact_type><![CDATA[' . $this->sage_contact_type . ']]></sage_contact_type>';
        $xml .= '<sage_contact_service><![CDATA[' . $this->sage_contact_service . ']]></sage_contact_service>';
        $xml .= '<sage_statistic_01><![CDATA[' . $this->sage_statistic_01 . ']]></sage_statistic_01>';
        $xml .= '<sage_statistic_02><![CDATA[' . $this->sage_statistic_02 . ']]></sage_statistic_02>';
        $xml .= '<sage_statistic_03><![CDATA[' . $this->sage_statistic_03 . ']]></sage_statistic_03>';
        $xml .= '<sage_statistic_04><![CDATA[' . $this->sage_statistic_04 . ']]></sage_statistic_04>';
        $xml .= '<sage_statistic_05><![CDATA[' . $this->sage_statistic_05 . ']]></sage_statistic_05>';
        $xml .= '<sage_statistic_06><![CDATA[' . $this->sage_statistic_06 . ']]></sage_statistic_06>';
        $xml .= '<sage_statistic_07><![CDATA[' . $this->sage_statistic_07 . ']]></sage_statistic_07>';
        $xml .= '<sage_statistic_08><![CDATA[' . $this->sage_statistic_08 . ']]></sage_statistic_08>';
        $xml .= '<sage_statistic_09><![CDATA[' . $this->sage_statistic_09 . ']]></sage_statistic_09>';
        $xml .= '<sage_statistic_10><![CDATA[' . $this->sage_statistic_10 . ']]></sage_statistic_10>';
        $xml .= '<sage_discount_rate><![CDATA[' . $this->sage_discount_rate . ']]></sage_discount_rate>';
        $xml .= '<sage_lending_rate><![CDATA[' . $this->sage_lending_rate . ']]></sage_lending_rate>';
        $xml .= '<sage_raised_rate><![CDATA[' . $this->sage_raised_rate . ']]></sage_raised_rate>';
        $xml .= '<sage_end_of_year_discount><![CDATA[' . $this->sage_end_of_year_discount . ']]></sage_end_of_year_discount>';

        $xml .= '<contacts>';
        if (count($this->contacts) > 0) {
            foreach ($this->contacts as $contact) {
                $xml .= $contact->getXML();
            }
        }
        $xml .= '</contacts>';

        $xml .= '<custom_fields>';
        if (count($this->custom_fields) > 0) {
            foreach ($this->custom_fields as $custom_field) {
                $xml .= $custom_field->getXML();
            }
        }
        $xml .= '</custom_fields>';

        $xml .= '</customer>';

        return $xml;
    }

    /**
     * Créé un objet CmsOrderCustomer à partir du XML des commandes
     *
     * @param \SimpleXMLElement $orderCustomerXml XML des commandes
     * @return CmsOrderCustomer
     */
    public static function createFromXml(\SimpleXMLElement $orderCustomerXml)
    {
        $cmsOrderCustomer = new CmsOrderCustomer();
        if ($orderCustomerXml) {
            $cmsOrderCustomer->erp_customer_account = (string)$orderCustomerXml->erp_customer_account;
            $cmsOrderCustomer->erp_accounting_account = (string)$orderCustomerXml->erp_accounting_account;
            $cmsOrderCustomer->erp_auxiliary_account = (string)$orderCustomerXml->erp_auxiliary_account;
            $cmsOrderCustomer->customer_group_key = (string)$orderCustomerXml->customer_group_key;
            $cmsOrderCustomer->customer_key = (string)$orderCustomerXml->customer_key;
            $cmsOrderCustomer->company = (string)$orderCustomerXml->company;
            $cmsOrderCustomer->civility = (string)$orderCustomerXml->civility;
            $cmsOrderCustomer->firstname = (string)$orderCustomerXml->firstname;
            $cmsOrderCustomer->lastname = (string)$orderCustomerXml->lastname;
            $cmsOrderCustomer->email = (string)$orderCustomerXml->email;
            $cmsOrderCustomer->notes = (string)$orderCustomerXml->notes;
            $cmsOrderCustomer->vat_number = (string)$orderCustomerXml->vat_number;
            $cmsOrderCustomer->siret = (string)$orderCustomerXml->siret;
            $cmsOrderCustomer->ape = (string)$orderCustomerXml->ape;
            $cmsOrderCustomer->birthday = (string)$orderCustomerXml->birthday;
            $cmsOrderCustomer->website = (string)$orderCustomerXml->website;
            if ($orderCustomerXml->address) {
                $cmsOrderCustomer->address = CmsOrderAddress::createFromXml($orderCustomerXml->address);
            }

            $cmsOrderCustomer->ebp_family_code = (string)$orderCustomerXml->ebp_family_code;
            $cmsOrderCustomer->ebp_subfamily_code = (string)$orderCustomerXml->ebp_subfamily_code;
            $cmsOrderCustomer->ebp_naturalperson = (string)$orderCustomerXml->ebp_naturalperson;
            $cmsOrderCustomer->ebp_activestate = (string)$orderCustomerXml->ebp_activestate;
            $cmsOrderCustomer->ebp_discount_rate = (string)$orderCustomerXml->ebp_discount_rate;
            $cmsOrderCustomer->ebp_second_discount_rate = (string)$orderCustomerXml->ebp_second_discount_rate;
            $cmsOrderCustomer->ebp_financial_discount_rate = (string)$orderCustomerXml->ebp_financial_discount_rate;
            $cmsOrderCustomer->ebp_allowed_amount = (string)$orderCustomerXml->ebp_allowed_amount;
            $cmsOrderCustomer->ebp_settlement_mode_code = (string)$orderCustomerXml->ebp_settlement_mode_code;
            $cmsOrderCustomer->ebp_groupe1 = (string)$orderCustomerXml->ebp_groupe1;
            $cmsOrderCustomer->ebp_groupe2 = (string)$orderCustomerXml->ebp_groupe2;
            $cmsOrderCustomer->ebp_colleague_code = (string)$orderCustomerXml->ebp_colleague_code;
            $cmsOrderCustomer->ebp_price_list_category_code = (string)$orderCustomerXml->ebp_price_list_category_code;
            $cmsOrderCustomer->ebp_price_with_taxe = (string)$orderCustomerXml->ebp_price_with_taxe;
            $cmsOrderCustomer->ebp_shipping_code = (string)$orderCustomerXml->ebp_shipping_code;
            $cmsOrderCustomer->ebp_must_be_reminder = (string)$orderCustomerXml->ebp_must_be_reminder;
            $cmsOrderCustomer->ebp_is_customer_account = (string)$orderCustomerXml->ebp_is_customer_account;
            $cmsOrderCustomer->ebp_bank_is_principal = (string)$orderCustomerXml->ebp_bank_is_principal;
            $cmsOrderCustomer->ebp_bank_name = (string)$orderCustomerXml->ebp_bank_name;
            $cmsOrderCustomer->ebp_bank_address1 = (string)$orderCustomerXml->ebp_bank_address1;
            $cmsOrderCustomer->ebp_bank_address2 = (string)$orderCustomerXml->ebp_bank_address2;
            $cmsOrderCustomer->ebp_bank_address3 = (string)$orderCustomerXml->ebp_bank_address3;
            $cmsOrderCustomer->ebp_bank_country = (string)$orderCustomerXml->ebp_bank_country;
            $cmsOrderCustomer->ebp_bank_rib_bban = (string)$orderCustomerXml->ebp_bank_rib_bban;
            $cmsOrderCustomer->ebp_bank_iban = (string)$orderCustomerXml->ebp_bank_iban;
            $cmsOrderCustomer->ebp_bank_bic = (string)$orderCustomerXml->ebp_bank_bic;

            $cmsOrderCustomer->ciel_analytic_code = (string)$orderCustomerXml->ciel_analytic_code;
            $cmsOrderCustomer->ciel_family_code = (string)$orderCustomerXml->ciel_family_code;
            $cmsOrderCustomer->ciel_colleague_code = (string)$orderCustomerXml->ciel_colleague_code;
            $cmsOrderCustomer->ciel_vat_type = (string)$orderCustomerXml->ciel_vat_type;
            $cmsOrderCustomer->ciel_discount_rate = (string)$orderCustomerXml->ciel_discount_rate;
            $cmsOrderCustomer->ciel_miscellaneous_customer = (string)$orderCustomerXml->ciel_miscellaneous_customer;
            $cmsOrderCustomer->ciel_do_not_follow_up = (string)$orderCustomerXml->ciel_do_not_follow_up;
            $cmsOrderCustomer->ciel_statements = (string)$orderCustomerXml->ciel_statements;
            $cmsOrderCustomer->ciel_charge_with_taxes = (string)$orderCustomerXml->ciel_charge_with_taxes;
            $cmsOrderCustomer->ciel_price_code = (string)$orderCustomerXml->ciel_price_code;
            $cmsOrderCustomer->ciel_business_code = (string)$orderCustomerXml->ciel_business_code;
            $cmsOrderCustomer->ciel_risk = (string)$orderCustomerXml->ciel_risk;
            $cmsOrderCustomer->ciel_function = (string)$orderCustomerXml->ciel_function;
            $cmsOrderCustomer->ciel_bank_name = (string)$orderCustomerXml->ciel_bank_name;
            $cmsOrderCustomer->ciel_bank_address1 = (string)$orderCustomerXml->ciel_bank_address1;
            $cmsOrderCustomer->ciel_bank_address2 = (string)$orderCustomerXml->ciel_bank_address2;
            $cmsOrderCustomer->ciel_bank_address3 = (string)$orderCustomerXml->ciel_bank_address3;
            $cmsOrderCustomer->ciel_bank_postcode = (string)$orderCustomerXml->ciel_bank_postcode;
            $cmsOrderCustomer->ciel_bank_city = (string)$orderCustomerXml->ciel_bank_city;
            $cmsOrderCustomer->ciel_bank_country = (string)$orderCustomerXml->ciel_bank_country;
            $cmsOrderCustomer->ciel_bank_bank_code = (string)$orderCustomerXml->ciel_bank_bank_code;
            $cmsOrderCustomer->ciel_bank_branch_code = (string)$orderCustomerXml->ciel_bank_branch_code;
            $cmsOrderCustomer->ciel_bank_account_number = (string)$orderCustomerXml->ciel_bank_account_number;
            $cmsOrderCustomer->ciel_bank_rib_key = (string)$orderCustomerXml->ciel_bank_rib_key;
            $cmsOrderCustomer->ciel_bank_iban = (string)$orderCustomerXml->ciel_bank_iban;
            $cmsOrderCustomer->ciel_bank_bic = (string)$orderCustomerXml->ciel_bank_bic;
            $cmsOrderCustomer->ciel_bank_on_radius = (string)$orderCustomerXml->ciel_bank_on_radius;

            $cmsOrderCustomer->sage_classifying = (string)$orderCustomerXml->sage_classifying;
            $cmsOrderCustomer->sage_quality = (string)$orderCustomerXml->sage_quality;
            $cmsOrderCustomer->sage_currency = (string)$orderCustomerXml->sage_currency;
            $cmsOrderCustomer->sage_accounting_category = (string)$orderCustomerXml->sage_accounting_category;
            $cmsOrderCustomer->sage_price_category = (string)$orderCustomerXml->sage_price_category;
            $cmsOrderCustomer->sage_payer_account = (string)$orderCustomerXml->sage_payer_account;
            $cmsOrderCustomer->sage_central_buying = (string)$orderCustomerXml->sage_central_buying;
            $cmsOrderCustomer->sage_colleague = (string)$orderCustomerXml->sage_colleague;
            $cmsOrderCustomer->sage_analytic_code = (string)$orderCustomerXml->sage_analytic_code;
            $cmsOrderCustomer->sage_language = (string)$orderCustomerXml->sage_language;
            $cmsOrderCustomer->sage_warehouse = (string)$orderCustomerXml->sage_warehouse;
            $cmsOrderCustomer->sage_invoice_number = (string)$orderCustomerXml->sage_invoice_number;
            $cmsOrderCustomer->sage_invoice_format = (string)$orderCustomerXml->sage_invoice_format;
            $cmsOrderCustomer->sage_nif_type = (string)$orderCustomerXml->sage_nif_type;
            $cmsOrderCustomer->sage_legal_representative_name = (string)$orderCustomerXml->sage_legal_representative_name;
            $cmsOrderCustomer->sage_legal_representative_nif = (string)$orderCustomerXml->sage_legal_representative_nif;
            $cmsOrderCustomer->sage_outstanding_amount = (string)$orderCustomerXml->sage_outstanding_amount;
            $cmsOrderCustomer->sage_outstanding_control = (string)$orderCustomerXml->sage_outstanding_control;
            $cmsOrderCustomer->sage_line_break = (string)$orderCustomerXml->sage_line_break;
            $cmsOrderCustomer->sage_automatic_matching = (string)$orderCustomerXml->sage_automatic_matching;
            $cmsOrderCustomer->sage_automatic_validation = (string)$orderCustomerXml->sage_automatic_validation;
            $cmsOrderCustomer->sage_pointed_out = (string)$orderCustomerXml->sage_pointed_out;
            $cmsOrderCustomer->sage_no_penalty = (string)$orderCustomerXml->sage_no_penalty;
            $cmsOrderCustomer->sage_contact_function = (string)$orderCustomerXml->sage_contact_function;
            $cmsOrderCustomer->sage_contact_type = (string)$orderCustomerXml->sage_contact_type;
            $cmsOrderCustomer->sage_contact_service = (string)$orderCustomerXml->sage_contact_service;
            $cmsOrderCustomer->sage_statistic_01 = (string)$orderCustomerXml->sage_statistic_01;
            $cmsOrderCustomer->sage_statistic_02 = (string)$orderCustomerXml->sage_statistic_02;
            $cmsOrderCustomer->sage_statistic_03 = (string)$orderCustomerXml->sage_statistic_03;
            $cmsOrderCustomer->sage_statistic_04 = (string)$orderCustomerXml->sage_statistic_04;
            $cmsOrderCustomer->sage_statistic_05 = (string)$orderCustomerXml->sage_statistic_05;
            $cmsOrderCustomer->sage_statistic_06 = (string)$orderCustomerXml->sage_statistic_06;
            $cmsOrderCustomer->sage_statistic_07 = (string)$orderCustomerXml->sage_statistic_07;
            $cmsOrderCustomer->sage_statistic_08 = (string)$orderCustomerXml->sage_statistic_08;
            $cmsOrderCustomer->sage_statistic_09 = (string)$orderCustomerXml->sage_statistic_09;
            $cmsOrderCustomer->sage_statistic_10 = (string)$orderCustomerXml->sage_statistic_10;
            $cmsOrderCustomer->sage_discount_rate = (string)$orderCustomerXml->sage_discount_rate;
            $cmsOrderCustomer->sage_lending_rate = (string)$orderCustomerXml->sage_lending_rate;
            $cmsOrderCustomer->sage_raised_rate = (string)$orderCustomerXml->sage_raised_rate;
            $cmsOrderCustomer->sage_end_of_year_discount = (string)$orderCustomerXml->sage_end_of_year_discount;

            if ($orderCustomerXml->contacts) {
                $cmsOrderCustomer->contacts = array();
                foreach ($orderCustomerXml->contacts->contact as $contact) {
                    $cmsOrderCustomer->contacts[] = CmsOrderCustomerContact::createFromXML($contact);
                }
            }
            if ($orderCustomerXml->custom_fields) {
                $cmsOrderCustomer->custom_fields = array();
                foreach ($orderCustomerXml->custom_fields->custom_field as $custom_field) {
                    $cmsOrderCustomer->custom_fields[] = CustomField::createFromXml($custom_field);
                }
            }
        }
        return $cmsOrderCustomer;
    }
}
