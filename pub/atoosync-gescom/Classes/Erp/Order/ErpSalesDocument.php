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

namespace AtooNext\AtooSync\Erp\Order;

/**
 * Class ErpSalesDocument
 */
class ErpSalesDocument
{
    const DOCUMENT_TYPE_QUOTE = 'QUOTE';
    const DOCUMENT_TYPE_ORDER = 'ORDER';
    const DOCUMENT_TYPE_DELIVERYSCHEDULE = 'DELIVERYSCHEDULE';
    const DOCUMENT_TYPE_DELIVERY = 'DELIVERY';
    const DOCUMENT_TYPE_RETURN = 'RETURN';
    const DOCUMENT_TYPE_CREDITNOTE = 'CREDITNOTE';
    const DOCUMENT_TYPE_INVOICE = 'INVOICE';
    const DOCUMENT_TYPE_LOCKEDINVOICE = 'LOCKEDINVOICE';

    /** @var string Le type de document dans l'ERP */
    public $document_type = "";

    /** @var string La clé de la commande dans le CMS */
    public $order_key = "";

    /** @var string La clé du devis dans le CMS */
    public $quote_key = "";

    /** @var string Le code du transporteur */
    public $carrier_key = "";

    /** @var string La clé du code client dans l'ERP */
    public $customer_account = "";

    /** @var string Le prénom du client dans l'ERP */
    public $customer_firstname = "";

    /** @var string Le nom du client dans l'ERP */
    public $customer_lastname = "";

    /** @var string La société du client dans l'ERP */
    public $customer_company = "";

    /** @var string L'email du client dans l'ERP */
    public $customer_email = "";

    /** @var string La clé du compte payeur du document dans l'ERP */
    public $payer_account = "";

    /** @var string Le prénom du compte payeur dans l'ERP */
    public $payer_firstname = "";

    /** @var string Le nom du compte payeur dans l'ERP */
    public $payer_lastname = "";

    /** @var string La société du compte payeur dans l'ERP */
    public $payer_company = "";

    /** @var string L'email du compte payeur dans l'ERP */
    public $payer_email = "";

    /** @var string Le numéro de document dans l'ERP */
    public $document_number = "";

    /** @var string La référence du document dans l'ERP */
    public $document_reference = "";

    /** @var string Le nom du fichier PDF */
    public $document_name = "";

    /** @var string La date du document dans l'ERP */
    public $document_date = "";

    /** @var string La date de livraison du document dans l'ERP */
    public $document_delivery_date = "";

    /** @var float Le montant HT du document dans l'ERP */
    public $document_total_tax_excl = 0.0;

    /** @var float Le montant TTC du document dans l'ERP */
    public $document_total_tax_incl = 0.0;

    /** @var float Le montant des taxes du document dans l'ERP */
    public $document_total_taxes = 0.0;

    /** @var float Le montant des frais de port du document dans l'ERP */
    public $document_shipping = 0.0;

    /** @var string Le mode de livraison du document dans l'ERP */
    public $document_shipping_method = "";

    /** @var string Le nom de la devise du document dans l'ERP */
    public $document_currency = "";

    /** @var float Le taux de conversion de la devise du document dans l'ERP */
    public $document_currency_rate = 0.0;

    /** @var string Le nom de l'adresse de livraison du document dans l'ERP */
    public $delivery_name = "";

    /** @var string Le nom de la société de l'adresse de livraison du document dans l'ERP */
    public $delivery_company = "";

    /** @var string Le prénom du contact de l'adresse de livraison du document dans l'ERP */
    public $delivery_firstname = "";

    /** @var string Le nom du contact de l'adresse de livraison du document dans l'ERP */
    public $delivery_lastname = "";

    /** @var string L'adresse 1 de l'adresse de livraison du document dans l'ERP */
    public $delivery_address1 = "";

    /** @var string L'adresse 2 de l'adresse de livraison du document dans l'ERP */
    public $delivery_address2 = "";

    /** @var string L'adresse 3 de l'adresse de livraison du document dans l'ERP */
    public $delivery_address3 = "";

