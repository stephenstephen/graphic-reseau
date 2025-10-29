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

namespace AtooNext\AtooSync\Commons;

/**
 * Class CustomField
 */
class CustomField
{
    /** @var string Le nom du champ personnalisé */
    public $name = "";

    /** @var string La valeur du champ personnalisé */
    public $value = "";

    public function __construct($name, $value)
    {
        $this->name = (string)$name;
        $this->value = (string)$value;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        return '<custom_field name="' . $this->escapeXMLString($this->name) . '" value="' . $this->escapeXMLString($this->value) . '" />';
    }

    private function escapeXMLString($string)
    {
        $tmp = (string)$string;
        $tmp = str_replace('"', "&quot;", $tmp);
        $tmp = str_replace("&", "&amp;", $tmp);
        $tmp = str_replace("<", "&lt;", $tmp);
        $tmp = str_replace(">", "&gt;", $tmp);
        $tmp = str_replace("’", "'", $tmp);
        return $tmp;
    }

    /**
     * Créé un objet CustomField à partir d'un XML
     *
     * @param \SimpleXMLElement $customFieldXml XML de la configuration
     * @return CustomField
     */
    public static function createFromXml(\SimpleXMLElement $customFieldXml)
    {
        return new CustomField($customFieldXml->name, $customFieldXml->value);
    }
}
