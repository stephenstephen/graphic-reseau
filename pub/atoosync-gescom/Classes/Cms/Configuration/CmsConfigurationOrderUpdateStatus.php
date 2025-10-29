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
 * Class CmsConfigurationOrderUpdateStatus
 */
class CmsConfigurationOrderUpdateStatus
{
    /** @var string Le code du statut de la commande pour la mise à jour */
    public $code = "";

    /** @var string Le nom du statut de la commande pour la mise à jour */
    public $name = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<order_update_status>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '</order_update_status>';

        return $xml;
    }


    /**
     * Créé un objet CmsConfigurationOrderUpdateStatus à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationOrderUpdateStatusXml XML de la configuration
     * @return CmsConfigurationOrderUpdateStatus
     */
    public static function createFromXML(\SimpleXMLElement $configurationOrderUpdateStatusXml)
    {
        $cmsConfigurationOrderUpdateStatus= new CmsConfigurationOrderUpdateStatus();
        if ($configurationOrderUpdateStatusXml) {
            $cmsConfigurationOrderUpdateStatus->code = (string)$configurationOrderUpdateStatusXml->code;
            $cmsConfigurationOrderUpdateStatus->name = (string)$configurationOrderUpdateStatusXml->name;
        }
        return $cmsConfigurationOrderUpdateStatus;
    }
}
