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
 * Représente un objet pour la création ou la mise à jour d'un article dans le CMS depuis l'ERP
 */
class ErpProduct
{
    /** @var string La correspondance de clé de taxe de l'article dans le CMS */
    public $tax_key = "";

    /**
     * @var string La correspondance de clé de taxe de l'article dans le CMS
     * @deprecated Utiliser tax_key à la place
     */
    public $id_tax_rules_group = "";

    /** @var string La référence unique de l'article dans l'ERP */
    public $reference = "";

    /** @var string Code barre de l'article dans l'ERP */
    public $ean13 = "";

    /** @var string Code Upc de l'article dans l'ERP */
    public $upc = "";

    /** @var string Code ISBN de l'article dans l'ERP */
    public $isbn = "";

    /** @var float Le montant de l'ecotaxe de l'article */
    public $ecotax = 0.00;

    /** @var float Le stock de l'article */
    public $quantity = 0.00;

    /** @var float La quantité minimal de vente de l'article */
    public $minimal_quantity = 0.00;

    /**
     * @var float Le prix de vente HT de l'article
     * @deprecated Utiliser price_tax_exclude à la place
     */
    public $price = 0;

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

    /** @var float Le prix d'achat de l'article dans l'EPR */
    public $wholesale_price = 0.00;

    /** @var string L'unité de vente de l'article dans l'ERP */
    public $unity = "";

    /** @var string Le nom du conditionnement de l'article dans l'ERP */
    public $packaging_name = "";

    /** @var string La référence fournisseur de l'article dans l'ERP */
    public $supplier_reference = "";

    /** @var string Le Code barre du fournisseur de l'article dans l'ERP */
    public $supplier_ean13 = "";

    /** @var string Le Code upc du fournisseur de l'article dans l'ERP */
    public $supplier_upc = "";

    /** @var string L'emplacement de l'article dans le dépôt dans l'ERP */
    public $location = "";

    /** @var float La longeur de l'article */
    public $width = 0.00;

    /** @var float La hauteur de l'article */
    public $height = 0.00;

    /** @var float La profondeur de l'article */
    public $depth = 0.00;

    /** @var float Le poids de l'article */
    public $weight = 0.00;

    /** @var string Date de disponibilité de l'article */
    public $available_date = "0000-00-00";

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

    /** @var string Le nom du fabricant de l'article */
    public $manufacturer_name = "";

    /** @var string Le nom du fournisseur de l'article */
    public $supplier_name = "";
    
    /** @var string Le nom de la référence de remplacement */
    public $substitute_product_key = "";

    /** @var string La famille de l'article dans l'ERP */
    public $product_family = "";

    /** @var string La sous-famille de l'article dans l'ERP */
    public $product_subfamily = "";

    /** @var string Le nom de la TVA de l'article dans l'ERP */
    public $vat_name = "";

    /** @var boolean Indique si l'article est suivi en stock ou non dans l'ERP */
    public $manage_stock = true;

    /** @var float La quantité de stock réel de l'article dans l'ERP */
    public $stock_real = 0.00;

    /** @var float La quantité de stock virtuel de l'article dans l'ERP */
    public $stock_virtual = 0.00;

    /** @var float La quantité de stock disponible de l'article dans l'ERP */
    public $stock_available = 0.00;

    /** @var float La quantité de stock à terme de l'article dans l'ERP */
    public $stock_target = 0.00;

    /** @var float La quantité de stock réel moins les commandes clients de l'article dans l'ERP */
    public $stock_real_minus_orders = 0.00;

    /** @var float La quantité de stock à terme moins les commandes d'achats de l'article dans l'ERP */
    public $stock_target_minus_purchase_orders = 0.00;

    /** @var float Le délai de livraison de l'article configuré dans l'ERP */
    public $delivery_delay = "";

    /** @var int La durée de garantie de l'article */
    public $warranty = 0;

    /** @var string La prochaine date de livraison de l'article */
    public $next_delivery_date = "0000-00-00 00:00:00";

    /** @var float La prochaine quantité livrée de l'article */
    public $next_delivery_quantity = 0.0;

    /** @var string Le nom de la catégorie de niveau 1 de l'article */
    public $product_category_1 = "";

