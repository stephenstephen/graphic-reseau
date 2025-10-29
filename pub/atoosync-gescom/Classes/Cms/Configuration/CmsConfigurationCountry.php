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
 * Class CmsConfigurationCountry
 */
class CmsConfigurationCountry
{
    /** @var string Le code du pays */
    public $code = "";

    /** @var string Le nom du pays */
    public $name = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<country>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '</country>';

        return $xml;
    }

    /**
     * Créé un objet CmsConfigurationCountry à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationCountryXml XML de la configuration
     * @return CmsConfigurationCountry
     */
    public static function createFromXML(\SimpleXMLElement $configurationCountryXml)
    {
        $cmsConfigurationCountry= new CmsConfigurationCountry();
        if ($configurationCountryXml) {
            $cmsConfigurationCountry->code = (string)$configurationCountryXml->code;
            $cmsConfigurationCountry->name = (string)$configurationCountryXml->name;
        }
        return $cmsConfigurationCountry;
    }
}
