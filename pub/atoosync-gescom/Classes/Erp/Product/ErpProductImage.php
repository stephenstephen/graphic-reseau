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
 * Class ErpProductImage
 */
class ErpProductImage
{
    /** @var string La clé de l'image */
    public $atoosync_key = "";

    /** @var string La référence du produit dans l'ERP */
    public $reference = "";

    /** @var string Le nom du fichier image */
    public $filename = "";

    /** @var bool Image de couverture */
    public $cover = false;

    /** @var string Données brut décodées de l'image */
    public $imagedata = "";

    /** @var string MimeType de l'image */
    public $mimetype = "";

    /**
     * Créé un objet ErpProductImage à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $productImageXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpProductImage
     */
    public static function createFromXML($productImageXml)
    {
        $erpProductImage = new ErpProductImage();
        if ($productImageXml) {
            $erpProductImage->atoosync_key = (string)$productImageXml->atoosync_key;
            $erpProductImage->reference = (string)$productImageXml->reference;
            $erpProductImage->filename = (string)$productImageXml->filename;
            $erpProductImage->cover = ((int)$productImageXml->cover == 1);
            $erpProductImage->imagedata = base64_decode((string)$productImageXml->imagedata);
            $erpProductImage->mimetype = (string)$productImageXml->mimetype;

            // si pas de mimetype alors essaye de le trouver
            if (empty($erpProductImage->mimetype)) {
                try {
                    $finfo = new \finfo(FILEINFO_MIME_TYPE);
                    $erpProductImage->mimetype = $finfo->buffer($erpProductImage->imagedata);
                } catch (\Exception $e) {
                    $erpProductImage->mimetype = '';
                }
            }
        }
        return $erpProductImage;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<image>';
        $xml .= '<atoosync_key><![CDATA[' . $this->atoosync_key . ']]></atoosync_key>';
        $xml .= '<reference><![CDATA[' . $this->reference . ']]></reference>';
        $xml .= '<filename><![CDATA[' . $this->filename . ']]></filename>';
        if ($this->cover) {
            $xml .= '<cover><![CDATA[' . '1' . ']]></cover>';
        } else {
            $xml .= '<cover><![CDATA[' . '0' . ']]></cover>';
        }
        $xml .= '<imagedata><![CDATA[' . base64_encode($this->imagedata) . ']]></imagedata>';
        $xml .= '<mimetype><![CDATA[' . $this->mimetype . ']]></mimetype>';
        $xml .= '</image>';
        return $xml;
    }
}
