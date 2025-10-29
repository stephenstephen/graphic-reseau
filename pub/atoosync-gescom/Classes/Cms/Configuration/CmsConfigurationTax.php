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

namespace AtooNext\AtooSync\Cms\Configuration;

/**
 * Class CmsConfigurationTax
 */
class CmsConfigurationTax
{
    /** @var string Le code de la taxe */
    public $code = "";

    /** @var string Le nom du taxe */
    public $name = "";

    /** @var float Le taux de la taxe */
    public $rate = 0.00;

    /** @var bool Taxe active */
    public $active = false;

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<tax>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '<rate><![CDATA[' . $this->rate . ']]></rate>';
        if ($this->active) {
            $xml .= '<active><![CDATA[' . '1' . ']]></active>';
        } else {
            $xml .= '<active><![CDATA[' . '0' . ']]></active>';
        }
        $xml .= '</tax>';

        return $xml;
    }


    /**
     * Créé un objet CmsConfigurationTax à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationTaxXml XML de la configuration
     * @return CmsConfigurationTax
     */
    public static function createFromXML(\SimpleXMLElement $configurationTaxXml)
    {
        $cmsConfigurationTax = new CmsConfigurationTax();
        if ($configurationTaxXml) {
            $cmsConfigurationTax->code = (string)$configurationTaxXml->code;
            $cmsConfigurationTax->name = (string)$configurationTaxXml->name;
            $cmsConfigurationTax->rate = (float)$configurationTaxXml->rate;
            $cmsConfigurationTax->active = (string)$configurationTaxXml->active=='1';
        }
        return $cmsConfigurationTax;
    }
}
