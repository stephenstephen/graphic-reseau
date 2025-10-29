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
 * Class CmsConfigurationQuoteStatus
 */
class CmsConfigurationQuoteStatus
{
    /** @var string Le code du statut du devis */
    public $code = "";

    /** @var string Le nom du statut du devis */
    public $name = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<quote_status>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '</quote_status>';

        return $xml;
    }


    /**
     * Créé un objet CmsConfigurationQuoteStatus à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationQuoteStatusXml XML de la configuration
     * @return CmsConfigurationQuoteStatus
     */
    public static function createFromXML(\SimpleXMLElement $configurationQuoteStatusXml)
    {
        $cmsConfigurationQuoteStatus = new CmsConfigurationQuoteStatus();
        if ($configurationQuoteStatusXml) {
            $cmsConfigurationQuoteStatus->code = (string)$configurationQuoteStatusXml->code;
            $cmsConfigurationQuoteStatus->name = (string)$configurationQuoteStatusXml->name;
        }
        return $cmsConfigurationQuoteStatus;
    }
}