    /** @var string Le nom de la catégorie de niveau 2 de l'article */
    public $product_category_2 = "";

    /** @var string Le nom de la catégorie de niveau 3 de l'article */
    public $product_category_3 = "";

    /** @var string Le nom de la catégorie de niveau 4 de l'article */
    public $product_category_4 = "";

    /** @var string Le nom de la catégorie de niveau 5 de l'article */
    public $product_category_5 = "";

    /** @var string Le nom de la catégorie de niveau 6 de l'article */
    public $product_category_6 = "";

    /** @var string Le nom de la catégorie de niveau 7 de l'article */
    public $product_category_7 = "";

    /** @var string Le nom de la catégorie de niveau 8 de l'article */
    public $product_category_8 = "";

    /** @var string Le nom de la catégorie de niveau 9 de l'article */
    public $product_category_9 = "";

    /** @var string Le nom de la catégorie de niveau 10 de l'article */
    public $product_category_10 = "";

    /** @var string Les catégories supplémentaires de l'article */
    public $additionnal_categories = "";

    /** @var string La référence de regroupement de l'article */
    public $variation_reference = "";

    /** @var string Le nom de la variation 1 */
    public $variation_1 = "";

    /** @var string La valeur de la variation 1 */
    public $variation_value_1 = "";

    /** @var string Le nom de la variation 2 */
    public $variation_2 = "";

    /** @var string La valeur de la variation 2 */
    public $variation_value_2 = "";

    /** @var string Le nom de la variation 3 */
    public $variation_3 = "";

    /** @var string La valeur de la variation 3 */
    public $variation_value_3 = "";

    /** @var string Le nom de la variation 4 */
    public $variation_4 = "";

    /** @var string La valeur de la variation 4 */
    public $variation_value_4 = "";

    /** @var string Le nom de la variation 5 */
    public $variation_5 = "";

    /** @var string La valeur de la variation 5 */
    public $variation_value_5 = "";

    /** @var string Le nom de la variation 6 */
    public $variation_6 = "";

    /** @var string La valeur de la variation 6 */
    public $variation_value_6 = "";

    /** @var string Le nom de la variation 7 */
    public $variation_7 = "";

    /** @var string La valeur de la variation 7 */
    public $variation_value_7 = "";

    /** @var string Le nom de la variation 8 */
    public $variation_8 = "";

    /** @var string La valeur de la variation 8 */
    public $variation_value_8 = "";

    /** @var string Le nom de la variation 9 */
    public $variation_9 = "";

    /** @var string La valeur de la variation 9 */
    public $variation_value_9 = "";

    /** @var string Le nom de la variation 10 */
    public $variation_10 = "";

    /** @var string La valeur de la variation 10 */
    public $variation_value_10 = "";

    /** @var string La numéro de catalogue de l'article dans Sage 100 Odbc */
    public $sage_cl_no = "";

    /** @var string Le code de la famille de l'article dans Sage 100 Odbc */
    public $sage_fa_codefamille = "";

    /** @var int Le numéro de la gamme 1 de l'article dans Sage 100 Odbc */
    public $sage_ar_gamme1 = 0;

    /** @var int Le numéro de la gamme 2 de l'article dans Sage 100 Odbc */
    public $sage_ar_gamme2 = 0;

    /** @var ErpProductLanguage[] Les langues de l'article */
    public $languages = array();

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

    /** @var ErpProductPack[] Les articles du pack dans l'ERP */
    public $packs = array();

    /** @var ErpProductWarehouse[] Les dépôts de l'article dans l'ERP */
    public $warehouses = array();

    /** @var ErpProductCrossSelling[] Les références des articles en ventes croisées de l'article dans l'ERP */
    public $crossSellings = array();

    /**
     * ATSCProductStock constructor.
     */
    public function __construct()
    {
        $this->languages = array();
        $this->customFields = array();
        $this->variations = array();
        $this->packagings = array();
        $this->features = array();
        $this->specificPrices = array();
        $this->packs = array();
        $this->warehouses = array();
        $this->crossSellings = array();
    }

