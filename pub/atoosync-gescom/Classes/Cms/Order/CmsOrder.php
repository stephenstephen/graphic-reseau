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
 * Class CmsOrder
 */
class CmsOrder
{
    const ORDER_TYPE_SALE = 'SALE';
    const ORDER_TYPE_CREDITNOTE = 'CREDITNOTE';
    const ORDER_TYPE_RETURN = 'RETURN';
    const ORDER_TYPE_QUOTE = 'QUOTE';


    /** @var string Le type de commande  SALE|CREDITNOTE|RETURN */
    public $order_type = self::ORDER_TYPE_SALE;

    /** @var string Le code de la boutique de la commande */
    public $shop_key = '1';

    /** @var string Le code le la commande */
    public $order_key = "";

    /** @var string Le code du devis */
    public $quote_key = "";

    /** @var string Le code l'avoir */
    public $creditnote_key = "";

    /** @var string Le code du retour */
    public $return_key = "";

    /** @var string Le code du panier de la commande */
    public $cart_key = "";

    /** @var string Le code de la zone de la commande */
    public $zone_key = "";

    /** @var string Le code du pays de la commande */
    public $country_key = "";

    /** @var string La date de la commande */
    public $order_date = "0000-00-00";

    /** @var string Le statut de la commande */
    public $order_status = "";

    /** @var string Le numéro de la commande */
    public $order_number = "";

    /** @var string La référence de la commande */
    public $order_reference = "";

    /** @var string La date de livraison de la commande */
    public $delivery_date = "0000-00-00";

    /** @var string La date de la facture */
    public $invoice_date = "0000-00-00";

    /** @var string La numéro de la facture */
    public $invoice_number = "";

    /** @var string La date de l'avoir */
    public $creditnote_date = "0000-00-00";

    /** @var string La numéro de l'avoir */
    public $creditnote_number = "";

    /** @var string La date du retour */
    public $return_date = "0000-00-00";

    /** @var string La numéro du retour */
    public $return_number = "";

    /** @var string La date d'échéance de la facture */
    public $due_date  = "0000-00-00";

    /** @var string Le mode de paiement de la commande */
    public $payment_name = "";

    /** @var string Le code de la devise */
    public $currency_key = "";

    /** @var float Le taux de conversion de la devise */
    public $currency_rate = 1;

    /** @var string Le code du transporteur */
    public $carrier_key = "";

    /** @var string Le nom du transporteur */
    public $carrier_name = "";

    /** @var bool Frais de port gratuit */
    public $free_shipping = false;

    /** @var float montant des frais de port HT */
    public $shipping_tax_excl = 0.00;

    /** @var float montant des frais de port TTC */
    public $shipping_tax_incl = 0.00;

    /** @var float montant des taxes des frais de port */
    public $shipping_tax = 0.00;

    /** @var float Taux de taxe des frais de port */
    public $shipping_tax_rate = 0.00;

    /** @var string Nom de la taxe des frais de port */
    public $shipping_tax_name = "";

    /** @var float Montant de la remise des frais de port HT */
    public $shipping_discount_tax_excl = 0.00;

    /** @var float Montant de la remise des frais de port TTC */
    public $shipping_discount_tax_incl = 0.00;

    /** @var float Montant des frais de port HT après remise */
    public $shipping_final_tax_excl = 0.00;

    /** @var float Montant des frais de port TTC après remise */
    public $shipping_final_tax_incl = 0.00;

    /** @var float Montant des taxes des frais de port après remise */
    public $shipping_final_tax = 0.00;

    /** @var float Montant des frais d'emballage HT */
    public $wrapping_tax_excl = 0.00;

    /** @var float Montant des frais d'emballage TTC */
    public $wrapping_tax_incl = 0.00;

    /** @var float Montant des taxes des frais d'emballage */
    public $wrapping_tax = 0.00;

    /** @var float Taux de taxe des frais d'emballage */
    public $wrapping_tax_rate = 0.00;

    /** @var string Nom de la taxe des frais d'emballage */
    public $wrapping_tax_name = "";

    /** @var float Montant total payé */
    public $total_paid = 0.00;

    /** @var float Montant total payé réellement */
    public $total_paid_real = 0.00;

    /** @var float Montant total des produits HT */
    public $total_products_tax_excl = 0.00;

