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
 * Class ErpCustomerSage100Informations
 */
class ErpCustomerSage100Informations
{
    /** @var string Le compte payeur du client */
    public $payer_account = "";

    /** @var string Le compte client de la centrale d'achat */
    public $central_buyin = "";

    /** @var float Le taux de remise du client */
    public $discount_rate = 0.00;

    /** @var float Le taux d'escompte du client */
    public $cash_discount_rate = 0.00;

    /** @var float Le taux relevé du client */
    public $respite_rate = 0.00;

    /** @var float Le taux R.F.A. du client */
    public $end_year_discount_rate = 0.00;

    /** @var string Le nom du dépot du client */
    public $warehouse = "";

    /** @var string La devise du client */
    public $currency = "";

    /** @var string Le code affaire du client */
    public $business_code = "";

    /** @var string La catégorie comptable du client */
    public $accounting_category = "";

    /** @var integer Le délai de transport du client */
    public $transport_time = 0;

    /** @var integer La priorité de livraison du client */
    public $delivery_priority = 0;

    /** @var boolean Autoriser la livraison partiel */
    public $partial_delivery = false;

    /** @var boolean Etablier une facture par bon de livraison */
    public $one_invoice_per_delivery = "";

    /** @var integer La langue du client (0=défaut, 1=langue 1, 2=langue 2) */
    public $language = 0;

    /** @var string La code APE du client */
    public $ape = "";

    /** @var string Le code EDI du client */
    public $edi_code = "";

    /** @var string La représentant du client */
    public $sales_representative = "";

    /** @var float L'encours du client */
    public $outstanding_allow_amount = 0.00;

    /** @var float L'assurance crédit du client */
    public $credit_insurance = "";

    /** @var float Le type de contrôle de l'encours (0=auto, 1=Selon code risque, 2=Bloqué) */
    public $outstanding = 0;

    /** @var string Le nom du code risque du client */
    public $risk_name = "";

    /** @var string Le nom des condtions de paiements du client */
    public $settlement_model_name = "";

    /** @var string La statistique 1 du client */
    public $statistic_1 = "";

    /** @var string La statistique 2 du client */
    public $statistic_2 = "";

    /** @var string La statistique 3 du client */
    public $statistic_3 = "";

    /** @var string La statistique 4 du client */
    public $statistic_4 = "";

    /** @var string La statistique 5 du client */
    public $statistic_5 = "";

    /** @var string La statistique 6 du client */
    public $statistic_6 = "";

    /** @var string La statistique 7 du client */
    public $statistic_7 = "";

    /** @var string La statistique 8 du client */
    public $statistic_8 = "";

    /** @var string La statistique 9 du client */
    public $statistic_9 = "";

    /** @var string La statistique 10 du client */
    public $statistic_10 = "";

    /** @var float Le solde du client */
    public $account_balance = 0.00;

    /** @var float Le montant total des bons de livraison et de factures du client */
    public $portfolio_BL_FA = 0.00;

    /** @var float Le montant total des bons de commande et de préparations de livraison du client */
    public $portfolio_BC_PL = 0.00;

    /** @var string La date de derniere règlement du client */
    public $portfolio_last_payment_date = '0000-00-00';

    /** @var float Le montant du risque réel du client */
    public $real_risk = 0.00;

    /** @var float Le solde du compte payeur du client */
    public $account_balance_payer = 0.00;

    /** @var float Le montant total des bons de livraison et de factures du compte payeur du client */
    public $portfolio_BL_FA_payer = 0.00;

    /** @var float Le montant total des bons de commande et de préparations de livraison du compte payeur du client */
    public $portfolio_BC_PL_payer = 0.00;

    /** @var string La date de derniere règlement du compte payeur du client */
    public $portfolio_last_payment_date_payer = '0000-00-00';

    /** @var float Le montant du risque réel du compte payeur du client */
    public $real_risk_payer = 0.00;

    /** @var ErpCustomerSage100FamilyDiscount[] Les remises par famille du client */
    public $families_discounts = array();

    /** @var ErpCustomerSage100Term[] Les échéances du client */
    public $customer_terms = array();

