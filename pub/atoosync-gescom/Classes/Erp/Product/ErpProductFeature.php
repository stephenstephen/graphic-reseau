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

namespace AtooNext\AtooSync\Erp\Product;

/**
 * Class ErpProductFeature
 */
class ErpProductFeature
{
    /** @var string La clé de la caractéristique */
    public $feature_key = "";

    /** @var string La valeur de la caractéristique */
    public $value = "";

    public function __construct($feature_key, $value)
    {
        $this->feature_key = (string)$feature_key;
        $this->value = (string)$value;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<feature>';
        $xml .= '<feature_key><![CDATA[' . $this->feature_key . ']]></feature_key>';
        $xml .= '<value><![CDATA[' . $this->value . ']]></value>';
        $xml .= '</feature>';
        return $xml;
    }
}