    /** @var float Montant total des produits TTC */
    public $total_products_tax_incl = 0.00;

    /** @var float Montant total des taxes des produits */
    public $total_products_tax = 0.00;

    /** @var float Montant total HT */
    public $total_tax_excl = 0.00;

    /** @var float Montant total TTC */
    public $total_tax_incl = 0.00;

    /** @var float Montant total des taxes */
    public $total_tax = 0.00;

    /** @var float Montant total des remises HT */
    public $total_discounts_tax_excl = 0.00;

    /** @var float Montant total des remises TTC */
    public $total_discounts_tax_incl = 0.00;

    /** @var float Montant total des taxes des remises */
    public $total_discounts_tax = 0.00;

    /** @var bool Créer les remises en TTC */
    public $create_discount_taxes_included = false;

    /** @var bool Indique si la commande à comme prix de base le TTC */
    public $calculation_taxes_included = false;

    /** @var string Le nom du dépôt de la commande */
    public $warehouse = "";

    /** @var string Les messages de la commande */
    public $messages = "";

    /** @var string Le nom de la série/souche de document à utiliser pour créer le document dans l'ERP */
    public $document_serial = "";

    /** @var string La référence personnalisé 1 de la commande */
    public $custom_reference_1 = "";

    /** @var string La référence personnalisé 2 de la commande */
    public $custom_reference_2 = "";

    /** @var string La référence personnalisé 3 de la commande */
    public $custom_reference_3 = "";

    /** @var string La référence personnalisé 4 de la commande */
    public $custom_reference_4 = "";

    /** @var string La référence personnalisé 5 de la commande */
    public $custom_reference_5 = "";

    /** @var string La code du représentant de la commande dans EBP */
    public $ebp_colleague_code = "";

    /** @var string La code du représentant de la commande dans CIEL/Sage50c */
    public $ciel_colleague_code = "";

    /** @var string La code affaire de la commande dans Ciel/Sage50c */
    public $ciel_business_code = "";

    /** @var string Le statut du document dans Sage 100 (vide par défaut ou 0,1,2)*/
    public $sage_document_status = "";

    /** @var string L'entête 1 de la commande dans Sage 100 */
    public $sage_header1 = "";

    /** @var string L'entête 2 de la commande dans Sage 100 */
    public $sage_header2 = "";

    /** @var string L'entête 3 de la commande dans Sage 100 */
    public $sage_header3 = "";

    /** @var string L'entête 4 de la commande dans Sage 100 */
    public $sage_header4 = "";

    /** @var string Le nom du représentant de la commande dans Sage 100 */
    public $sage_colleague = "";

    /** @var string Le code affaire de la commande dans Sage 100 */
    public $sage_analytic_code = "";

    /** @var integer Le nombre de colis de la commande dans Sage 100 */
    public $sage_number_packages = 1;

    /** @var string Le type de colisage de la commande dans Sage 100 */
    public $sage_packing = "";

    /** @var string La condition de livraison de la commande dans Sage 100 */
    public $sage_delivery_mode = "";

    /** @var string Le compte payeur de la commande dans Sage 100 */
    public $sage_payer_account = "";

    /** @var string Le numéro de compte de la centrale d'achat de la commande dans Sage 100 */
    public $sage_central_buying = "";

    /** @var CmsOrderCustomer Le client de la commande */
    public $customer;

    /** @var CmsOrderAddress L'adresse de facturation de la commande */
    public $invoice_address;

    /** @var CmsOrderAddress L'adresse de livraison de la commande */
    public $delivery_address;

    /** @var CmsOrderPayment[]  La liste des paiements de la commande */
    public $payments = array();

    /**  @var CmsOrderDiscount[]  La liste des réductions de la commande */
    public $discounts = array();

    /**  @var CmsOrderFile[]  La liste des fichiers de la commande */
    public $files = array();

    /** @var CmsOrderProduct[]  La liste des articles de la commande */
    public $products = array();

    /** @var CmsOrderProductTax[]  La liste des taxes des articles de la commande */
    public $productTaxes = array();

    /** @var CmsOrderShippingTax[]  La liste des taxes des frais de port de la commande */
    public $shippingTaxes = array();

    /** @var CmsOrderAdjustmentTax[]  La liste des ajustements de l'avoir */
    public $adjustmentTaxes = array();

