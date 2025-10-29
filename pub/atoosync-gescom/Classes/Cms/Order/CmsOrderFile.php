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

namespace AtooNext\AtooSync\Cms\Order;

/**
 * Class CmsOrderFile
 */
class CmsOrderFile
{
    /** @var string  Le nom du fichier (ex: plan01.pdf) */
    public $filename = "";

    /** @var string  Le lien du fichier à télécharger */
    public $url = "";

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '';
        $xml .= '<file>';
        $xml .= '<filename><![CDATA[' . $this->filename . ']]></filename>';
        $xml .= '<url><![CDATA[' . $this->url . ']]></link>';
        $xml .= '</file>';

        return $xml;
    }

    /**
     * Créé un objet CmsOrderFile à partir du XML
     *
     * @param \SimpleXMLElement $orderFileXml XML du fichier
     * @return CmsOrderFile
     */
    public static function createFromXml(\SimpleXMLElement $orderFileXml)
    {
        $cmsOrderFile = new CmsOrderFile();
        if ($cmsOrderFile) {
            $cmsOrderFile->filename = (string)$orderFileXml->filename;
            $cmsOrderFile->url = (string)$orderFileXml->url;
        }
        return $cmsOrderFile;
    }
}
