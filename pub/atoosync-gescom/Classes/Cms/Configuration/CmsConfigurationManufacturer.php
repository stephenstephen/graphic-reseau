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
 * Class CmsConfigurationManufacturer
 */
class CmsConfigurationManufacturer
{
    /** @var string Le code de fabricant */
    public $code = "";

    /** @var string Le nom du fabricant */
    public $name = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<manufacturer>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '</manufacturer>';

        return $xml;
    }


    /**
     * Créé un objet CmsConfigurationManuFacturer à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationManufacturerXml XML de la configuration
     * @return CmsConfigurationManufacturer
     */
    public static function createFromXML(\SimpleXMLElement $configurationManufacturerXml)
    {
        $cmsConfigurationManufacturer= new CmsConfigurationManufacturer();
        if ($configurationManufacturerXml) {
            $cmsConfigurationManufacturer->code = (string)$configurationManufacturerXml->code;
            $cmsConfigurationManufacturer->name = (string)$configurationManufacturerXml->name;
        }
        return $cmsConfigurationManufacturer;
    }
}