    /**
     * Créé un objet ErpProduct à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $productXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpProduct
     */
    public static function createFromXML($productXml)
    {
        $ErpProduct = new ErpProduct();
        if ($productXml) {
            $ErpProduct->reference = (string)$productXml->reference;
            $ErpProduct->tax_key = (string)$productXml->tax_key;
            $ErpProduct->id_tax_rules_group = (string)$productXml->id_tax_rules_group;
            $ErpProduct->ean13 = (string)$productXml->ean13;
            $ErpProduct->upc = (string)$productXml->upc;
            $ErpProduct->isbn = (string)$productXml->isbn;
            $ErpProduct->ecotax = (float)$productXml->ecotax;
            $ErpProduct->quantity = (float)$productXml->quantity;
            $ErpProduct->minimal_quantity = (float)$productXml->minimal_quantity;
            $ErpProduct->price = (float)$productXml->price_tax_exclude;
            $ErpProduct->regular_price_tax_exclude = (float)$productXml->regular_price_tax_exclude;
            $ErpProduct->regular_price_tax_include = (float)$productXml->regular_price_tax_include;
            $ErpProduct->price_tax_exclude = (float)$productXml->price_tax_exclude;
            $ErpProduct->price_tax_include = (float)$productXml->price_tax_include;
            $ErpProduct->price_tax = (float)$productXml->price_tax;
            $ErpProduct->tax_rate = (float)$productXml->tax_rate;
            $ErpProduct->wholesale_price = (float)$productXml->wholesale_price;
            $ErpProduct->unity = (string)$productXml->unity;
            $ErpProduct->packaging_name = (string)$productXml->packaging_name;
            $ErpProduct->supplier_reference = (string)$productXml->supplier_reference;
            $ErpProduct->supplier_ean13 = (string)$productXml->supplier_ean13;
            $ErpProduct->supplier_upc = (string)$productXml->supplier_upc;
            $ErpProduct->location = (string)$productXml->location;
            $ErpProduct->width = (float)$productXml->width;
            $ErpProduct->height = (float)$productXml->height;
            $ErpProduct->depth = (float)$productXml->depth;
            $ErpProduct->weight = (float)$productXml->weight;
            $ErpProduct->available_date = (string)$productXml->available_date;

            $ErpProduct->name = (string)$productXml->name;
            $ErpProduct->description = (string)$productXml->description;
            $ErpProduct->description_short = (string)$productXml->description_short;
            $ErpProduct->link_rewrite = (string)$productXml->link_rewrite;
            $ErpProduct->meta_description = (string)$productXml->meta_description;
            $ErpProduct->meta_keywords = (string)$productXml->meta_keywords;
            $ErpProduct->meta_title = (string)$productXml->meta_title;

            $ErpProduct->manufacturer_name = (string)$productXml->manufacturer_name;
            $ErpProduct->supplier_name = (string)$productXml->supplier_name;
            $ErpProduct->substitute_product_key = (string)$productXml->substitute_product_key;
            $ErpProduct->product_family = (string)$productXml->product_family;
            $ErpProduct->product_subfamily = (string)$productXml->product_subfamily;
            $ErpProduct->vat_name = (string)$productXml->vat_name;

            $ErpProduct->manage_stock = ((int)$productXml->manage_stock == 1);
            $ErpProduct->stock_real = (float)$productXml->stock_real;
            $ErpProduct->stock_virtual = (float)$productXml->stock_virtual;
            $ErpProduct->stock_available = (float)$productXml->stock_available;
            $ErpProduct->stock_target = (float)$productXml->stock_target;
            $ErpProduct->stock_real_minus_orders = (float)$productXml->stock_real_minus_orders;
            $ErpProduct->stock_target_minus_purchase_orders = (float)$productXml->stock_target_minus_purchase_orders;

            $ErpProduct->delivery_delay = (float)$productXml->delivery_delay;
            $ErpProduct->warranty = (int)$productXml->warranty;
            $ErpProduct->next_delivery_date = (string)$productXml->next_delivery_date;
            $ErpProduct->next_delivery_quantity = (float)$productXml->next_delivery_quantity;

            $ErpProduct->product_category_1 = (string)$productXml->product_category_1;
            $ErpProduct->product_category_2 = (string)$productXml->product_category_2;
            $ErpProduct->product_category_3 = (string)$productXml->product_category_3;
            $ErpProduct->product_category_4 = (string)$productXml->product_category_4;
            $ErpProduct->product_category_5 = (string)$productXml->product_category_5;
            $ErpProduct->product_category_6 = (string)$productXml->product_category_6;
            $ErpProduct->product_category_7 = (string)$productXml->product_category_7;
            $ErpProduct->product_category_8 = (string)$productXml->product_category_8;
            $ErpProduct->product_category_9 = (string)$productXml->product_category_9;
            $ErpProduct->product_category_10 = (string)$productXml->product_category_10;
            $ErpProduct->additionnal_categories = (string)$productXml->additionnal_categories;

            $ErpProduct->variation_reference = (string)$productXml->variation_reference;
            $ErpProduct->variation_1 = (string)$productXml->variation_1;
            $ErpProduct->variation_value_1 = (string)$productXml->variation_value_1;
            $ErpProduct->variation_2 = (string)$productXml->variation_2;
            $ErpProduct->variation_value_2 = (string)$productXml->variation_value_2;
            $ErpProduct->variation_3 = (string)$productXml->variation_3;
            $ErpProduct->variation_value_3 = (string)$productXml->variation_value_3;
            $ErpProduct->variation_4 = (string)$productXml->variation_4;
            $ErpProduct->variation_value_4 = (string)$productXml->variation_value_4;
            $ErpProduct->variation_5 = (string)$productXml->variation_5;
            $ErpProduct->variation_value_5 = (string)$productXml->variation_value_5;
            $ErpProduct->variation_6 = (string)$productXml->variation_6;
            $ErpProduct->variation_value_6 = (string)$productXml->variation_value_6;
            $ErpProduct->variation_7 = (string)$productXml->variation_7;
            $ErpProduct->variation_value_7 = (string)$productXml->variation_value_7;
            $ErpProduct->variation_8 = (string)$productXml->variation_8;
            $ErpProduct->variation_value_8 = (string)$productXml->variation_value_8;
            $ErpProduct->variation_9 = (string)$productXml->variation_9;
            $ErpProduct->variation_value_9 = (string)$productXml->variation_value_9;
            $ErpProduct->variation_10 = (string)$productXml->variation_10;
            $ErpProduct->variation_value_10 = (string)$productXml->variation_value_10;


            $ErpProduct->sage_cl_no = (int)$productXml->sage_cl_no;
            $ErpProduct->sage_fa_codefamille = (string)$productXml->sage_fa_codefamille;
            $ErpProduct->sage_ar_gamme1 = (int)$productXml->sage_ar_gamme1;
            $ErpProduct->sage_ar_gamme2 = (int)$productXml->sage_ar_gamme2;

            // les prix spécifiques de l'article
            if ($productXml->languages) {
                $ErpProduct->languages = array();

                foreach ($productXml->languages->language as $languageXml) {
                    $ErpProduct->languages[] = ErpProductLanguage::createFromXML($languageXml);
                }
            }

            // les variations de l'article
            if ($productXml->variations) {
                $ErpProduct->variations = array();

                foreach ($productXml->variations->variation as $variation) {
                    $ErpProduct->variations[] = ErpProductVariation::createFromXML($variation);
                }
            }

            // les conditionnements de l'article
            if ($productXml->packagings) {
                $ErpProduct->packagings = array();

                foreach ($productXml->packagings->packaging as $packaging) {
                    $ErpProduct->packagings[] = ErpProductPackaging::createFromXML($packaging);
                }
            }

            // le contenu du pack de l'article
            if ($productXml->packs) {
                $ErpProduct->packs = array();

                foreach ($productXml->packs->pack as $pack) {
                    $ErpProductPack = new ErpProductPack();
                    $ErpProductPack->reference = (string)$pack->reference;
                    $ErpProductPack->quantity = (string)$pack->quantity;
                    $ErpProduct->packs[] = $ErpProductPack;
                }
            }

            // les prix spécifiques de l'article
            if ($productXml->specific_prices) {
                $ErpProduct->specificPrices = array();

                foreach ($productXml->specific_prices->specific_price as $specific_price) {
                    $ErpProduct->specificPrices[] = ErpProductSpecificPrice::createFromXML($specific_price);
                }
            }

            // les caractéristiques de l'article
            if ($productXml->features) {
                $ErpProduct->features = array();
                foreach ($productXml->features->feature as $feature) {
                    $ErpProduct->features[] = new ErpProductFeature((string)$feature->feature_key, (string)$feature->value);
                }
            }

            // les dépots de l'article
            if ($productXml->warehouses) {
                $ErpProduct->warehouses = array();

                foreach ($productXml->warehouses->productwarehouse as $productwarehouse) {
                    $ErpProduct->warehouses[] = ErpProductWarehouse::createFromXML($productwarehouse);
                }
            }

            // les ventes croisées de l'article
            if ($productXml->cross_sellings) {
                $ErpProduct->crossSellings = array();

                foreach ($productXml->cross_sellings->cross_selling as $crossSelling) {
                    $ErpProduct->crossSellings[] = ErpProductCrossSelling::createFromXML($crossSelling);
                }
            }

            // les champs custom de l'article
            if ($productXml->custom_fields) {
                $ErpProduct->customFields = array();

                foreach ($productXml->custom_fields->custom_field as $custom_field) {
                    $ErpProduct->customFields[] = new CustomField((string)$custom_field->name, (string)$custom_field->value);
                }
            }
        }
        return $ErpProduct;
    }


    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<products>';
        $xml .= '<product>';

