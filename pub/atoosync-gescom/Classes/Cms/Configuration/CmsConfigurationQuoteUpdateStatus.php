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
 * Class CmsConfigurationQuoteUpdateStatus
 */
class CmsConfigurationQuoteUpdateStatus
{
    /** @var string Le code du statut du devis pour la mise à jour */
    public $code = "";

    /** @var string Le nom du statut du devis pour la mise à jour */
    public $name = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<quote_update_status>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '</quote_update_status>';

        return $xml;
    }


    /**
     * Créé un objet CmsConfigurationQuoteUpdateStatus à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationQuoteUpdateStatusXml XML de la configuration
     * @return CmsConfigurationQuoteUpdateStatus
     */
    public static function createFromXML(\SimpleXMLElement $configurationQuoteUpdateStatusXml)
    {
        $cmsConfigurationQuoteUpdateStatus = new CmsConfigurationQuoteUpdateStatus();
        if ($configurationQuoteUpdateStatusXml) {
            $cmsConfigurationQuoteUpdateStatus->code = (string)$configurationQuoteUpdateStatusXml->code;
            $cmsConfigurationQuoteUpdateStatus->name = (string)$configurationQuoteUpdateStatusXml->name;
        }
        return $cmsConfigurationQuoteUpdateStatus;
    }
}
