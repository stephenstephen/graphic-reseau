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
 * Class ErpProductDocument
 */
class ErpProductDocument
{
    /** @var string La référence du produit dans l'ERP */
    public $reference = "";

    /** @var string La clé de l'image */
    public $atoosync_key = "";

    /** @var string Nom du fichier sans l'extension */
    public $name = "";

    /** @var string Nom du fichier */
    public $filename = "";

    /** @var string Données brut décodées du document */
    public $documentdata = "";

    /** @var string MimeType du document */
    public $mimetype = "";

    /**
     * Créé un objet ErpProductDocument à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $productDocumentXml L'objet XML envoyé par l'application Atoo-Sync
     * @return ErpProductDocument
     */
    public static function createFromXML($productDocumentXml)
    {
        $erpProductDocument = new ErpProductDocument();
        if ($productDocumentXml) {
            $erpProductDocument->reference = (string)$productDocumentXml->reference;
            $erpProductDocument->atoosync_key = (string)$productDocumentXml->atoosync_key;
            $erpProductDocument->name = (string)$productDocumentXml->name;
            $erpProductDocument->filename = (string)$productDocumentXml->filename;
            $erpProductDocument->documentdata = base64_decode((string)$productDocumentXml->documentdata);
            $erpProductDocument->mimetype = (string)$productDocumentXml->mimetype;

            // si pas de mimetype alors essaye de le trouver
            if (empty($erpProductDocument->mimetype)) {
                try {
                    $finfo = new \finfo(FILEINFO_MIME_TYPE);
                    $erpProductDocument->mimetype = $finfo->buffer($erpProductDocument->imagedata);
                } catch (\Exception $e) {
                    $erpProductDocument->mimetype = '';
                }
            }
        }
        return $erpProductDocument;
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<document>';
        $xml .= '<reference><![CDATA[' . $this->reference . ']]></reference>';
        $xml .= '<atoosync_key><![CDATA[' . $this->atoosync_key . ']]></atoosync_key>';
        $xml .= '<name><![CDATA[' . $this->name . ']]></name>';
        $xml .= '<filename><![CDATA[' . $this->filename . ']]></filename>';
        $xml .= '<documentdata><![CDATA[' . base64_encode($this->documentdata) . ']]></documentdata>';
        $xml .= '<mimetype><![CDATA[' . $this->mimetype . ']]></mimetype>';
        $xml .= '</document>';
        return $xml;
    }
}
