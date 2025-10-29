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

use AtooNext\AtooSync\Commons\CustomField;

/**
 * Représente un objet pour la mise à jour des prix d'un article dans le CMS depuis l'ERP
 */
class ErpProductPrice
{
    /** @var string La référence unique de l'article dans l'ERP */
    public $reference = "";

    /** @var string La correspondance de clé de taxe de l'article dans le CMS */
    public $tax_key = "";

    /** @var string La correspondance de clé de taxe de l'article dans le CMS (Utiliser tax_key à la place) */
    public $id_tax_rules_group = "";

    /** @var float Le montant de l'ecotaxe de l'article */
    public $ecotax = 0.00;

    /**
     * @var float Le prix de vente HT de l'article
     * @deprecated Utiliser price_tax_exclude à la place
     */
    public $price = 0.00;

    /** @var float Le prix de vente régulier HT de l'article */
    public $regular_price_tax_exclude = 0.00;

    /** @var float Le prix de vente régulier TTC de l'article */
    public $regular_price_tax_include = 0.00;

    /** @var float Le prix de vente HT de l'article */
    public $price_tax_exclude = 0.00;

    /** @var float Le prix de vente TTC de l'article */
    public $price_tax_include = 0.00;

    /** @var float Le montant de la TVA de l'article */
    public $price_tax = 0.00;

    /** @var float Le taux de TVA de l'article */
    public $tax_rate = 0.00;

    /** @var float Le prix d'achat de l'article */
    public $wholesale_price = 0.00;

    /** @var CustomField[] Les champs personnalisés de l'article dans l'ERP */
    public $customFields = array();

    /** @var ErpProductVariation[] Les variations de l'article */
    public $variations = array();

    /** @var ErpProductPackaging[] Les conditionnements de l'article dans l'ERP */
    public $packagings = array();

    /** @var ErpProductFeature[] Les caractéristiques de l'article configuré dans Atoo-Sync */
    public $features = array();

    /** @var ErpProductSpecificPrice[] Les prix spécifiques de l'article dans l'ERP */
    public $specificPrices = array();

    /**
     * ATSCProductStock constructor.
     */
    public function __construct()
    {
        $this->customFields = array();
        $this->variations = array();
        $this->packagings = array();
        $this->features = array();
        $this->specificPrices = array();
    }

