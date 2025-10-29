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
 * Class CmsConfigurationPayment
 */
class CmsConfigurationPayment
{
    /** @var string Le nom du mode de paiement */
    public $name = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<payment>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '</payment>';

        return $xml;
    }

    /**
     * Créé un objet CmsConfigurationPayment à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationPaymentXml XML de la configuration
     * @return CmsConfigurationPayment
     */
    public static function createFromXML(\SimpleXMLElement $configurationPaymentXml)
    {
        $cmsConfigurationPayment = new CmsConfigurationPayment();
        if ($configurationPaymentXml) {
            $cmsConfigurationPayment->name=(string) $configurationPaymentXml->name;
        }
        return $cmsConfigurationPayment;
    }
}
