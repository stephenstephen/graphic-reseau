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
 * Class ErpProductLanguage
 */
class ErpProductLanguage
{
    /** @var string Le code de la boutique dans le CMS */
    public $shop_key = "";

    /** @var string Le code de la langue dans le CMS */
    public $language_key = "";

    /** @var string Le nom de l'article */
    public $name = "";

    /** @var string La description de l'article */
    public $description = "";

    /** @var string Le résumé de l'article */
    public $description_short = "";

    /** @var string L'url simplifiée de l'article */
    public $link_rewrite = "";

    /** @var string La meta description de l'article */
    public $meta_description = "";

    /** @var string Les meta mots clés de l'article */
    public $meta_keywords = "";

    /** @var string La balise titre de l'article */
    public $meta_title = "";

    /**
     * Créé un objet ErpProductLanguage à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $productLanguageXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpProductLanguage
     */
    public static function createFromXML($productLanguageXml)
    {
        $erpProductLanguage = new ErpProductLanguage();
        if ($productLanguageXml) {
            $erpProductLanguage->shop_key = (string)$productLanguageXml->shop_key;
            $erpProductLanguage->language_key = (string)$productLanguageXml->language_key;
            $erpProductLanguage->name = (string)$productLanguageXml->name;
            $erpProductLanguage->description = (string)$productLanguageXml->description;
            $erpProductLanguage->description_short = (string)$productLanguageXml->description_short;
            $erpProductLanguage->link_rewrite = (string)$productLanguageXml->link_rewrite;
            $erpProductLanguage->meta_description = (string)$productLanguageXml->meta_description;
            $erpProductLanguage->meta_keywords = (string)$productLanguageXml->meta_keywords;
            $erpProductLanguage->meta_title = (string)$productLanguageXml->meta_title;
        }
        return $erpProductLanguage;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<language>';
        $xml .= '<shop_key><![CDATA[' . $this->shop_key . ']]></shop_key>';
        $xml .= '<language_key><![CDATA[' . $this->language_key . ']]></language_key>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '<description><![CDATA[' . $this->description . ']]></description>';
        $xml .= '<description_short><![CDATA[' . $this->description_short . ']]></description_short>';
        $xml .= '<link_rewrite><![CDATA[' . $this->link_rewrite . ']]></link_rewrite>';
        $xml .= '<meta_description><![CDATA[' . $this->meta_description . ']]></meta_description>';
        $xml .= '<meta_keywords><![CDATA[' . $this->meta_keywords . ']]></meta_keywords>';
        $xml .= '<meta_title><![CDATA[' . $this->meta_title . ']]></meta_title>';
        $xml .= '</language>';
        return $xml;
    }
}
