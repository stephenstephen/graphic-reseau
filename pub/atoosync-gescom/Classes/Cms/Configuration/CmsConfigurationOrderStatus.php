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
 * Class CmsConfigurationOrderStatus
 */
class CmsConfigurationOrderStatus
{
    /** @var string Le code du statut de la commande */
    public $code = "";

    /** @var string Le nom du statut de la commande */
    public $name = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<order_status>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '</order_status>';

        return $xml;
    }


    /**
     * Créé un objet CmsConfigurationOrderStatus à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationOrderStatusXml XML de la configuration
     * @return CmsConfigurationOrderStatus
     */
    public static function createFromXML(\SimpleXMLElement $configurationOrderStatusXml)
    {
        $cmsConfigurationOrderStatus = new CmsConfigurationOrderStatus();
        if ($configurationOrderStatusXml) {
            $cmsConfigurationOrderStatus->code = (string)$configurationOrderStatusXml->code;
            $cmsConfigurationOrderStatus->name = (string)$configurationOrderStatusXml->name;
        }
        return $cmsConfigurationOrderStatus;
    }
}