    /**
     * Créé un objet ErpProductPrice à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $productPriceXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpProductPrice
     */
    public static function createFromXML($productPriceXml)
    {
        $ErpProductPrice = new ErpProductPrice();
        if ($productPriceXml) {
            $ErpProductPrice->reference = (string)$productPriceXml->reference;
            $ErpProductPrice->tax_key = (string)$productPriceXml->tax_key;
            $ErpProductPrice->id_tax_rules_group = (string)$productPriceXml->id_tax_rules_group;
            $ErpProductPrice->ecotax = (float)$productPriceXml->ecotax;
            $ErpProductPrice->price = (float)$productPriceXml->price_tax_exclude;
            $ErpProductPrice->regular_price_tax_exclude = (float)$productPriceXml->regular_price_tax_exclude;
            $ErpProductPrice->regular_price_tax_include = (float)$productPriceXml->regular_price_tax_include;
            $ErpProductPrice->price_tax_exclude = (float)$productPriceXml->price_tax_exclude;
            $ErpProductPrice->price_tax_include = (float)$productPriceXml->price_tax_include;
            $ErpProductPrice->price_tax = (float)$productPriceXml->price_tax;
            $ErpProductPrice->tax_rate = (float)$productPriceXml->tax_rate;
            $ErpProductPrice->wholesale_price = (float)$productPriceXml->wholesale_price;

            // les prix spécifiques de l'article
            if ($productPriceXml->specific_prices) {
                $ErpProductPrice->specificPrices = array();

                foreach ($productPriceXml->specific_prices->specific_price as $specific_price) {
                    $ErpProductPrice->specificPrices[] = ErpProductSpecificPrice::createFromXML($specific_price);
                }
            }

            // les variations de l'article
            if ($productPriceXml->variations) {
                $ErpProductPrice->variations = array();

                foreach ($productPriceXml->variations->variation as $variation) {
                    $ErpProductPrice->variations[] = ErpProductVariation::createFromXML($variation);
                }
            }

            // les conditionnements de l'article
            if ($productPriceXml->packagings) {
                $ErpProductPrice->packagings = array();

                foreach ($productPriceXml->packagings->packaging as $packaging) {
                    $ErpProductPrice->packagings[] = ErpProductPackaging::createFromXML($packaging);
                }
            }

            // les champs custom de l'article
            if ($productPriceXml->custom_fields) {
                $ErpProductPrice->customFields = array();

                foreach ($productPriceXml->custom_fields->custom_field as $custom_field) {
                    $ErpProductPrice->customFields[] = new CustomField((string)$custom_field->name, (string)$custom_field->value);
                }
            }

            // les caractéristiques de l'article
            if ($productPriceXml->features) {
                $ErpProductPrice->features = array();

                foreach ($productPriceXml->features->feature as $feature) {
                    $ErpProductPrice->features[] = new ErpProductFeature((string)$feature->feature_key, (string)$feature->value);
                }
            }
        }
        return $ErpProductPrice;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<productprices>';
        $xml .= '<productprice>';
        $xml .= '<reference><![CDATA[' . $this->reference . ']]></reference>';
        $xml .= '<tax_key><![CDATA[' . $this->tax_key . ']]></tax_key>';
        $xml .= '<id_tax_rules_group><![CDATA[' . $this->id_tax_rules_group . ']]></id_tax_rules_group>';
        $xml .= '<ecotax><![CDATA[' . $this->ecotax . ']]></ecotax>';
        $xml .= '<regular_price_tax_exclude><![CDATA[' . $this->regular_price_tax_exclude . ']]></regular_price_tax_exclude>';
        $xml .= '<regular_price_tax_include><![CDATA[' . $this->regular_price_tax_include . ']]></regular_price_tax_include>';
        $xml .= '<price_tax_exclude><![CDATA[' . $this->price_tax_exclude . ']]></price_tax_exclude>';
        $xml .= '<price_tax_include><![CDATA[' . $this->price_tax_include . ']]></price_tax_include>';
        $xml .= '<price_tax><![CDATA[' . $this->price_tax . ']]></price_tax>';
        $xml .= '<tax_rate><![CDATA[' . $this->tax_rate . ']]></tax_rate>';
        $xml .= '<wholesale_price><![CDATA[' . $this->wholesale_price . ']]></wholesale_price>';

        $xml .= '<specific_prices>';
        if (count($this->specificPrices)) {
            foreach ($this->specificPrices as $specific_price) {
                $xml .= $specific_price->getXML();
            }
        }
        $xml .= '</specific_prices>';
        $xml .= '<variations>';
        if (count($this->variations) > 0) {
            foreach ($this->variations as $variation) {
                $xml .= $variation->getXML();
            }
        }
        $xml .= '</variations>';
        $xml .= '<packagings>';
        if (count($this->packagings) > 0) {
            foreach ($this->packagings as $packaging) {
                $xml .= $packaging->getXML();
            }
        }
        $xml .= '</packagings>';
        $xml .= '<custom_fields>';
        if (count($this->customFields) > 0) {
            foreach ($this->customFields as $custom_field) {
                $xml .= $custom_field->getXML();
            }
        }
        $xml .= '</custom_fields>';
        $xml .= '<features>';
        if (count($this->features) > 0) {
            foreach ($this->features as $feature) {
                $xml .= $feature->getXML();
            }
        }
        $xml .= '</features>';

        $xml .= '</productprice>';
        $xml .= '</productprices>';
        return $xml;
    }
}
