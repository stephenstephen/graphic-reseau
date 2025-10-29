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
 * Class ErpProductPack
 */
class ErpProductPack
{
    /** @var string La ré&férence de l'article dans le pack */
    public $reference = "";

    /** @var float La quantité de la référence de l'article dans le pack */
    public $quantity = 0.00;

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = '<pack>';
        $xml .= '<reference><![CDATA[' . $this->reference . ']]></reference>';
        $xml .= '<quantity><![CDATA[' . $this->quantity . ']]></quantity>';
        $xml .= '</pack>';
        return $xml;
    }
}
