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
 * Class CmsConfigurationTaxRule
 */
class CmsConfigurationTaxRule
{
    /** @var string Le code de taxe des articles */
    public $code = "";

    /** @var string Le nom de la taxe des articles */
    public $name = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<tax_rule>';
        $xml .= '<code><![CDATA[' . $this->code . ']]></code>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '</tax_rule>';

        return $xml;
    }


    /**
     * Créé un objet CmsConfigurationTaxRule à partir du XML de la configuration
     *
     * @param \SimpleXMLElement $configurationTaxRuleXml XML de la configuration
     * @return CmsConfigurationTaxRule
     */
    public static function createFromXML(\SimpleXMLElement $configurationTaxRuleXml)
    {
        $cmsConfigurationTaxRule = new CmsConfigurationTaxRule();
        if ($configurationTaxRuleXml) {
            $cmsConfigurationTaxRule->code = (string)$configurationTaxRuleXml->code;
            $cmsConfigurationTaxRule->name = (string)$configurationTaxRuleXml->name;
        }
        return $cmsConfigurationTaxRule;
    }
}
