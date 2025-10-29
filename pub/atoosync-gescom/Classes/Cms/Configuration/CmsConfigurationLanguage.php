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
 * Class CmsConfigurationLanguage
 */
class CmsConfigurationLanguage
{
    /** @var string Le Code de la langue */
    public $code = "";

    /** @var string Le Code Iso de la langue */
    public $isocode = "";

    /** @var string Le Nom de la langue */
    public $name = "";

    /** @var bool Langue par défaut dans le CMS */
    public $default = false;

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<language>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<isocode><![CDATA[' . $this->isocode . ']]></isocode>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        if ($this->default) {
            $xml .= '<default><![CDATA[' . '1' . ']]></default>';
        } else {
            $xml .= '<default><![CDATA[' . '0' . ']]></default>';
        }
        $xml .= '</language>';

        return $xml;
    }

    /**
     * Créé un objet CmsConfigurationLanguage à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationLanguageXml XML de la configuration
     * @return CmsConfigurationLanguage
     */
    public static function createFromXML(\SimpleXMLElement $configurationLanguageXml)
    {
        $cmsConfigurationLanguage = new CmsConfigurationLanguage();
        if ($configurationLanguageXml) {
            $cmsConfigurationLanguage->code = (string)$configurationLanguageXml->code;
            $cmsConfigurationLanguage->isocode = (string)$configurationLanguageXml->isocode;
            $cmsConfigurationLanguage->name = (string)$configurationLanguageXml->name;
            $cmsConfigurationLanguage->default = (string)$configurationLanguageXml->default == '1';
        }
        return $cmsConfigurationLanguage;
    }
}
