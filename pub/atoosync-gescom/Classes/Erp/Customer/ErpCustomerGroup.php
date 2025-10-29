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
 * Class ErpCustomerGroup
 */
class ErpCustomerGroup
{
    /** @var integer La clé du groupe de client dans Sage ODBC */
    public $atoosync_odbc_key = 0;

    /** @var string La clé du groupe de client dans l'ERP */
    public $atoosync_key = "";

    /** @var string Le nom du groupe de client */
    public $name = "";

    /** @var float La remise du groupe de client */
    public $reduction = 0.00;

    /** @var boolean Afficher les prix */
    public $show_prices = true;

    /** @var boolean Afficher les prix avec ou sans TVA */
    public $show_prices_tax_incl = false;

    /**
     * Créé un objet ErpCustomerGroup à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $customerGroupXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpCustomerGroup
     */
    public static function createFromXML($customerGroupXml)
    {
        $ErpCustomerGroup = new ErpCustomerGroup();
        if ($customerGroupXml) {
            $ErpCustomerGroup->atoosync_odbc_key = (string)$customerGroupXml->atoosync_odbc_key;
            $ErpCustomerGroup->atoosync_key = (string)$customerGroupXml->atoosync_key;
            $ErpCustomerGroup->name = (string)$customerGroupXml->name;
            $ErpCustomerGroup->reduction = (float)$customerGroupXml->reduction;
            $ErpCustomerGroup->show_prices = ((int)$customerGroupXml->show_prices == 1);
            $ErpCustomerGroup->show_prices_tax_incl = ((int)$customerGroupXml->show_prices_tax_incl == 1);
        }
        return $ErpCustomerGroup;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<erp_customer_group>';
        $xml .= '<atoosync_odbc_key><![CDATA[' . $this->atoosync_odbc_key . ']]></atoosync_odbc_key>';
        $xml .= '<atoosync_key><![CDATA[' . $this->atoosync_key . ']]></atoosync_key>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '<reduction><![CDATA[' . $this->reduction . ']]></reduction>';
        if ($this->show_prices) {
            $xml .= '<show_prices><![CDATA[' . '1' . ']]></show_prices>';
        } else {
            $xml .= '<show_prices><![CDATA[' . '0' . ']]></show_prices>';
        }
        if ($this->show_prices_tax_incl) {
            $xml .= '<show_prices_tax_incl><![CDATA[' . '1' . ']]></show_prices_tax_incl>';
        } else {
            $xml .= '<show_prices_tax_incl><![CDATA[' . '0' . ']]></show_prices_tax_incl>';
        }

        $xml .= '</erp_customer_group>';
        return $xml;
    }
}
