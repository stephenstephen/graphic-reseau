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
 * Class CmsConfigurationZone
 */
class CmsConfigurationZone
{
    /** @var string Le code de la zone */
    public $code = "";
    /** @var string Le nom de la zone */
    public $name = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<zone>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '</zone>';

        return $xml;
    }


    /**
     * Créé un objet CmsConfigurationZone à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationZoneXml XML de la configuration
     * @return CmsConfigurationZone
     */
    public static function createFromXML(\SimpleXMLElement $configurationZoneXml)
    {
        $cmsConfigurationZone = new CmsConfigurationZone();
        if ($configurationZoneXml) {
            $cmsConfigurationZone->code = (string)$configurationZoneXml->code;
            $cmsConfigurationZone->name = (string)$configurationZoneXml->name;
        }
        return $cmsConfigurationZone;
    }
}