    /** @var string Le code postal de l'adresse de livraison du document dans l'ERP */
    public $delivery_postcode = "";

    /** @var string La ville de l'adresse de livraison du document dans l'ERP */
    public $delivery_city = "";

    /** @var string La région/état de l'adresse de livraison du document dans l'ERP */
    public $delivery_state = "";

    /** @var string Le nom du pays de l'adresse de livraison du document dans l'ERP */
    public $delivery_country = "";

    /** @var string Le numéro de téléphone de l'adresse de livraison du document dans l'ERP */
    public $delivery_phone = "";

    /** @var string Le numéro de mobile de l'adresse de livraison du document dans l'ERP */
    public $delivery_mobile = "";

    /** @var string Le numéro de fax de l'adresse de livraison du document dans l'ERP */
    public $delivery_fax = "";

    /** @var string L'adresse email de l'adresse de livraison du document dans l'ERP */
    public $delivery_email = "";

    /** @var string Le nom de l'adresse de facturation du document dans l'ERP */
    public $invoice_name = "";

    /** @var string Le nom de la société de l'adresse de facturation du document dans l'ERP */
    public $invoice_company = "";

    /** @var string Le prénom du contact de l'adresse de facturation du document dans l'ERP */
    public $invoice_firstname = "";

    /** @var string Le nom du contact de l'adresse de facturation du document dans l'ERP */
    public $invoice_lastname = "";

    /** @var string L'adresse 1 de l'adresse de facturation du document dans l'ERP */
    public $invoice_address1 = "";

    /** @var string L'adresse 2 de l'adresse de facturation du document dans l'ERP */
    public $invoice_address2 = "";

    /** @var string L'adresse 3 de l'adresse de facturation du document dans l'ERP */
    public $invoice_address3 = "";

    /** @var string Le code postal de l'adresse de facturation du document dans l'ERP */
    public $invoice_postcode = "";

    /** @var string La ville de l'adresse de facturation du document dans l'ERP */
    public $invoice_city = "";

    /** @var string La région/état de l'adresse de facturation du document dans l'ERP */
    public $invoice_state = "";

    /** @var string Le nom du pays de l'adresse de facturation du document dans l'ERP */
    public $invoice_country = "";

    /** @var string Le numéro de téléphone de l'adresse de facturation du document dans l'ERP */
    public $invoice_phone = "";

    /** @var string Le numéro de mobile de l'adresse de facturation du document dans l'ERP */
    public $invoice_mobile = "";

    /** @var string Le numéro de fax de l'adresse de facturation du document dans l'ERP */
    public $invoice_fax = "";

    /** @var string L'adresse email de l'adresse de facturation du document dans l'ERP */
    public $invoice_email = "";

    /** @var string Données brut décodées du document en PDF */
    public $documentpdf = "";

    /** @var ErpSalesDocumentProduct[] Les lignes d'articles du document dans l'ERP */
    public $products = array();

    /**
     * ErpSalesDocument constructor.
     */
    public function __construct()
    {
        $this->products = array();
    }

