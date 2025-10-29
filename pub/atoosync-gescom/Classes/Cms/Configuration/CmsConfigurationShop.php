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
 * Class CmsConfigurationShop
 */
class CmsConfigurationShop
{
    /** @var string Le code de boutique */
    public $code = "";

    /** @var string Le nom de la boutique */
    public $name = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<shop>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '</shop>';

        return $xml;
    }

    /**
     * Créé un objet CmsConfigurationShop à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationShopXml XML de la configuration
     * @return CmsConfigurationShop
     */
    public static function createFromXML(\SimpleXMLElement $configurationShopXml)
    {
        $cmsConfigurationShop = new CmsConfigurationShop();
        if ($configurationShopXml) {
            $cmsConfigurationShop->code = (string)$configurationShopXml->code;
            $cmsConfigurationShop->name = (string)$configurationShopXml->name;
        }
        return $cmsConfigurationShop;
    }
}