    /** @var CustomField[] Les champs personnalisé de la commande */
    public $custom_fields = array();

    /** @var int La précision des montants */
    public $price_precision = 2;

    /**
     * ATSCOrder constructor.
     */
    public function __construct()
    {
        $this->customer = new CmsOrderCustomer();
        $this->invoice_address = new CmsOrderAddress();
        $this->delivery_address = new CmsOrderAddress();
        $this->payments = array();
        $this->discounts = array();
        $this->files = array();
        $this->products = array();
        $this->custom_fields = array();
    }

    /**
     * Ajoute un champ personnalisé à la commande
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
        // arrondi les montants
        $this->shipping_tax_rate = number_format(round($this->shipping_tax_rate, 3), 3, '.', '');
        $this->shipping_tax_excl = number_format(round($this->shipping_tax_excl, $this->price_precision), $this->price_precision, '.', '');
        $this->shipping_tax_incl = number_format(round($this->shipping_tax_incl, $this->price_precision), $this->price_precision, '.', '');
        $this->shipping_tax = number_format(round($this->shipping_tax, $this->price_precision), $this->price_precision, '.', '');
        $this->shipping_discount_tax_excl = number_format(round($this->shipping_discount_tax_excl, $this->price_precision), $this->price_precision, '.', '');
        $this->shipping_discount_tax_incl = number_format(round($this->shipping_discount_tax_incl, $this->price_precision), $this->price_precision, '.', '');
        $this->shipping_final_tax_excl = number_format(round($this->shipping_final_tax_excl, $this->price_precision), $this->price_precision, '.', '');
        $this->shipping_final_tax_incl = number_format(round($this->shipping_final_tax_incl, $this->price_precision), $this->price_precision, '.', '');
        $this->shipping_final_tax = number_format(round($this->shipping_final_tax, $this->price_precision), $this->price_precision, '.', '');

        $this->wrapping_tax_rate = number_format(round($this->wrapping_tax_rate, 3), 3, '.', '');
        $this->wrapping_tax_excl = number_format(round($this->wrapping_tax_excl, $this->price_precision), $this->price_precision, '.', '');
        $this->wrapping_tax_incl = number_format(round($this->wrapping_tax_incl, $this->price_precision), $this->price_precision, '.', '');
        $this->wrapping_tax = number_format(round($this->wrapping_tax, $this->price_precision), $this->price_precision, '.', '');

        $this->total_discounts_tax_excl = number_format(round($this->total_discounts_tax_excl, $this->price_precision), $this->price_precision, '.', '');
        $this->total_discounts_tax_incl = number_format(round($this->total_discounts_tax_incl, $this->price_precision), $this->price_precision, '.', '');
        $this->total_discounts_tax = number_format(round($this->total_discounts_tax, $this->price_precision), $this->price_precision, '.', '');

        $this->total_paid = number_format(round($this->total_paid, $this->price_precision), $this->price_precision, '.', '');
        $this->total_paid_real = number_format(round($this->total_paid_real, $this->price_precision), $this->price_precision, '.', '');
        $this->total_products_tax_excl = number_format(round($this->total_products_tax_excl, $this->price_precision), $this->price_precision, '.', '');
        $this->total_products_tax_incl = number_format(round($this->total_products_tax_incl, $this->price_precision), $this->price_precision, '.', '');
        $this->total_products_tax = number_format(round($this->total_products_tax, $this->price_precision), $this->price_precision, '.', '');
        $this->total_tax_excl = number_format(round($this->total_tax_excl, $this->price_precision), $this->price_precision, '.', '');
        $this->total_tax_incl = number_format(round($this->total_tax_incl, $this->price_precision), $this->price_precision, '.', '');
        $this->total_tax = number_format(round($this->total_tax, $this->price_precision), $this->price_precision, '.', '');

        $xml = '<order>';

        $xml .= '<order_type><![CDATA[' . $this->order_type . ']]></order_type>';
        $xml .= '<shop_key><![CDATA[' . $this->shop_key . ']]></shop_key>';
        $xml .= '<order_key><![CDATA[' . $this->order_key . ']]></order_key>';
        $xml .= '<quote_key><![CDATA[' . $this->quote_key . ']]></quote_key>';
        $xml .= '<creditnote_key><![CDATA[' . $this->creditnote_key . ']]></creditnote_key>';
        $xml .= '<return_key><![CDATA[' . $this->return_key . ']]></return_key>';
        $xml .= '<cart_key><![CDATA[' . $this->cart_key . ']]></cart_key>';
        $xml .= '<zone_key><![CDATA[' . $this->zone_key . ']]></zone_key>';
        $xml .= '<country_key><![CDATA[' . $this->country_key . ']]></country_key>';

        $xml .= '<order_date><![CDATA[' . $this->order_date . ']]></order_date>';
        $xml .= '<order_status><![CDATA[' . $this->order_status . ']]></order_status>';
        $xml .= '<order_number><![CDATA[' . $this->order_number . ']]></order_number>';
        $xml .= '<order_reference><![CDATA[' . $this->order_reference . ']]></order_reference>';

        $xml .= '<delivery_date><![CDATA[' . $this->delivery_date . ']]></delivery_date>';

        $xml .= '<invoice_date><![CDATA[' . $this->invoice_date . ']]></invoice_date>';
        $xml .= '<invoice_number><![CDATA[' . $this->invoice_number . ']]></invoice_number>';

        $xml .= '<creditnote_date><![CDATA[' . $this->creditnote_date . ']]></creditnote_date>';
        $xml .= '<creditnote_number><![CDATA[' . $this->creditnote_number . ']]></creditnote_number>';

        $xml .= '<return_date><![CDATA[' . $this->return_date . ']]></return_date>';
        $xml .= '<return_number><![CDATA[' . $this->return_number . ']]></return_number>';

        $xml .= '<due_date><![CDATA[' . $this->due_date . ']]></due_date>';

        $xml .= '<payment_name><![CDATA[' . $this->payment_name . ']]></payment_name>';

        $xml .= '<currency_key><![CDATA[' . $this->currency_key . ']]></currency_key>';
        $xml .= '<currency_rate><![CDATA[' . $this->currency_rate . ']]></currency_rate>';

        $xml .= '<carrier_key><![CDATA[' . $this->carrier_key . ']]></carrier_key>';
        $xml .= '<carrier_name><![CDATA[' . $this->carrier_name . ']]></carrier_name>';

        $xml .= '<shipping_tax_name><![CDATA[' . $this->shipping_tax_name . ']]></shipping_tax_name>';
        $xml .= '<shipping_tax_rate><![CDATA[' . $this->shipping_tax_rate . ']]></shipping_tax_rate>';
        $xml .= '<shipping_tax_excl><![CDATA[' . $this->shipping_tax_excl . ']]></shipping_tax_excl>';
        $xml .= '<shipping_tax_incl><![CDATA[' . $this->shipping_tax_incl . ']]></shipping_tax_incl>';
        $xml .= '<shipping_tax><![CDATA[' . $this->shipping_tax . ']]></shipping_tax>';
        $xml .= '<shipping_discount_tax_excl><![CDATA[' . $this->shipping_discount_tax_excl . ']]></shipping_discount_tax_excl>';
        $xml .= '<shipping_discount_tax_incl><![CDATA[' . $this->shipping_discount_tax_incl . ']]></shipping_discount_tax_incl>';
        $xml .= '<shipping_final_tax_excl><![CDATA[' . $this->shipping_final_tax_excl . ']]></shipping_final_tax_excl>';
        $xml .= '<shipping_final_tax_incl><![CDATA[' . $this->shipping_final_tax_incl . ']]></shipping_final_tax_incl>';
        $xml .= '<shipping_final_tax><![CDATA[' . $this->shipping_final_tax . ']]></shipping_final_tax>';

        if ($this->free_shipping) {
            $xml .= '<free_shipping><![CDATA[' . '1' . ']]></free_shipping>';
        } else {
            $xml .= '<free_shipping><![CDATA[' . '0' . ']]></free_shipping>';
        }

        $xml .= '<wrapping_tax_name><![CDATA[' . $this->wrapping_tax_name . ']]></wrapping_tax_name>';
        $xml .= '<wrapping_tax_rate><![CDATA[' . $this->wrapping_tax_rate . ']]></wrapping_tax_rate>';
        $xml .= '<wrapping_tax_excl><![CDATA[' . $this->wrapping_tax_excl . ']]></wrapping_tax_excl>';
        $xml .= '<wrapping_tax_incl><![CDATA[' . $this->wrapping_tax_incl . ']]></wrapping_tax_incl>';
        $xml .= '<wrapping_tax><![CDATA[' . $this->wrapping_tax . ']]></wrapping_tax>';


        $xml .= '<total_paid><![CDATA[' . $this->total_paid . ']]></total_paid>';
        $xml .= '<total_paid_real><![CDATA[' . $this->total_paid_real . ']]></total_paid_real>';
        $xml .= '<total_tax_excl><![CDATA[' . $this->total_tax_excl . ']]></total_tax_excl>';
        $xml .= '<total_tax_incl><![CDATA[' . $this->total_tax_incl . ']]></total_tax_incl>';
        $xml .= '<total_tax><![CDATA[' . $this->total_tax . ']]></total_tax>';
        $xml .= '<total_products_tax_excl><![CDATA[' . $this->total_products_tax_excl . ']]></total_products_tax_excl>';
        $xml .= '<total_products_tax_incl><![CDATA[' . $this->total_products_tax_incl . ']]></total_products_tax_incl>';
        $xml .= '<total_products_tax><![CDATA[' . $this->total_products_tax . ']]></total_products_tax>';

        $xml .= '<total_discounts_tax_excl><![CDATA[' . $this->total_discounts_tax_excl . ']]></total_discounts_tax_excl>';
        $xml .= '<total_discounts_tax_incl><![CDATA[' . $this->total_discounts_tax_incl . ']]></total_discounts_tax_incl>';
        $xml .= '<total_discounts_tax><![CDATA[' . $this->total_discounts_tax . ']]></total_discounts_tax>';

        if ($this->create_discount_taxes_included) {
            $xml .= '<create_discount_taxes_included><![CDATA[' . '1' . ']]></create_discount_taxes_included>';
        } else {
            $xml .= '<create_discount_taxes_included><![CDATA[' . '0' . ']]></create_discount_taxes_included>';
        }
        if ($this->calculation_taxes_included) {
            $xml .= '<calculation_taxes_included><![CDATA[' . '1' . ']]></calculation_taxes_included>';
        } else {
            $xml .= '<calculation_taxes_included><![CDATA[' . '0' . ']]></calculation_taxes_included>';
        }

        $xml .= '<warehouse><![CDATA[' . $this->warehouse . ']]></warehouse>';
        $xml .= '<messages><![CDATA[' . $this->messages . ']]></messages>';
        $xml .= '<document_serial><![CDATA[' . $this->document_serial . ']]></document_serial>';
        $xml .= '<custom_reference_1><![CDATA[' . $this->custom_reference_1 . ']]></custom_reference_1>';
        $xml .= '<custom_reference_2><![CDATA[' . $this->custom_reference_2 . ']]></custom_reference_2>';
        $xml .= '<custom_reference_3><![CDATA[' . $this->custom_reference_3 . ']]></custom_reference_3>';
        $xml .= '<custom_reference_4><![CDATA[' . $this->custom_reference_4 . ']]></custom_reference_4>';
        $xml .= '<custom_reference_5><![CDATA[' . $this->custom_reference_5 . ']]></custom_reference_5>';

        // champs EBP
        $xml .= '<ebp_colleague_code><![CDATA[' . $this->ebp_colleague_code . ']]></ebp_colleague_code>';
        // Champs CIEL / Sage50c
        $xml .= '<ciel_colleague_code><![CDATA[' . $this->ciel_colleague_code . ']]></ciel_colleague_code>';
        $xml .= '<ciel_business_code><![CDATA[' . $this->ciel_business_code . ']]></ciel_business_code>';
        // Champs Sage 100
        $xml .= '<sage_document_status><![CDATA[' . $this->sage_document_status . ']]></sage_document_status>';
        $xml .= '<sage_header1><![CDATA[' . $this->sage_header1 . ']]></sage_header1>';
        $xml .= '<sage_header2><![CDATA[' . $this->sage_header2 . ']]></sage_header2>';
        $xml .= '<sage_header3><![CDATA[' . $this->sage_header3 . ']]></sage_header3>';
        $xml .= '<sage_header4><![CDATA[' . $this->sage_header4 . ']]></sage_header4>';
        $xml .= '<sage_colleague><![CDATA[' . $this->sage_colleague . ']]></sage_colleague>';
        $xml .= '<sage_analytic_code><![CDATA[' . $this->sage_analytic_code . ']]></sage_analytic_code>';
        $xml .= '<sage_number_packages><![CDATA[' . $this->sage_number_packages . ']]></sage_number_packages>';
        $xml .= '<sage_packing><![CDATA[' . $this->sage_packing . ']]></sage_packing>';
        $xml .= '<sage_delivery_mode><![CDATA[' . $this->sage_delivery_mode . ']]></sage_delivery_mode>';
        $xml .= '<sage_payer_account><![CDATA[' . $this->sage_payer_account . ']]></sage_payer_account>';
        $xml .= '<sage_central_buying><![CDATA[' . $this->sage_central_buying . ']]></sage_central_buying>';

        // Le client de la commande
        $xml .= $this->customer->getXML();

        // l'adresse de facturation
        $xml .= $this->invoice_address->getXML('invoice_address');

        // l'adresse de livraison
        $xml .= $this->delivery_address->getXML('delivery_address');

        $xml .= '<custom_fields>';
        if (count($this->custom_fields) > 0) {
            foreach ($this->custom_fields as $custom_field) {
                $xml .= $custom_field->getXML();
            }
        }
        $xml .= '</custom_fields>';

        $xml .= '<payments>';
        if (count($this->payments) > 0) {
            foreach ($this->payments as $payment) {
                $xml .= $payment->getXML();
            }
        }
        $xml .= '</payments>';

        $xml .= '<discounts>';
        if (count($this->discounts) > 0) {
            foreach ($this->discounts as $discount) {
                $xml .= $discount->getXML();
            }
        }
        $xml .= '</discounts>';

        $xml .= '<files>';
        if (count($this->files) > 0) {
            foreach ($this->files as $file) {
                $xml .= $file->getXML();
            }
        }
        $xml .= '</files>';

        $xml .= '<products>';
        if (count($this->products) > 0) {
            foreach ($this->products as $product) {
                $xml .= $product->getXML();
            }
        }
        $xml .= '</products>';

        $xml .= '<product_taxes>';
        if (count($this->productTaxes) > 0) {
            foreach ($this->productTaxes as $productTax) {
                $xml .= $productTax->getXML();
            }
        }
        $xml .= '</product_taxes>';

        $xml .= '<shipping_taxes>';
        if (count($this->shippingTaxes) > 0) {
            foreach ($this->shippingTaxes as $shippingTax) {
                $xml .= $shippingTax->getXML();
            }
        }
        $xml .= '</shipping_taxes>';

        $xml .= '<adjustment_taxes>';
        if (count($this->adjustmentTaxes) > 0) {
            foreach ($this->adjustmentTaxes as $adjustmentTax) {
                $xml .= $adjustmentTax->getXML();
            }
        }
        $xml .= '</adjustment_taxes>';

        $xml .= '</order>';
        return $xml;
    }

    /**
     * Créé un objet CmsOrder à partir du XML des commandes
     *
     * @param \SimpleXMLElement $ordersXml XML des commandes
     * @return CmsOrder
     */
    public static function createFromXML(\SimpleXMLElement $ordersXml)
    {
        $cmsOrder = new CmsOrder();
        if ($ordersXml) {
            $cmsOrder->order_type = (string)$ordersXml->order_type;
            $cmsOrder->shop_key = (string)$ordersXml->shop_key;
            $cmsOrder->order_key = (string)$ordersXml->order_key;
            $cmsOrder->quote_key = (string)$ordersXml->quote_key;
            $cmsOrder->creditnote_key = (string)$ordersXml->creditnote_key;
            $cmsOrder->return_key = (string)$ordersXml->return_key;
            $cmsOrder->cart_key = (string)$ordersXml->cart_key;
            $cmsOrder->zone_key = (string)$ordersXml->zone_key;
            $cmsOrder->country_key = (string)$ordersXml->country_key;
            $cmsOrder->order_date = (string)$ordersXml->order_date;
            $cmsOrder->order_status = (string)$ordersXml->order_status;
            $cmsOrder->order_number = (string)$ordersXml->order_number;
            $cmsOrder->order_reference = (string)$ordersXml->order_reference;
            $cmsOrder->delivery_date = (string)$ordersXml->delivery_date;
            $cmsOrder->invoice_date = (string)$ordersXml->invoice_date;
            $cmsOrder->invoice_number = (string)$ordersXml->invoice_number;
            $cmsOrder->creditnote_date = (string)$ordersXml->creditnote_date;
            $cmsOrder->creditnote_number = (string)$ordersXml->creditnote_number;
            $cmsOrder->return_date = (string)$ordersXml->return_date;
            $cmsOrder->return_number = (string)$ordersXml->return_number;
            $cmsOrder->due_date = (string)$ordersXml->due_date;

            $cmsOrder->payment_name = (string)$ordersXml->payment_name;
            $cmsOrder->currency_key = (string)$ordersXml->currency_key;
            $cmsOrder->currency_rate = (string)$ordersXml->currency_rate;
            $cmsOrder->carrier_key = (string)$ordersXml->carrier_key;
            $cmsOrder->carrier_name = (string)$ordersXml->carrier_name;
            $cmsOrder->shipping_tax_name = (string)$ordersXml->shipping_tax_name;
            $cmsOrder->shipping_tax_rate = (float)$ordersXml->shipping_tax_rate;
            $cmsOrder->shipping_tax_incl = (float)$ordersXml->shipping_tax_incl;
            $cmsOrder->shipping_tax_excl = (float)$ordersXml->shipping_tax_excl;
            $cmsOrder->shipping_tax = (float)$ordersXml->shipping_tax;
            $cmsOrder->shipping_discount_tax_excl = (float)$ordersXml->shipping_discount_tax_excl;
            $cmsOrder->shipping_discount_tax_incl = (float)$ordersXml->shipping_discount_tax_incl;
            $cmsOrder->shipping_final_tax_excl = (float)$ordersXml->shipping_final_tax_excl;
            $cmsOrder->shipping_final_tax_incl = (float)$ordersXml->shipping_final_tax_incl;
            $cmsOrder->shipping_final_tax = (float)$ordersXml->shipping_final_tax;
            $cmsOrder->free_shipping = (string)$ordersXml->free_shipping == '1';
            $cmsOrder->wrapping_tax_name = (string)$ordersXml->wrapping_tax_name;
            $cmsOrder->wrapping_tax_rate = (float)$ordersXml->wrapping_tax_rate;
            $cmsOrder->wrapping_tax_excl = (float)$ordersXml->wrapping_tax_excl;
            $cmsOrder->wrapping_tax_incl = (float)$ordersXml->wrapping_tax_incl;
            $cmsOrder->wrapping_tax = (float)$ordersXml->wrapping_tax;
            $cmsOrder->total_paid = (float)$ordersXml->total_paid;
            $cmsOrder->total_paid_real = (float)$ordersXml->total_paid_real;
            $cmsOrder->total_tax_excl = (float)$ordersXml->total_tax_excl;
            $cmsOrder->total_tax_incl = (float)$ordersXml->total_tax_incl;
            $cmsOrder->total_tax = (float)$ordersXml->total_tax;
            $cmsOrder->total_products_tax_excl = (float)$ordersXml->total_products_tax_excl;
            $cmsOrder->total_products_tax_incl = (float)$ordersXml->total_products_tax_incl;
            $cmsOrder->total_products_tax = (float)$ordersXml->total_products_tax;
            $cmsOrder->total_discounts_tax_excl = (float)$ordersXml->total_discounts_tax_excl;
            $cmsOrder->total_discounts_tax_incl = (float)$ordersXml->total_discounts_tax_incl;
            $cmsOrder->total_discounts_tax = (float)$ordersXml->total_discounts_tax;
            $cmsOrder->create_discount_taxes_included = (string)$ordersXml->create_discount_taxes_included == '1';
            $cmsOrder->calculation_taxes_included = (string)$ordersXml->calculation_taxes_included == '1';
            $cmsOrder->warehouse = (string)$ordersXml->warehouse;
            $cmsOrder->messages = (string)$ordersXml->messages;
            $cmsOrder->document_serial = (string)$ordersXml->document_serial;
            $cmsOrder->custom_reference_1 = (string)$ordersXml->custom_reference_1;
            $cmsOrder->custom_reference_2 = (string)$ordersXml->custom_reference_2;
            $cmsOrder->custom_reference_3 = (string)$ordersXml->custom_reference_3;
            $cmsOrder->custom_reference_4 = (string)$ordersXml->custom_reference_4;
            $cmsOrder->custom_reference_5 = (string)$ordersXml->custom_reference_5;

            $cmsOrder->ebp_colleague_code = (string)$ordersXml->ebp_colleague_code;

            $cmsOrder->ciel_colleague_code = (string)$ordersXml->ciel_colleague_code;
            $cmsOrder->ciel_business_code = (string)$ordersXml->ciel_business_code;

            $cmsOrder->sage_document_status = (string)$ordersXml->sage_document_status;
            $cmsOrder->sage_header1 = (string)$ordersXml->sage_header1;
            $cmsOrder->sage_header2 = (string)$ordersXml->sage_header2;
            $cmsOrder->sage_header3 = (string)$ordersXml->sage_header3;
            $cmsOrder->sage_header4 = (string)$ordersXml->sage_header4;
            $cmsOrder->sage_colleague = (string)$ordersXml->sage_colleague;
            $cmsOrder->sage_analytic_code = (string)$ordersXml->sage_analytic_code;
            $cmsOrder->sage_number_packages = (string)$ordersXml->sage_number_packages;
            $cmsOrder->sage_packing = (string)$ordersXml->sage_packing;
            $cmsOrder->sage_delivery_mode = (string)$ordersXml->sage_delivery_mode;
            $cmsOrder->sage_payer_account = (string)$ordersXml->sage_payer_account;
            $cmsOrder->sage_central_buying = (string)$ordersXml->sage_central_buying;
            if ($ordersXml->customer) {
                $cmsOrder->customer = CmsOrderCustomer::createFromXml($ordersXml->customer);
            }
            if ($ordersXml->invoice_address) {
                $cmsOrder->invoice_address = CmsOrderAddress::createFromXml($ordersXml->invoice_address);
            }
            if ($ordersXml->delivery_address) {
                $cmsOrder->delivery_address = CmsOrderAddress::createFromXml($ordersXml->delivery_address);
            }
            if ($ordersXml->custom_fields) {
                $cmsOrder->custom_fields = array();
                foreach ($ordersXml->custom_fields->custom_field as $custom_field) {
                    $cmsOrder->custom_fields[] = CustomField::createFromXml($custom_field);
                }
            }
            if ($ordersXml->payments) {
                $cmsOrder->payments = array();
                foreach ($ordersXml->payments->payment as $payment) {
                    $cmsOrder->payments[] = CmsOrderPayment::createFromXml($payment);
                }
            }
            if ($ordersXml->discounts) {
                $cmsOrder->discounts = array();
                foreach ($ordersXml->discounts->discont as $discount) {
                    $cmsOrder->discounts[] = CmsOrderDiscount::createFromXml($discount);
                }
            }

            if ($ordersXml->files) {
                $cmsOrder->files = array();
                foreach ($ordersXml->files->file as $file) {
                    $cmsOrder->files[] = CmsOrderFile::createFromXml($file);
                }
            }

            if ($ordersXml->products) {
                $cmsOrder->products = array();
                foreach ($ordersXml->products->product as $product) {
                    $cmsOrder->products[] = CmsOrderProduct::createFromXml($product);
                }
            }
            if ($ordersXml->productTaxes) {
                $cmsOrder->productTaxes = array();
                foreach ($ordersXml->productTaxes->productTax as $productTax) {
                    $cmsOrder->productTaxes[] = CmsOrderProductTax::createFromXml($productTax);
                }
            }
            if ($ordersXml->shippingTaxes) {
                $cmsOrder->shippingTaxes = array();
                foreach ($ordersXml->shippingTaxes->shippingTax as $shippingTax) {
                    $cmsOrder->shippingTaxes[] = CmsOrderShippingTax::createFromXml($shippingTax);
                }
            }
            if ($ordersXml->adjustmentTaxes) {
                $cmsOrder->adjustmentTaxes = array();
                foreach ($ordersXml->adjutmentTaxes->adjustmentTax as $adjustmentTax) {
                    $cmsOrder->adjustmentTaxes[] = CmsOrderAdjustmentTax::createFromXML($adjustmentTax);
                }
            }
        }
        return $cmsOrder;
    }
}
