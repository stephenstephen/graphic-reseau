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
 * Représente un objet détaillant le stock dans le dépot dans l'ERP
 */
class ErpProductWarehouse
{
    /** @var string Le code du dépot dans l'ERP */
    public $warehouse_key = "";

    /** @var string Le nom du dépôt dans l'ERP */
    public $warehouse_name = "";

    /** @var string L'emplacement de l'article dans le dépôt */
    public $location = "";

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

    /**
     * Créé un objet ErpProductWarehouse à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $productWarehouseXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpProductWarehouse
     */
    public static function createFromXML($productWarehouseXml)
    {
        $ErpProductWarehouse = new ErpProductWarehouse();
        if ($ErpProductWarehouse) {
            $ErpProductWarehouse->warehouse_key = (string)$productWarehouseXml->warehouse_key;
            $ErpProductWarehouse->warehouse_name = (string)$productWarehouseXml->warehouse_name;
            $ErpProductWarehouse->location = (string)$productWarehouseXml->location;
            $ErpProductWarehouse->stock_real = (float)$productWarehouseXml->stock_real;
            $ErpProductWarehouse->stock_virtual = (float)$productWarehouseXml->stock_virtual;
            $ErpProductWarehouse->stock_available = (float)$productWarehouseXml->stock_available;
            $ErpProductWarehouse->stock_target = (float)$productWarehouseXml->stock_target;
            $ErpProductWarehouse->stock_real_minus_orders = (float)$productWarehouseXml->stock_real_minus_orders;
            $ErpProductWarehouse->stock_target_minus_purchase_orders = (float)$productWarehouseXml->stock_target_minus_purchase_orders;
        }
        return $ErpProductWarehouse;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<productwarehouse>';
        $xml .= '<warehouse_key><![CDATA[' . $this->warehouse_key . ']]></warehouse_key>';
        $xml .= '<warehouse_name><![CDATA[' . $this->warehouse_name . ']]></warehouse_name>';
        $xml .= '<location><![CDATA[' . $this->location . ']]></location>';
        $xml .= '<stock_real><![CDATA[' . $this->stock_real . ']]></stock_real>';
        $xml .= '<stock_virtual><![CDATA[' . $this->stock_virtual . ']]></stock_virtual>';
        $xml .= '<stock_available><![CDATA[' . $this->stock_available . ']]></stock_available>';
        $xml .= '<stock_target><![CDATA[' . $this->stock_target . ']]></stock_target>';
        $xml .= '<stock_real_minus_orders><![CDATA[' . $this->stock_real_minus_orders . ']]></stock_real_minus_orders>';
        $xml .= '<stock_target_minus_purchase_orders><![CDATA[' . $this->stock_target_minus_purchase_orders . ']]></stock_target_minus_purchase_orders>';
        $xml .= '</productwarehouse>';
        return $xml;
    }
}