    /** @var ErpCustomerSage100Term[] Les échéances du compte payeur du client */
    public $payer_terms = array();

    /**
     * ErpCustomerSage100Informations constructor.
     */
    public function __construct()
    {
        $this->families_discounts = array();
        $this->customer_terms = array();
        $this->payer_account = array();
    }

    /**
     * Créé un objet ErpCustomerSage100Informations à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $customerSage100InformationsXML L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpCustomerSage100Informations
     */
    public static function createFromXML($customerSage100InformationsXML)
    {
        $sage100Informations = new ErpCustomerSage100Informations();
        if ($customerSage100InformationsXML) {
            $sage100Informations->payer_account = (string)$customerSage100InformationsXML->payer_account;
            $sage100Informations->central_buyin = (string)$customerSage100InformationsXML->central_buyin;
            $sage100Informations->discount_rate = (float)$customerSage100InformationsXML->discount_rate;
            $sage100Informations->cash_discount_rate = (float)$customerSage100InformationsXML->cash_discount_rate;
            $sage100Informations->respite_rate = (float)$customerSage100InformationsXML->respite_rate;
            $sage100Informations->end_year_discount_rate = (float)$customerSage100InformationsXML->end_year_discount_rate;

            $sage100Informations->warehouse = (string)$customerSage100InformationsXML->warehouse;
            $sage100Informations->currency = (string)$customerSage100InformationsXML->currency;
            $sage100Informations->ape = (string)$customerSage100InformationsXML->ape;
            $sage100Informations->edi_code = (string)$customerSage100InformationsXML->edi_code;
            $sage100Informations->business_code = (string)$customerSage100InformationsXML->business_code;
            $sage100Informations->accounting_category = (string)$customerSage100InformationsXML->accounting_category;
            $sage100Informations->sales_representative = (string)$customerSage100InformationsXML->sales_representative;

            $sage100Informations->transport_time = (int)$customerSage100InformationsXML->transport_time;
            $sage100Informations->delivery_priority = (string)$customerSage100InformationsXML->delivery_priority;
            $sage100Informations->partial_delivery = (int)$customerSage100InformationsXML->partial_delivery == 1;
            $sage100Informations->one_invoice_per_delivery = (int)$customerSage100InformationsXML->one_invoice_per_delivery == 1;
            $sage100Informations->language = (int)$customerSage100InformationsXML->language;

            $sage100Informations->outstanding_allow_amount = (float)$customerSage100InformationsXML->outstanding_allow_amount;
            $sage100Informations->credit_insurance = (float)$customerSage100InformationsXML->credit_insurance;
            $sage100Informations->outstanding = (int)$customerSage100InformationsXML->outstanding;
            $sage100Informations->risk_name = (string)$customerSage100InformationsXML->risk_name;
            $sage100Informations->settlement_model_name = (string)$customerSage100InformationsXML->settlement_model_name;

            $sage100Informations->statistic_1 = (string)$customerSage100InformationsXML->statistic_1;
            $sage100Informations->statistic_2 = (string)$customerSage100InformationsXML->statistic_2;
            $sage100Informations->statistic_3 = (string)$customerSage100InformationsXML->statistic_3;
            $sage100Informations->statistic_4 = (string)$customerSage100InformationsXML->statistic_4;
            $sage100Informations->statistic_5 = (string)$customerSage100InformationsXML->statistic_5;
            $sage100Informations->statistic_6 = (string)$customerSage100InformationsXML->statistic_6;
            $sage100Informations->statistic_7 = (string)$customerSage100InformationsXML->statistic_7;
            $sage100Informations->statistic_8 = (string)$customerSage100InformationsXML->statistic_8;
            $sage100Informations->statistic_9 = (string)$customerSage100InformationsXML->statistic_9;
            $sage100Informations->statistic_10 = (string)$customerSage100InformationsXML->statistic_10;

            $sage100Informations->account_balance = (float)$customerSage100InformationsXML->account_balance;
            $sage100Informations->portfolio_BL_FA = (float)$customerSage100InformationsXML->portfolio_BL_FA;
            $sage100Informations->portfolio_BC_PL = (float)$customerSage100InformationsXML->portfolio_BC_PL;
            $sage100Informations->real_risk = (float)$customerSage100InformationsXML->real_risk;
            $sage100Informations->portfolio_last_payment_date = (string)$customerSage100InformationsXML->portfolio_last_payment_date;

            $sage100Informations->account_balance_payer = (float)$customerSage100InformationsXML->account_balance_payer;
            $sage100Informations->portfolio_BL_FA_payer = (float)$customerSage100InformationsXML->portfolio_BL_FA_payer;
            $sage100Informations->portfolio_BC_PL_payer = (float)$customerSage100InformationsXML->portfolio_BC_PL_payer;
            $sage100Informations->real_risk_payer = (float)$customerSage100InformationsXML->real_risk_payer;
            $sage100Informations->portfolio_last_payment_date_payer = (string)$customerSage100InformationsXML->portfolio_last_payment_date_payer;

            // les remises par familles du client
            if ($customerSage100InformationsXML->families_discounts) {
                $sage100Informations->families_discounts = array();
                foreach ($customerSage100InformationsXML->families_discounts->family_discount as $family_discount) {
                    $sage100Informations->families_discounts[] = ErpCustomerSage100FamilyDiscount::createFromXML($family_discount);
                }
            }

            // les conditions de paiements du client
            if ($customerSage100InformationsXML->customer_terms) {
                $sage100Informations->customer_terms = array();
                foreach ($customerSage100InformationsXML->customer_terms->term as $term) {
                    $sage100Informations->customer_terms[] = ErpCustomerSage100Term::createFromXML($term);
                }
            }

            // les conditions de paiements du compte payeur du clientclient
            if ($customerSage100InformationsXML->payer_terms) {
                $sage100Informations->payer_terms = array();
                foreach ($customerSage100InformationsXML->payer_terms->term as $term) {
                    $sage100Informations->payer_terms[] = ErpCustomerSage100Term::createFromXML($term);
                }
            }
        }
        return $sage100Informations;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<sage100_informations>';

        $xml .= '<payer_account><![CDATA[' . $this->payer_account . ']]></payer_account>';
        $xml .= '<central_buyin><![CDATA[' . $this->central_buyin . ']]></central_buyin>';
        $xml .= '<discount_rate><![CDATA[' . $this->discount_rate . ']]></discount_rate>';
        $xml .= '<cash_discount_rate><![CDATA[' . $this->cash_discount_rate . ']]></cash_discount_rate>';
        $xml .= '<respite_rate><![CDATA[' . $this->respite_rate . ']]></respite_rate>';
        $xml .= '<end_year_discount_rate><![CDATA[' . $this->end_year_discount_rate . ']]></end_year_discount_rate>';
        $xml .= '<warehouse><![CDATA[' . $this->warehouse . ']]></warehouse>';
        $xml .= '<currency><![CDATA[' . $this->currency . ']]></currency>';
        $xml .= '<ape><![CDATA[' . $this->ape . ']]></ape>';
        $xml .= '<edi_code><![CDATA[' . $this->edi_code . ']]></edi_code>';
        $xml .= '<business_code><![CDATA[' . $this->business_code . ']]></business_code>';
        $xml .= '<accounting_category><![CDATA[' . $this->accounting_category . ']]></accounting_category>';
        $xml .= '<sales_representative><![CDATA[' . $this->sales_representative . ']]></sales_representative>';
        $xml .= '<transport_time><![CDATA[' . $this->transport_time . ']]></transport_time>';
        $xml .= '<delivery_priority><![CDATA[' . $this->delivery_priority . ']]></delivery_priority>';
        if ($this->partial_delivery) {
            $xml .= '<partial_delivery><![CDATA[' . '1' . ']]></partial_delivery>';
        } else {
            $xml .= '<partial_delivery><![CDATA[' . '0' . ']]></partial_delivery>';
        }
        if ($this->one_invoice_per_delivery) {
            $xml .= '<one_invoice_per_delivery><![CDATA[' . '1' . ']]></one_invoice_per_delivery>';
        } else {
            $xml .= '<one_invoice_per_delivery><![CDATA[' . '0' . ']]></one_invoice_per_delivery>';
        }
        $xml .= '<language><![CDATA[' . $this->language . ']]></language>';
        $xml .= '<outstanding_allow_amount><![CDATA[' . $this->outstanding_allow_amount . ']]></outstanding_allow_amount>';
        $xml .= '<credit_insurance><![CDATA[' . $this->credit_insurance . ']]></credit_insurance>';
        $xml .= '<outstanding><![CDATA[' . $this->outstanding . ']]></outstanding>';
        $xml .= '<risk_name><![CDATA[' . $this->risk_name . ']]></risk_name>';
        $xml .= '<settlement_model_name><![CDATA[' . $this->settlement_model_name . ']]></settlement_model_name>';
        $xml .= '<statistic_1><![CDATA[' . $this->statistic_1 . ']]></statistic_1>';
        $xml .= '<statistic_2><![CDATA[' . $this->statistic_2 . ']]></statistic_2>';
        $xml .= '<statistic_3><![CDATA[' . $this->statistic_3 . ']]></statistic_3>';
        $xml .= '<statistic_4><![CDATA[' . $this->statistic_4 . ']]></statistic_4>';
        $xml .= '<statistic_5><![CDATA[' . $this->statistic_5 . ']]></statistic_5>';
        $xml .= '<statistic_6><![CDATA[' . $this->statistic_6 . ']]></statistic_6>';
        $xml .= '<statistic_7><![CDATA[' . $this->statistic_7 . ']]></statistic_7>';
        $xml .= '<statistic_8><![CDATA[' . $this->statistic_8 . ']]></statistic_8>';
        $xml .= '<statistic_9><![CDATA[' . $this->statistic_9 . ']]></statistic_9>';
        $xml .= '<statistic_10><![CDATA[' . $this->statistic_10 . ']]></statistic_10>';
        $xml .= '<account_balance><![CDATA[' . $this->account_balance . ']]></account_balance>';
        $xml .= '<portfolio_BL_FA><![CDATA[' . $this->portfolio_BL_FA . ']]></portfolio_BL_FA>';
        $xml .= '<portfolio_BC_PL><![CDATA[' . $this->portfolio_BC_PL . ']]></portfolio_BC_PL>';
        $xml .= '<real_risk><![CDATA[' . $this->real_risk . ']]></real_risk>';
        $xml .= '<portfolio_last_payment_date><![CDATA[' . $this->portfolio_last_payment_date . ']]></portfolio_last_payment_date>';
        $xml .= '<account_balance_payer><![CDATA[' . $this->account_balance_payer . ']]></account_balance_payer>';
        $xml .= '<portfolio_BL_FA_payer><![CDATA[' . $this->portfolio_BL_FA_payer . ']]></portfolio_BL_FA_payer>';
        $xml .= '<portfolio_BC_PL_payer><![CDATA[' . $this->portfolio_BC_PL_payer . ']]></portfolio_BC_PL_payer>';
        $xml .= '<real_risk_payer><![CDATA[' . $this->real_risk_payer . ']]></real_risk_payer>';
        $xml .= '<portfolio_last_payment_date_payer><![CDATA[' . $this->portfolio_last_payment_date_payer . ']]></portfolio_last_payment_date_payer>';

        $xml .= '<families_discounts>';
        if (count($this->families_discounts) > 0) {
            foreach ($this->families_discounts as $families_discount) {
                $xml .= $families_discount->getXML();
            }
        }
        $xml .= '</families_discounts>';

        $xml .= '<customer_terms>';
        if (count($this->customer_terms) > 0) {
            foreach ($this->customer_terms as $customer_term) {
                $xml .= $customer_term->getXML();
            }
        }
        $xml .= '</customer_terms>';

        $xml .= '<payer_terms>';
        if (count($this->payer_terms) > 0) {
            foreach ($this->payer_terms as $payer_term) {
                $xml .= $payer_term->getXML();
            }
        }
        $xml .= '</payer_terms>';


        $xml .= '</sage100_informations>';
        return $xml;
    }
}
