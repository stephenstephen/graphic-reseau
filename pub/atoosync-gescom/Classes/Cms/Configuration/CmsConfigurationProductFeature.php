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
 * Class CmsConfigurationProductFeature
 */
class CmsConfigurationProductFeature
{
    /** @var string Le code de la caractéristique de l'article */
    public $code = "";

    /** @var string Le nom de la caractéristique de l'article */
    public $name = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<product_feature>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '</product_feature>';

        return $xml;
    }


    /**
     * Créé un objet CmsConfigurationProductFeature à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationProductFeatureXml XML de la configuration
     * @return CmsConfigurationProductFeature
     */
    public static function createFromXML(\SimpleXMLElement $configurationProductFeatureXml)
    {
        $cmsConfigurationProductFeature = new CmsConfigurationProductFeature();
        if ($configurationProductFeatureXml) {
            $cmsConfigurationProductFeature->code = (string)$configurationProductFeatureXml->code;
            $cmsConfigurationProductFeature->name = (string)$configurationProductFeatureXml->name;
        }
        return $cmsConfigurationProductFeature;
    }
}
