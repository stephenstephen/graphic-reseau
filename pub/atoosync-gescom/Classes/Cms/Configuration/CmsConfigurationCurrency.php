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
 * Class CmsConfigurationCurrency
 */
class CmsConfigurationCurrency
{
    /** @var string Le code de la devise */
    public $code = "";

    /** @var string Le nom de la devise */
    public $name = "";

    /** @var bool Devise par défaut dans le CMS */
    public $default = false;

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<currency>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        if ($this->default) {
            $xml .= '<default><![CDATA[' . '1' . ']]></default>';
        } else {
            $xml .= '<default><![CDATA[' . '0' . ']]></default>';
        }
        $xml .= '</currency>';

        return $xml;
    }

    /**
     * Créé un objet CmsConfigurationCurrency à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationCurrencyXml XML de la configuration
     * @return CmsConfigurationCurrency
     */
    public static function createFromXML(\SimpleXMLElement $configurationCurrencyXml)
    {
        $cmsConfigurationCurrency= new CmsConfigurationCurrency();
        if ($configurationCurrencyXml) {
            $cmsConfigurationCurrency->code = (string)$configurationCurrencyXml->code;
            $cmsConfigurationCurrency->name = (string)$configurationCurrencyXml->name;
            $cmsConfigurationCurrency->default = (string)$configurationCurrencyXml->default=='1';
        }
        return $cmsConfigurationCurrency;
    }
}
