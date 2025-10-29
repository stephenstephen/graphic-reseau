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
 * Représente un objet pour les ventes croisées/articles liés d'un article dans le CMS
 */
class ErpProductCrossSelling
{
    /** @var string La référence unique de l'article dans l'ERP */
    public $reference = "";

    /**
     * Créé un objet ErpProductCrossSelling à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $productCrossSellinXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpProductCrossSelling
     */
    public static function createFromXML($productCrossSellinXml)
    {
        $ErpProductCrossSelling = new ErpProductCrossSelling();
        if ($ErpProductCrossSelling) {
            $ErpProductCrossSelling->reference  = (string)$productCrossSellinXml->reference;
        }
        return $ErpProductCrossSelling;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<cross_selling>';
        $xml .= '<reference><![CDATA[' . $this->reference . ']]></reference>';
        $xml .= '</cross_selling>';
        return $xml;
    }
}