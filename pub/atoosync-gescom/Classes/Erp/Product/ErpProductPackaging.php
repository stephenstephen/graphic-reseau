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
 * Class ErpProductPackaging
 */
class ErpProductPackaging
{
    /** @var string La clé unique du conditionnement de l'article */
    public $atoosync_key = "";

    /** @var string La référence du conditionnement de l'article */
    public $reference = "";

    /** @var string La référence fournisseur du conditionnement de l'article */
    public $supplier_reference = "";

    /** @var string Le code EAN 13 du conditionnement de l'article */
    public $ean13 = "";

    /** @var string Le Code UPC du conditionnement de l'article */
    public $upc = "";

    /** @var float Le prix de vente du conditionnement de l'article */
    public $price = 0;

    /** @var float La quantité en stock du conditionnement de l'article */
    public $quantity = 0;

    /** @var bool Conditionnement par défaut */
    public $default_on = false;

    /** @var string La nom du conditionnment */
    public $packaging_name = "";

    /** @var float La quantité de conditionnment */
    public $packaging_quantity = 0.00;

    /** @var float La quantité de stock réel du conditionnement de l'article dans l'ERP */
    public $realstock = 0.00;

    /** @var float La quantité de stock virtuel du conditionnement de l'article dans l'ERP */
    public $virtualstock = 0.00;

    /** @var float La quantité de stock disponible du conditionnement de l'article dans l'ERP */
    public $availablestock = 0.00;

    /** @var float La quantité de stock à terme du conditionnement de l'article dans l'ERP */
    public $targetstock = 0.00;

    /** @var float La quantité de stock réel mois les commandes clients du conditionnement de l'article dans l'ERP */
    public $realstockminusorders = 0.00;

    /** @var float La quantité de stock à terme moins les commandes d'achats du conditionnement de l'article dans l'ERP */
    public $targetminuspurchasesorders = 0.00;

    /** @var string La prochaine date de livraison de l'article */
    public $nextdeliverydate = "";

    /** @var float La prochaine quantité livrée de l'article */
    public $nextdeliveryquantity = 0.0;


    /**
     * Créé un objet ErpProductPackaging à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $productPackagingXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpProductPackaging
     */
    public static function createFromXML($productPackagingXml)
    {
        $ErpProductPackaging = new ErpProductPackaging();
        if ($ErpProductPackaging) {
            $ErpProductPackaging->atoosync_key = (string)$productPackagingXml->atoosync_key;
            $ErpProductPackaging->reference = (string)$productPackagingXml->reference;
            $ErpProductPackaging->supplier_reference = (string)$productPackagingXml->supplier_reference;
            $ErpProductPackaging->ean13 = (string)$productPackagingXml->ean13;
            $ErpProductPackaging->upc = (string)$productPackagingXml->upc;
            $ErpProductPackaging->price = (float)$productPackagingXml->price;
            $ErpProductPackaging->quantity = (float)$productPackagingXml->quantity;
            $ErpProductPackaging->default_on = ((int)$productPackagingXml->default_on == 1);

            $ErpProductPackaging->packaging_name = (string)$productPackagingXml->packaging_name;
            $ErpProductPackaging->packaging_quantity = (float)$productPackagingXml->packaging_quantity;

            $ErpProductPackaging->realstock = (float)$productPackagingXml->realstock;
            $ErpProductPackaging->virtualstock = (float)$productPackagingXml->virtualstock;
            $ErpProductPackaging->availablestock = (float)$productPackagingXml->availablestock;
            $ErpProductPackaging->targetstock = (float)$productPackagingXml->targetstock;
            $ErpProductPackaging->realstockminusorders = (float)$productPackagingXml->realstockminusorders;
            $ErpProductPackaging->targetminuspurchasesorders = (float)$productPackagingXml->targetminuspurchasesorders;
            $ErpProductPackaging->nextdeliverydate = (float)$productPackagingXml->nextdeliverydate;
            $ErpProductPackaging->nextdeliveryquantity = (float)$productPackagingXml->nextdeliveryquantity;
        }
        return $ErpProductPackaging;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<packaging>';
        $xml .= '<atoosync_key><![CDATA[' . $this->atoosync_key . ']]></atoosync_key>';
        $xml .= '<reference><![CDATA[' . $this->reference . ']]></reference>';
        $xml .= '<supplier_reference><![CDATA[' . $this->supplier_reference . ']]></supplier_reference>';
        $xml .= '<ean13><![CDATA[' . $this->ean13 . ']]></ean13>';
        $xml .= '<upc><![CDATA[' . $this->upc . ']]></upc>';
        $xml .= '<price><![CDATA[' . $this->price . ']]></price>';
        $xml .= '<quantity><![CDATA[' . $this->quantity . ']]></quantity>';
        $xml .= '<default_on><![CDATA[' . $this->default_on . ']]></default_on>';
        $xml .= '<packaging_name><![CDATA[' . $this->packaging_name . ']]></packaging_name>';
        $xml .= '<packaging_quantity><![CDATA[' . $this->packaging_quantity . ']]></packaging_quantity>';
        $xml .= '<realstock><![CDATA[' . $this->realstock . ']]></realstock>';
        $xml .= '<virtualstock><![CDATA[' . $this->virtualstock . ']]></virtualstock>';
        $xml .= '<availablestock><![CDATA[' . $this->availablestock . ']]></availablestock>';
        $xml .= '<targetstock><![CDATA[' . $this->targetstock . ']]></targetstock>';
        $xml .= '<realstockminusorders><![CDATA[' . $this->realstockminusorders . ']]></realstockminusorders>';
        $xml .= '<targetminuspurchasesorders><![CDATA[' . $this->targetminuspurchasesorders . ']]></targetminuspurchasesorders>';
        $xml .= '<nextdeliverydate><![CDATA[' . $this->nextdeliverydate . ']]></nextdeliverydate>';
        $xml .= '<nextdeliveryquantity><![CDATA[' . $this->nextdeliveryquantity . ']]></nextdeliveryquantity>';
        $xml .= '</packaging>';
        return $xml;
    }
}