        $xml .= '<reference><![CDATA[' . $this->reference . ']]></reference>';
        $xml .= '<tax_key><![CDATA[' . $this->tax_key . ']]></tax_key>';
        $xml .= '<ean13><![CDATA[' . $this->ean13 . ']]></ean13>';
        $xml .= '<upc><![CDATA[' . $this->upc . ']]></upc>';
        $xml .= '<isbn><![CDATA[' . $this->isbn . ']]></isbn>';
        $xml .= '<ecotax><![CDATA[' . $this->ecotax . ']]></ecotax>';
        $xml .= '<quantity><![CDATA[' . $this->quantity . ']]></quantity>';
        $xml .= '<minimal_quantity><![CDATA[' . $this->minimal_quantity . ']]></minimal_quantity>';
        $xml .= '<regular_price_tax_exclude><![CDATA[' . $this->regular_price_tax_exclude . ']]></regular_price_tax_exclude>';
        $xml .= '<regular_price_tax_include><![CDATA[' . $this->regular_price_tax_include . ']]></regular_price_tax_include>';
        $xml .= '<price_tax_exclude><![CDATA[' . $this->price_tax_exclude . ']]></price_tax_exclude>';
        $xml .= '<price_tax_include><![CDATA[' . $this->price_tax_include . ']]></price_tax_include>';
        $xml .= '<price_tax><![CDATA[' . $this->price_tax . ']]></price_tax>';
        $xml .= '<tax_rate><![CDATA[' . $this->tax_rate . ']]></tax_rate>';
        $xml .= '<wholesale_price><![CDATA[' . $this->wholesale_price . ']]></wholesale_price>';
        $xml .= '<unity><![CDATA[' . $this->unity . ']]></unity>';
        $xml .= '<packaging_name><![CDATA[' . $this->packaging_name . ']]></packaging_name>';
        $xml .= '<supplier_reference><![CDATA[' . $this->supplier_reference . ']]></supplier_reference>';
        $xml .= '<supplier_ean13><![CDATA[' . $this->supplier_ean13 . ']]></supplier_ean13>';
        $xml .= '<supplier_upc><![CDATA[' . $this->supplier_upc . ']]></supplier_upc>';
        $xml .= '<location><![CDATA[' . $this->location . ']]></location>';
        $xml .= '<width><![CDATA[' . $this->width . ']]></width>';
        $xml .= '<height><![CDATA[' . $this->height . ']]></height>';
        $xml .= '<depth><![CDATA[' . $this->depth . ']]></depth>';
        $xml .= '<weight><![CDATA[' . $this->weight . ']]></weight>';
        $xml .= '<available_date><![CDATA[' . $this->available_date . ']]></available_date>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '<description><![CDATA[' . $this->description . ']]></description>';
        $xml .= '<description_short><![CDATA[' . $this->description_short . ']]></description_short>';
        $xml .= '<link_rewrite><![CDATA[' . $this->link_rewrite . ']]></link_rewrite>';
        $xml .= '<meta_description><![CDATA[' . $this->meta_description . ']]></meta_description>';
        $xml .= '<meta_keywords><![CDATA[' . $this->meta_keywords . ']]></meta_keywords>';
        $xml .= '<meta_title><![CDATA[' . $this->meta_title . ']]></meta_title>';
        $xml .= '<manufacturer_name><![CDATA[' . $this->manufacturer_name . ']]></manufacturer_name>';
        $xml .= '<supplier_name><![CDATA[' . $this->supplier_name . ']]></supplier_name>';
        $xml .= '<product_family><![CDATA[' . $this->product_family . ']]></product_family>';
        $xml .= '<product_subfamily><![CDATA[' . $this->product_subfamily . ']]></product_subfamily>';
        $xml .= '<vat_name><![CDATA[' . $this->vat_name . ']]></vat_name>';
        if ($this->manage_stock) {
            $xml .= '<manage_stock><![CDATA[' . '1' . ']]></manage_stock>';
        } else {
            $xml .= '<manage_stock><![CDATA[' . '0' . ']]></manage_stock>';
        }
        $xml .= '<stock_real><![CDATA[' . $this->stock_real . ']]></stock_real>';
        $xml .= '<stock_virtual><![CDATA[' . $this->stock_virtual . ']]></stock_virtual>';
        $xml .= '<stock_available><![CDATA[' . $this->stock_available . ']]></stock_available>';
        $xml .= '<stock_target><![CDATA[' . $this->stock_target . ']]></stock_target>';
        $xml .= '<stock_real_minus_orders><![CDATA[' . $this->stock_real_minus_orders . ']]></stock_real_minus_orders>';
        $xml .= '<stock_target_minus_purchase_orders><![CDATA[' . $this->stock_target_minus_purchase_orders . ']]></stock_target_minus_purchase_orders>';
        $xml .= '<delivery_delay><![CDATA[' . $this->delivery_delay . ']]></delivery_delay>';
        $xml .= '<warranty><![CDATA[' . $this->warranty . ']]></warranty>';
        $xml .= '<next_delivery_date><![CDATA[' . $this->next_delivery_date . ']]></next_delivery_date>';
        $xml .= '<next_delivery_quantity><![CDATA[' . $this->next_delivery_quantity . ']]></next_delivery_quantity>';
        $xml .= '<product_category_1><![CDATA[' . $this->product_category_1 . ']]></product_category_1>';
        $xml .= '<product_category_2><![CDATA[' . $this->product_category_2 . ']]></product_category_2>';
        $xml .= '<product_category_3><![CDATA[' . $this->product_category_3 . ']]></product_category_3>';
        $xml .= '<product_category_4><![CDATA[' . $this->product_category_4 . ']]></product_category_4>';
        $xml .= '<product_category_5><![CDATA[' . $this->product_category_5 . ']]></product_category_5>';
        $xml .= '<product_category_6><![CDATA[' . $this->product_category_6 . ']]></product_category_6>';
        $xml .= '<product_category_7><![CDATA[' . $this->product_category_7 . ']]></product_category_7>';
        $xml .= '<product_category_8><![CDATA[' . $this->product_category_8 . ']]></product_category_8>';
        $xml .= '<product_category_9><![CDATA[' . $this->product_category_9 . ']]></product_category_9>';
        $xml .= '<product_category_10><![CDATA[' . $this->product_category_10 . ']]></product_category_10>';
        $xml .= '<additionnal_categories><![CDATA[' . $this->additionnal_categories . ']]></additionnal_categories>';
        $xml .= '<variation_reference><![CDATA[' . $this->variation_reference . ']]></variation_reference>';
        $xml .= '<variation_1><![CDATA[' . $this->variation_1 . ']]></variation_1>';
        $xml .= '<variation_value_1><![CDATA[' . $this->variation_value_1 . ']]></variation_value_1>';
        $xml .= '<variation_2><![CDATA[' . $this->variation_2 . ']]></variation_2>';
        $xml .= '<variation_value_2><![CDATA[' . $this->variation_value_2 . ']]></variation_value_2>';
        $xml .= '<variation_3><![CDATA[' . $this->variation_3 . ']]></variation_3>';
        $xml .= '<variation_value_3><![CDATA[' . $this->variation_value_3 . ']]></variation_value_3>';
        $xml .= '<variation_4><![CDATA[' . $this->variation_4 . ']]></variation_4>';
        $xml .= '<variation_value_4><![CDATA[' . $this->variation_value_4 . ']]></variation_value_4>';
        $xml .= '<variation_5><![CDATA[' . $this->variation_5 . ']]></variation_5>';
        $xml .= '<variation_value_5><![CDATA[' . $this->variation_value_5 . ']]></variation_value_5>';
        $xml .= '<variation_6><![CDATA[' . $this->variation_6 . ']]></variation_6>';
        $xml .= '<variation_value_6><![CDATA[' . $this->variation_value_6 . ']]></variation_value_6>';
        $xml .= '<variation_7><![CDATA[' . $this->variation_7 . ']]></variation_7>';
        $xml .= '<variation_value_7><![CDATA[' . $this->variation_value_7 . ']]></variation_value_7>';
        $xml .= '<variation_8><![CDATA[' . $this->variation_8 . ']]></variation_8>';
        $xml .= '<variation_value_8><![CDATA[' . $this->variation_value_8 . ']]></variation_value_8>';
        $xml .= '<variation_9><![CDATA[' . $this->variation_9 . ']]></variation_9>';
        $xml .= '<variation_value_9><![CDATA[' . $this->variation_value_9 . ']]></variation_value_9>';
        $xml .= '<variation_10><![CDATA[' . $this->variation_10 . ']]></variation_10>';
        $xml .= '<variation_value_10><![CDATA[' . $this->variation_value_10 . ']]></variation_value_10>';
        $xml .= '<sage_cl_no><![CDATA[' . $this->sage_cl_no . ']]></sage_cl_no>';
        $xml .= '<sage_fa_codefamille><![CDATA[' . $this->sage_fa_codefamille . ']]></sage_fa_codefamille>';
        $xml .= '<sage_ar_gamme1><![CDATA[' . $this->sage_ar_gamme1 . ']]></sage_ar_gamme1>';
        $xml .= '<sage_ar_gamme2><![CDATA[' . $this->sage_ar_gamme2 . ']]></sage_ar_gamme2>';

