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
 * Class CmsOrderPayment
 */
class CmsOrderPayment
{
    /** @var string  Le code unique du mode de paiement */
    public $payment_key = "";

    /** @var string  Le nom du mode de paiement */
    public $method = "";

    /** @var float   Le mmontant du paiement */
    public $amount = 0.00;

    /** @var float Le montant des frais du paiement */
    public $fee = 0.00;

    /** @var string La date du paiement */
    public $date = "0000-00-00";

    /** @var string La devise du paiement */
    public $currency_key = "";

    /** @var float Le taux de conversion de la devise du paiement */
    public $currency_rate = 1.00;

    /** @var string Le code de la transaction du paiement */
    public $transaction_code = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<payment>';
        $xml .= '<payment_key><![CDATA[' . $this->payment_key . ']]></payment_key>';
        $xml .= '<method><![CDATA[' . $this->method . ']]></method>';
        $xml .= '<amount><![CDATA[' . $this->amount . ']]></amount>';
        $xml .= '<fee><![CDATA[' . $this->fee . ']]></fee>';
        $xml .= '<date><![CDATA[' . $this->date . ']]></date>';
        $xml .= '<currency_key><![CDATA[' . $this->currency_key . ']]></currency_key>';
        $xml .= '<currency_rate><![CDATA[' . $this->currency_rate . ']]></currency_rate>';
        $xml .= '<transaction_code><![CDATA[' . $this->transaction_code . ']]></transaction_code>';
        $xml .= '</payment>';

        return $xml;
    }

    /**
     * Créé un objet CmsOrderPayment à partir du XML des commandes
     *
     * @param \SimpleXMLElement $orderPaymentXml XML de la configuration
     * @return CmsOrderPayment
     */
    public static function createFromXml(\SimpleXMLElement $orderPaymentXml)
    {
        $cmsOrderPayment = new CmsOrderPayment();
        if ($orderPaymentXml) {
            $cmsOrderPayment->payment_key = (string)$orderPaymentXml->payment_key;
            $cmsOrderPayment->method = (string)$orderPaymentXml->method;
            $cmsOrderPayment->amount = (float)$orderPaymentXml->amount;
            $cmsOrderPayment->fee = (float)$orderPaymentXml->fee;
            $cmsOrderPayment->date = (string)$orderPaymentXml->date;
            $cmsOrderPayment->currency_key = (string)$orderPaymentXml->currency_key;
            $cmsOrderPayment->currency_rate = (float)$orderPaymentXml->currency_rate;
            $cmsOrderPayment->transaction_code = (string)$orderPaymentXml->transaction_code;
        }
        return $cmsOrderPayment;
    }
}
