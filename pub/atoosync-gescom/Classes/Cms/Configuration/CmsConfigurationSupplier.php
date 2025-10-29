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
 * Class CmsConfigurationSupplier
 */
class CmsConfigurationSupplier
{
    /** @var string Le code de fournisseur */
    public $code = "";

    /** @var string Le nom du fournisseur */
    public $name = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<supplier>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '</supplier>';

        return $xml;
    }


    /**
     * Créé un objet CmsConfigurationSupplier à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationSuplierXml XML de la configuration
     * @return CmsConfigurationSupplier
     */
    public static function createFromXML(\SimpleXMLElement $configurationSuplierXml)
    {
        $cmsConfigurationSupplier = new CmsConfigurationSupplier();
        if ($configurationSuplierXml) {
            $cmsConfigurationSupplier->code = (string)$configurationSuplierXml->code;
            $cmsConfigurationSupplier->name = (string)$configurationSuplierXml->name;
        }
        return $cmsConfigurationSupplier;
    }
}
