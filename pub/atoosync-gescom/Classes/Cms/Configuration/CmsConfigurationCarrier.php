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
 * Class CmsConfigurationCarrier
 */
class CmsConfigurationCarrier
{
    /** @var string Le code du transporteur */
    public $code = "";

    /** @var string Le nom du transporteur */
    public $name = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<carrier>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '</carrier>';

        return $xml;
    }

    /**
     * Créé un objet CmsConfigurationCarrier à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationCarrierXml XML de la configuration
     * @return CmsConfigurationCarrier
     */
    public static function createFromXML(\SimpleXMLElement $configurationCarrierXml)
    {
        $cmsConfigurationCarrier= new CmsConfigurationCarrier();
        if ($configurationCarrierXml) {
            $cmsConfigurationCarrier->code = (string)$configurationCarrierXml->code;
            $cmsConfigurationCarrier->name = (string)$configurationCarrierXml->name;
        }
        return $cmsConfigurationCarrier;
    }
}