        $xml .= '<languages>';
        if (count($this->languages) > 0) {
            foreach ($this->languages as $language) {
                $xml .= $language->getXML();
            }
        }
        $xml .= '</languages>';

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

        $xml .= '<packs>';
        if (count($this->packs) > 0) {
            foreach ($this->packs as $pack) {
                $xml .= $pack->getXML();
            }
        }
        $xml .= '</packs>';

        $xml .= '<specific_prices>';
        if (count($this->specificPrices) > 0) {
            foreach ($this->specificPrices as $specific_price) {
                $xml .= $specific_price->getXML();
            }
        }
        $xml .= '</specific_prices>';

        $xml .= '<features>';
        if (count($this->features) > 0) {
            foreach ($this->features as $feature) {
                $xml .= $feature->getXML();
            }
        }
        $xml .= '</features>';

        $xml .= '<warehouses>';
        if (count($this->warehouses) > 0) {
            foreach ($this->warehouses as $warehouse) {
                $xml .= $warehouse->getXML();
            }
        }
        $xml .= '</warehouses>';

        $xml .= '<cross_sellings>';
        if (count($this->crossSellings) > 0) {
            foreach ($this->crossSellings as $crossSelling) {
                $xml .= $crossSelling->getXML();
            }
        }
        $xml .= '</cross_sellings>';

        $xml .= '<custom_fields>';
        if (count($this->customFields) > 0) {
            foreach ($this->customFields as $custom_field) {
                $xml .= $custom_field->getXML();
            }
        }
        $xml .= '</custom_fields>';

        $xml .= '</product>';
        $xml .= '</products>';

        return $xml;
    }
}
