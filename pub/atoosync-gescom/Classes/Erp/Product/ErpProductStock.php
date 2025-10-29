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
 * Représente un objet pour la mise à jour du stock d'un article dans le CMS depuis l'ERP
 */
class ErpProductStock
{
    /** @var string La référence unique de l'article dans l'ERP */
    public $reference = "";

    /** @var float La quantité de l'article */
    public $quantity = 0.00;

    /** @var float La quantité minimal de vente */
    public $minimal_quantity = 0.00;

    /** @var float Le poids de l'article */
    public $weight = 0.00;

    /** @var string Le code EAN 13 de l'article */
    public $ean13 = "";

    /** @var string Le Code UPC de l'article */
    public $upc = "";

    /** @var string Code ISBN de l'article dans l'ERP */
    public $isbn = "";

    /** @var string L'emplacement de l'article dans le dépôt */
    public $location = "";

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

    /** @var int La durée de garantie de l'article */
    public $warranty = 0;

    /** @var string La prochaine date de livraison de l'article */
    public $next_delivery_date = "0000-00-00 00:00:00";

    /** @var float La prochaine quantité livrée de l'article */
    public $next_delivery_quantity = 0.0;

    /** @var CustomField[] Les champs personnalisés de l'article dans l'ERP */
    public $customFields = array();

    /** @var ErpProductVariation[] Les variations de l'article */
    public $variations = array();

    /** @var ErpProductPackaging[] Les conditionnements de l'article dans l'ERP */
    public $packagings = array();

    /** @var ErpProductFeature[] Les caractéristiques de l'article configuré dans Atoo-Sync */
    public $features = array();

    /** @var ErpProductFeature[] Les dépôts de l'article dans l'ERP */
    public $warehouses = array();

    /**
     * ATSCProductStock constructor.
     */
    public function __construct()
    {
        $this->customFields = array();
        $this->variations = array();
        $this->packagings = array();
        $this->features = array();
        $this->warehouses = array();
    }

    /**
     * Créé un objet ErpProductStock à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $productStockXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpProductStock
     */
    public static function createFromXML($productStockXml)
    {
        $ErpProductStock = new ErpProductStock();
        if ($productStockXml) {
            $ErpProductStock->reference = (string)$productStockXml->reference;
            $ErpProductStock->quantity = (float)$productStockXml->quantity;
            $ErpProductStock->minimal_quantity = (float)$productStockXml->minimal_quantity;
            $ErpProductStock->weight = (float)$productStockXml->weight;
            $ErpProductStock->ean13 = (string)$productStockXml->ean13;
            $ErpProductStock->upc = (string)$productStockXml->upc;
            $ErpProductStock->isbn = (string)$productStockXml->isbn;
            $ErpProductStock->location = (string)$productStockXml->location;

            $ErpProductStock->manage_stock = ((int)$productStockXml->manage_stock == 1);
            $ErpProductStock->stock_real = (float)$productStockXml->stock_real;
            $ErpProductStock->stock_virtual = (float)$productStockXml->stock_virtual;
            $ErpProductStock->stock_available = (float)$productStockXml->stock_available;
            $ErpProductStock->stock_target = (float)$productStockXml->stock_target;
            $ErpProductStock->stock_real_minus_orders = (float)$productStockXml->stock_real_minus_orders;
            $ErpProductStock->stock_target_minus_purchase_orders = (float)$productStockXml->stock_target_minus_purchase_orders;
            $ErpProductStock->warranty = (int)$productStockXml->warranty;

            $ErpProductStock->next_delivery_date = (string)$productStockXml->next_delivery_date;
            $ErpProductStock->next_delivery_quantity = (float)$productStockXml->next_delivery_quantity;

            // les variations de l'article
            if ($productStockXml->variations) {
                $ErpProductStock->variations = array();

                foreach ($productStockXml->variations->variation as $variation) {
                    $ErpProductStock->variations[] = ErpProductVariation::createFromXML($variation);
                }
            }

            // les conditionnements de l'article
            if ($productStockXml->packagings) {
                $ErpProductStock->packagings = array();

                foreach ($productStockXml->packagings->packaging as $packaging) {
                    $ErpProductStock->packagings[] = ErpProductPackaging::createFromXML($packaging);
                }
            }

            // les champs custom de l'article
            if ($productStockXml->custom_fields) {
                $ErpProductStock->customFields = array();

                foreach ($productStockXml->custom_fields->custom_field as $custom_field) {
                    $ErpProductStock->customFields[] = new CustomField((string)$custom_field->name, (string)$custom_field->value);
                }
            }

            // les caractéristiques de l'article
            if ($productStockXml->features) {
                $ErpProductStock->features = array();

                foreach ($productStockXml->features->feature as $feature) {
                    $ErpProductStock->features[] = new ErpProductFeature((string)$feature->feature_key, (string)$feature->value);
                }
            }

            // les dépots de l'article
            if ($productStockXml->warehouses) {
                $ErpProductStock->warehouses = array();

                foreach ($productStockXml->warehouses->productwarehouse as $productwarehouse) {
                    $ErpProductStock->warehouses[] = ErpProductWarehouse::createFromXML($productwarehouse);
                }
            }
        }
        return $ErpProductStock;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<productstocks>';
        $xml .= '<productstock>';
        $xml .= '<reference><![CDATA[' . $this->reference . ']]></reference>';
        $xml .= '<quantity><![CDATA[' . $this->quantity . ']]></quantity>';
        $xml .= '<minimal_quantity><![CDATA[' . $this->minimal_quantity . ']]></minimal_quantity>';
        $xml .= '<weight><![CDATA[' . $this->weight . ']]></weight>';
        $xml .= '<ean13><![CDATA[' . $this->ean13 . ']]></ean13>';
        $xml .= '<upc><![CDATA[' . $this->upc . ']]></upc>';
        $xml .= '<isbn><![CDATA[' . $this->isbn . ']]></isbn>';
        $xml .= '<location><![CDATA[' . $this->location . ']]></location>';
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
        $xml .= '<warranty><![CDATA[' . $this->warranty . ']]></warranty>';
        $xml .= '<next_delivery_date><![CDATA[' . $this->next_delivery_date . ']]></next_delivery_date>';
        $xml .= '<next_delivery_quantity><![CDATA[' . $this->next_delivery_quantity . ']]></next_delivery_quantity>';

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
        $xml .= '<warehouses>';
        if (count($this->warehouses) > 0) {
            foreach ($this->warehouses as $warehouse) {
                $xml .= $warehouse->getXML();
            }
        }
        $xml .= '</warehouses>';

        $xml .= '</productstock>';
        $xml .= '</productstocks>';
        return $xml;
    }
}
