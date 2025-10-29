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
 * Class ErpProductCategory
 */
class ErpProductCategory
{
    /** @var string La clé de la catégorie dans Sage ODBC */
    public $atoosync_odbc_key = "";

    /** @var string La clé de la catégorie dans l'ERP */
    public $atoosync_key = "";

    /** @var string La clé de la catégorie parente dans l'ERP */
    public $parent_atoosync_key = "";

    /** @var string Le nom de la catégorie */
    public $name = "";

    /** @var string La description de la catégorie */
    public $description = "";

    /** @var string L'url simplifiée de la catégorie */
    public $link_rewrite = "";

    /** @var string Les meta keywords de la catégorie */
    public $meta_keywords = "";

    /** @var string La meta description de la catégorie */
    public $meta_description = "";

    /** @var boolean Catégorie active */
    public $active = true;


    /**
     * Créé un objet ErpProductCategory à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $productCategoryXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpProductCategory
     */
    public static function createFromXML($productCategoryXml)
    {
        $ErpProductCategory = new ErpProductCategory();
        if ($productCategoryXml) {
            $ErpProductCategory->atoosync_odbc_key = (string)$productCategoryXml->atoosync_odbc_key;
            $ErpProductCategory->atoosync_key = (string)$productCategoryXml->atoosync_key;
            $ErpProductCategory->parent_atoosync_key = (string)$productCategoryXml->parent_atoosync_key;
            $ErpProductCategory->name = (string)$productCategoryXml->name;
            $ErpProductCategory->description = (string)$productCategoryXml->description;
            $ErpProductCategory->link_rewrite = (string)$productCategoryXml->link_rewrite;
            $ErpProductCategory->meta_keywords = (string)$productCategoryXml->meta_keywords;
            $ErpProductCategory->meta_description = (string)$productCategoryXml->meta_description;
            $ErpProductCategory->active = ((int)$productCategoryXml->active == 1);
        }
        return $ErpProductCategory;
    }


    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<categories>';
        $xml .= '<category>';
        $xml .= '<atoosync_odbc_key><![CDATA[' . $this->atoosync_odbc_key . ']]></atoosync_odbc_key>';
        $xml .= '<atoosync_key><![CDATA[' . $this->atoosync_key . ']]></atoosync_key>';
        $xml .= '<parent_atoosync_key><![CDATA[' . $this->parent_atoosync_key . ']]></parent_atoosync_key>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '<description><![CDATA[' . $this->description . ']]></description>';
        $xml .= '<link_rewrite><![CDATA[' . $this->link_rewrite . ']]></link_rewrite>';
        $xml .= '<meta_keywords><![CDATA[' . $this->meta_keywords . ']]></meta_keywords>';
        $xml .= '<meta_description><![CDATA[' . $this->meta_description . ']]></meta_description>';
        if ($this->active) {
            $xml .= '<active><![CDATA[' . '1' . ']]></active>';
        } else {
            $xml .= '<active><![CDATA[' . '0' . ']]></active>';
        }
        $xml .= '</category>';
        $xml .= '</categories>';
        return $xml;
    }
}