    /**
     * Créé un objet ErpSalesDocument à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $erpSaleDocumentXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpSalesDocument
     */
    public static function createFromXML($erpSaleDocumentXml)
    {
        $erpSalesDocument = new ErpSalesDocument();
        if ($erpSaleDocumentXml) {
            $erpSalesDocument->document_type = (string)$erpSaleDocumentXml->document_type;
            $erpSalesDocument->order_key = (string)$erpSaleDocumentXml->order_key;
            $erpSalesDocument->quote_key = (string)$erpSaleDocumentXml->quote_key;
            $erpSalesDocument->carrier_key = (string)$erpSaleDocumentXml->carrier_key;

            $erpSalesDocument->customer_account = (string)$erpSaleDocumentXml->customer_account;
            $erpSalesDocument->customer_firstname = (string)$erpSaleDocumentXml->customer_firstname;
            $erpSalesDocument->customer_lastname = (string)$erpSaleDocumentXml->customer_lastname;
            $erpSalesDocument->customer_company = (string)$erpSaleDocumentXml->customer_company;
            $erpSalesDocument->customer_email = (string)$erpSaleDocumentXml->customer_email;

            $erpSalesDocument->payer_account = (string)$erpSaleDocumentXml->payer_account;
            $erpSalesDocument->payer_firstname = (string)$erpSaleDocumentXml->payer_firstname;
            $erpSalesDocument->payer_lastname = (string)$erpSaleDocumentXml->payer_lastname;
            $erpSalesDocument->payer_company = (string)$erpSaleDocumentXml->payer_company;
            $erpSalesDocument->payer_email = (string)$erpSaleDocumentXml->payer_email;

            $erpSalesDocument->document_number = (string)$erpSaleDocumentXml->document_number;
            $erpSalesDocument->document_reference = (string)$erpSaleDocumentXml->document_reference;
            $erpSalesDocument->document_name = (string)$erpSaleDocumentXml->document_name;
            $erpSalesDocument->document_date = (string)$erpSaleDocumentXml->document_date;
            $erpSalesDocument->document_delivery_date = (string)$erpSaleDocumentXml->document_delivery_date;
            $erpSalesDocument->document_total_tax_excl = (float)$erpSaleDocumentXml->document_total_tax_excl;
            $erpSalesDocument->document_total_tax_incl = (float)$erpSaleDocumentXml->document_total_tax_incl;
            $erpSalesDocument->document_total_taxes = (float)$erpSaleDocumentXml->document_total_taxes;
            $erpSalesDocument->document_shipping = (float)$erpSaleDocumentXml->document_shipping;
            $erpSalesDocument->document_shipping_method = (string)$erpSaleDocumentXml->document_shipping_method;
            $erpSalesDocument->document_currency = (string)$erpSaleDocumentXml->document_currency;
            $erpSalesDocument->document_currency_rate = (float)$erpSaleDocumentXml->document_currency_rate;

            $erpSalesDocument->delivery_name = (string)$erpSaleDocumentXml->delivery_name;
            $erpSalesDocument->delivery_company = (string)$erpSaleDocumentXml->delivery_company;
            $erpSalesDocument->delivery_firstname = (string)$erpSaleDocumentXml->delivery_firstname;
            $erpSalesDocument->delivery_lastname = (string)$erpSaleDocumentXml->delivery_lastname;
            $erpSalesDocument->delivery_address1 = (string)$erpSaleDocumentXml->delivery_address1;
            $erpSalesDocument->delivery_address2 = (string)$erpSaleDocumentXml->delivery_address2;
            $erpSalesDocument->delivery_address3 = (string)$erpSaleDocumentXml->delivery_address3;
            $erpSalesDocument->delivery_postcode = (string)$erpSaleDocumentXml->delivery_postcode;
            $erpSalesDocument->delivery_city = (string)$erpSaleDocumentXml->delivery_city;
            $erpSalesDocument->delivery_state = (string)$erpSaleDocumentXml->delivery_state;
            $erpSalesDocument->delivery_country = (string)$erpSaleDocumentXml->delivery_country;
            $erpSalesDocument->delivery_phone = (string)$erpSaleDocumentXml->delivery_phone;
            $erpSalesDocument->delivery_mobile = (string)$erpSaleDocumentXml->delivery_mobile;
            $erpSalesDocument->delivery_fax = (string)$erpSaleDocumentXml->delivery_fax;
            $erpSalesDocument->delivery_email = (string)$erpSaleDocumentXml->delivery_email;

            $erpSalesDocument->invoice_name = (string)$erpSaleDocumentXml->invoice_name;
            $erpSalesDocument->invoice_company = (string)$erpSaleDocumentXml->invoice_company;
            $erpSalesDocument->invoice_firstname = (string)$erpSaleDocumentXml->invoice_firstname;
            $erpSalesDocument->invoice_lastname = (string)$erpSaleDocumentXml->invoice_lastname;
            $erpSalesDocument->invoice_address1 = (string)$erpSaleDocumentXml->invoice_address1;
            $erpSalesDocument->invoice_address2 = (string)$erpSaleDocumentXml->invoice_address2;
            $erpSalesDocument->invoice_address3 = (string)$erpSaleDocumentXml->invoice_address3;
            $erpSalesDocument->invoice_postcode = (string)$erpSaleDocumentXml->invoice_postcode;
            $erpSalesDocument->invoice_city = (string)$erpSaleDocumentXml->invoice_city;
            $erpSalesDocument->invoice_state = (string)$erpSaleDocumentXml->invoice_state;
            $erpSalesDocument->invoice_country = (string)$erpSaleDocumentXml->invoice_country;
            $erpSalesDocument->invoice_phone = (string)$erpSaleDocumentXml->invoice_phone;
            $erpSalesDocument->invoice_mobile = (string)$erpSaleDocumentXml->invoice_mobile;
            $erpSalesDocument->invoice_fax = (string)$erpSaleDocumentXml->invoice_fax;
            $erpSalesDocument->invoice_email = (string)$erpSaleDocumentXml->invoice_email;

            $erpSalesDocument->documentpdf = base64_decode((string)$erpSaleDocumentXml->documentpdf);

            // les lignes des articles
            if ($erpSaleDocumentXml->products) {
                $erpSalesDocument->products = array();
                foreach ($erpSaleDocumentXml->products->product as $productXml) {
                    $erpSalesDocument->products[] = ErpSalesDocumentProduct::createFromXML($productXml);
                }
            }
        }
        return $erpSalesDocument;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<document>';
        $xml .= '<document_type><![CDATA[' . $this->document_type . ']]></document_type>';
        $xml .= '<order_key><![CDATA[' . $this->order_key . ']]></order_key>';
        $xml .= '<quote_key><![CDATA[' . $this->quote_key . ']]></quote_key>';
        $xml .= '<carrier_key><![CDATA[' . $this->carrier_key . ']]></carrier_key>';
        $xml .= '<customer_account><![CDATA[' . $this->customer_account . ']]></customer_account>';
        $xml .= '<customer_firstname><![CDATA[' . $this->customer_firstname . ']]></customer_firstname>';
        $xml .= '<customer_lastname><![CDATA[' . $this->customer_lastname . ']]></customer_lastname>';
        $xml .= '<customer_company><![CDATA[' . $this->customer_company . ']]></customer_company>';
        $xml .= '<customer_email><![CDATA[' . $this->customer_email . ']]></customer_email>';
        $xml .= '<payer_account><![CDATA[' . $this->payer_account . ']]></payer_account>';
        $xml .= '<payer_firstname><![CDATA[' . $this->payer_firstname . ']]></payer_firstname>';
        $xml .= '<payer_lastname><![CDATA[' . $this->payer_lastname . ']]></payer_lastname>';
        $xml .= '<payer_company><![CDATA[' . $this->payer_company . ']]></payer_company>';
        $xml .= '<payer_email><![CDATA[' . $this->payer_email . ']]></payer_email>';
        $xml .= '<document_number><![CDATA[' . $this->document_number . ']]></document_number>';
        $xml .= '<document_reference><![CDATA[' . $this->document_reference . ']]></document_reference>';
        $xml .= '<document_name><![CDATA[' . $this->document_name . ']]></document_name>';
        $xml .= '<document_date><![CDATA[' . $this->document_date . ']]></document_date>';
        $xml .= '<document_delivery_date><![CDATA[' . $this->document_delivery_date . ']]></document_delivery_date>';
        $xml .= '<document_total_tax_excl><![CDATA[' . $this->document_total_tax_excl . ']]></document_total_tax_excl>';
        $xml .= '<document_total_tax_incl><![CDATA[' . $this->document_total_tax_incl . ']]></document_total_tax_incl>';
        $xml .= '<document_total_taxes><![CDATA[' . $this->document_total_taxes . ']]></document_total_taxes>';
        $xml .= '<document_shipping><![CDATA[' . $this->document_shipping . ']]></document_shipping>';
        $xml .= '<document_shipping_method><![CDATA[' . $this->document_shipping_method . ']]></document_shipping_method>';
        $xml .= '<document_currency><![CDATA[' . $this->document_currency . ']]></document_currency>';
        $xml .= '<document_currency_rate><![CDATA[' . $this->document_currency_rate . ']]></document_currency_rate>';
        $xml .= '<delivery_name><![CDATA[' . $this->delivery_name . ']]></delivery_name>';
        $xml .= '<delivery_company><![CDATA[' . $this->delivery_company . ']]></delivery_company>';
        $xml .= '<delivery_firstname><![CDATA[' . $this->delivery_firstname . ']]></delivery_firstname>';
        $xml .= '<delivery_lastname><![CDATA[' . $this->delivery_lastname . ']]></delivery_lastname>';
        $xml .= '<delivery_address1><![CDATA[' . $this->delivery_address1 . ']]></delivery_address1>';
        $xml .= '<delivery_address2><![CDATA[' . $this->delivery_address2 . ']]></delivery_address2>';
        $xml .= '<delivery_address3><![CDATA[' . $this->delivery_address3 . ']]></delivery_address3>';
        $xml .= '<delivery_postcode><![CDATA[' . $this->delivery_postcode . ']]></delivery_postcode>';
        $xml .= '<delivery_city><![CDATA[' . $this->delivery_city . ']]></delivery_city>';
        $xml .= '<delivery_state><![CDATA[' . $this->delivery_state . ']]></delivery_state>';
        $xml .= '<delivery_country><![CDATA[' . $this->delivery_country . ']]></delivery_country>';
        $xml .= '<delivery_phone><![CDATA[' . $this->delivery_phone . ']]></delivery_phone>';
        $xml .= '<delivery_mobile><![CDATA[' . $this->delivery_mobile . ']]></delivery_mobile>';
        $xml .= '<delivery_fax><![CDATA[' . $this->delivery_fax . ']]></delivery_fax>';
        $xml .= '<delivery_email><![CDATA[' . $this->delivery_email . ']]></delivery_email>';
        $xml .= '<invoice_name><![CDATA[' . $this->invoice_name . ']]></invoice_name>';
        $xml .= '<invoice_company><![CDATA[' . $this->invoice_company . ']]></invoice_company>';
        $xml .= '<invoice_firstname><![CDATA[' . $this->invoice_firstname . ']]></invoice_firstname>';
        $xml .= '<invoice_lastname><![CDATA[' . $this->invoice_lastname . ']]></invoice_lastname>';
        $xml .= '<invoice_address1><![CDATA[' . $this->invoice_address1 . ']]></invoice_address1>';
        $xml .= '<invoice_address2><![CDATA[' . $this->invoice_address2 . ']]></invoice_address2>';
        $xml .= '<invoice_address3><![CDATA[' . $this->invoice_address3 . ']]></invoice_address3>';
        $xml .= '<invoice_postcode><![CDATA[' . $this->invoice_postcode . ']]></invoice_postcode>';
        $xml .= '<invoice_city><![CDATA[' . $this->invoice_city . ']]></invoice_city>';
        $xml .= '<invoice_state><![CDATA[' . $this->invoice_state . ']]></invoice_state>';
        $xml .= '<invoice_country><![CDATA[' . $this->invoice_country . ']]></invoice_country>';
        $xml .= '<invoice_phone><![CDATA[' . $this->invoice_phone . ']]></invoice_phone>';
        $xml .= '<invoice_mobile><![CDATA[' . $this->invoice_mobile . ']]></invoice_mobile>';
        $xml .= '<invoice_fax><![CDATA[' . $this->invoice_fax . ']]></invoice_fax>';
        $xml .= '<invoice_email><![CDATA[' . $this->invoice_email . ']]></invoice_email>';
        $xml .= '<documentpdf><![CDATA[' . base64_encode($this->documentpdf) . ']]></documentpdf>';

        $xml .= '<products>';
        if (count($this->products) > 0) {
            foreach ($this->products as $product) {
                $xml .= $product->getXML();
            }
        }
        $xml .= '</products>';

        $xml .= '</document>';
        return $xml;
    }
}
