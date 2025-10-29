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

use AtooNext\AtooSync\Commons\CustomField;

/**
 * Class CmsOrderProduct
 */
class CmsOrderProduct
{
    /** @var string La clé (référence) de l'article */
    public $product_key = '';

    /** @var string La clé (référence) de la variation de l'article */
    public $product_variation_key = '';

    /** @var string Le code barre de l'article */
    public $product_ean13 = '';

    /** @var string Le nom de l'article */
    public $product_name = '';

    /** @var float La quantité vendu */
    public $quantity = 0.00;

    /** @var float La prix de vente unitaire HT avant remise */
    public $unit_price_tax_excl = 0.00;

    /** @var float La prix de vente unitaire TTC avant remise */
    public $unit_price_tax_incl = 0.00;

    /** @var float Le montant de la taxe unitaire de l'article avant remise */
    public $unit_price_tax = 0.00;

    /** @var float La prix de vente unitaire HT après remise */
    public $unit_final_price_tax_excl = 0.00;

    /** @var float La prix de vente unitaire TTC après remise */
    public $unit_final_price_tax_incl = 0.00;

    /** @var float Le montant de la taxe unitaire de l'article après remise */
    public $unit_final_price_tax = 0.00;

    /** @var string Le code de la taxe de l'article */
    public $tax_key = '';

    /** @var string Le nom de la taxe de l'article */
    public $tax_name = '';

    /** @var float Le taux de taxe de l'article */
    public $tax_rate = 0.00;

    /** @var float Le taux de remise de l'article */
    public $unit_reduction_percent = 0.00;

    /** @var float Le montant unitaire de la remise de l'article */
    public $unit_reduction_amount = 0.00;

    /** @var float Le montant unitaire de l'ecotaxe de l'article */
    public $unit_ecotax = 0.00;

    /** @var float Le taux de taxe de l'ecotaxe de l'article */
    public $ecotax_tax_rate = 0.00;

    /** @var string La valeur de la gamme 1 de Sage */
    public $sage_gamme1 = '';

    /** @var string La valeur de la gamme 2 de Sage */
    public $sage_gamme2 = '';

    /** @var string Le nom du conditionnement de Sage */
    public $sage_packaging_name = '';

    /** @var float La valeur du conditionnement de Sage */
    public $sage_packaging_quantity = 0.00;

    /** @var bool Indique à l'application Atoo-Sync qu'il ne faut pas créer le détail de la nomenclature */
    public $sage_do_not_create_components = false;

    /** @var string Le nom du dépôt de la ligne */
    public $warehouse = '';

    /** @var CustomField[] Les champs personnalisé de la ligne de commande */
    public $custom_fields = array();

    /** @var int La précision des montants */
    public $price_precision = 2;

    /** @var bool Indique à l'application Atoo-Sync que la ligne est une ligne de texte */
    public $is_text_line = false;

    /**
     * ATSCOrderProduct constructor.
     */
    public function __construct()
    {
        $this->custom_fields = array();
    }

    /**
     * Ajoute un champ personnalisé a la ligne de la commande
     *
     * @param string $name Le nom du champ personnalisé
     * @param string $value La valeur du champ personnalisé
     */
    public function addCustomField($name, $value)
    {
        if (!is_array($this->custom_fields)) {
            $this->custom_fields = array();
        }

        $this->custom_fields[] = new CustomField($name, $value);
    }

    /**
     *  Formate l'objet en XML
     *
     * @return string
     */
    public function getXML()
    {
        // arrondi les montants
        $this->quantity = number_format(round($this->quantity, $this->price_precision), $this->price_precision, '.', '');
        $this->unit_price_tax_excl = number_format(round($this->unit_price_tax_excl, $this->price_precision), $this->price_precision, '.', '');
        $this->unit_price_tax_incl = number_format(round($this->unit_price_tax_incl, $this->price_precision), $this->price_precision, '.', '');
        $this->unit_price_tax = number_format(round($this->unit_price_tax, $this->price_precision), $this->price_precision, '.', '');
        $this->unit_final_price_tax_excl = number_format(round($this->unit_final_price_tax_excl, $this->price_precision), $this->price_precision, '.', '');
        $this->unit_final_price_tax_incl = number_format(round($this->unit_final_price_tax_incl, $this->price_precision), $this->price_precision, '.', '');
        $this->unit_final_price_tax = number_format(round($this->unit_final_price_tax, $this->price_precision), $this->price_precision, '.', '');
        $this->tax_rate = number_format(round($this->tax_rate, 3), 3, '.', '');
        $this->unit_reduction_percent = number_format(round($this->unit_reduction_percent, 3), 3, '.', '');
        $this->unit_reduction_amount = number_format(round($this->unit_reduction_amount, $this->price_precision), $this->price_precision, '.', '');
        $this->sage_packaging_quantity = number_format(round($this->sage_packaging_quantity, $this->price_precision), $this->price_precision, '.', '');
        $this->unit_ecotax = number_format(round($this->unit_ecotax, $this->price_precision), $this->price_precision, '.', '');
        $this->ecotax_tax_rate = number_format(round($this->ecotax_tax_rate, 3), 3, '.', '');

        $xml = '';
        $xml .= '<product>';
        $xml .= '<product_key><![CDATA[' . $this->product_key . ']]></product_key>';
        $xml .= '<product_variation_key><![CDATA[' . $this->product_variation_key . ']]></product_variation_key>';
        $xml .= '<product_ean13><![CDATA[' . $this->product_ean13 . ']]></product_ean13>';
        $xml .= '<product_name><![CDATA[' . $this->product_name . ']]></product_name>';
        $xml .= '<quantity><![CDATA[' . $this->quantity . ']]></quantity>';
        $xml .= '<unit_price_tax_excl><![CDATA[' . $this->unit_price_tax_excl . ']]></unit_price_tax_excl>';
        $xml .= '<unit_price_tax_incl><![CDATA[' . $this->unit_price_tax_incl . ']]></unit_price_tax_incl>';
        $xml .= '<unit_price_tax><![CDATA[' . $this->unit_price_tax . ']]></unit_price_tax>';
        $xml .= '<unit_final_price_tax_excl><![CDATA[' . $this->unit_final_price_tax_excl . ']]></unit_final_price_tax_excl>';
        $xml .= '<unit_final_price_tax_incl><![CDATA[' . $this->unit_final_price_tax_incl . ']]></unit_final_price_tax_incl>';
        $xml .= '<unit_final_price_tax><![CDATA[' . $this->unit_final_price_tax . ']]></unit_final_price_tax>';
        $xml .= '<tax_key><![CDATA[' . $this->tax_key . ']]></tax_key>';
        $xml .= '<tax_name><![CDATA[' . $this->tax_name . ']]></tax_name>';
        $xml .= '<tax_rate><![CDATA[' . $this->tax_rate . ']]></tax_rate>';
        $xml .= '<unit_reduction_percent><![CDATA[' . $this->unit_reduction_percent . ']]></unit_reduction_percent>';
        $xml .= '<unit_reduction_amount><![CDATA[' . $this->unit_reduction_amount . ']]></unit_reduction_amount>';
        $xml .= '<unit_ecotax><![CDATA[' . $this->unit_ecotax . ']]></unit_ecotax>';
        $xml .= '<ecotax_tax_rate><![CDATA[' . $this->ecotax_tax_rate . ']]></ecotax_tax_rate>';
        $xml .= '<warehouse><![CDATA[' . $this->warehouse . ']]></warehouse>';
        $xml .= '<sage_gamme1><![CDATA[' . $this->sage_gamme1 . ']]></sage_gamme1>';
        $xml .= '<sage_gamme2><![CDATA[' . $this->sage_gamme2 . ']]></sage_gamme2>';
        $xml .= '<sage_packaging_name><![CDATA[' . $this->sage_packaging_name . ']]></sage_packaging_name>';
        $xml .= '<sage_packaging_quantity><![CDATA[' . $this->sage_packaging_quantity . ']]></sage_packaging_quantity>';
        if ($this->sage_do_not_create_components) {
            $xml .= '<sage_do_not_create_components><![CDATA[' . '1' . ']]></sage_do_not_create_components>';
        } else {
            $xml .= '<sage_do_not_create_components><![CDATA[' . '0' . ']]></sage_do_not_create_components>';
        }
        if ($this->is_text_line) {
            $xml .= '<is_text_line><![CDATA[' . '1' . ']]></is_text_line>';
        } else {
            $xml .= '<is_text_line><![CDATA[' . '0' . ']]></is_text_line>';
        }

        $xml .= '<custom_fields>';
        if (count($this->custom_fields) > 0) {
            foreach ($this->custom_fields as $custom_field) {
                $xml .= $custom_field->getXML();
            }
        }
        $xml .= '</custom_fields>';

        $xml .= '</product>';

        return $xml;
    }

    /**
     * Créé un objet CmsOrderProduct à partir du XML des commandes
     *
     * @param \SimpleXMLElement $orderProductXml XML de la configuration
     * @return CmsOrderProduct
     */
    public static function createFromXml(\SimpleXMLElement $orderProductXml)
    {
        $cmsOrderProduct = new CmsOrderProduct();
        if ($orderProductXml) {
            $cmsOrderProduct->product_key = (string)$orderProductXml->product_key;
            $cmsOrderProduct->product_variation_key = (string)$orderProductXml->product_variation_key;
            $cmsOrderProduct->product_ean13 = (string)$orderProductXml->product_ean13;
            $cmsOrderProduct->product_name = (string)$orderProductXml->product_name;
            $cmsOrderProduct->quantity = (float)$orderProductXml->quantity;
            $cmsOrderProduct->unit_price_tax_excl = (float)$orderProductXml->unit_price_tax_excl;
            $cmsOrderProduct->unit_price_tax_incl = (float)$orderProductXml->unit_price_tax_incl;
            $cmsOrderProduct->unit_price_tax = (float)$orderProductXml->unit_price_tax;
            $cmsOrderProduct->unit_final_price_tax_excl = (float)$orderProductXml->unit_final_price_tax_excl;
            $cmsOrderProduct->unit_final_price_tax_incl = (float)$orderProductXml->unit_final_price_tax_incl;
            $cmsOrderProduct->unit_final_price_tax = (float)$orderProductXml->unit_final_price_tax;
            $cmsOrderProduct->tax_key = (string)$orderProductXml->tax_key;
            $cmsOrderProduct->tax_name = (string)$orderProductXml->tax_name;
            $cmsOrderProduct->tax_rate = (float)$orderProductXml->tax_rate;
            $cmsOrderProduct->unit_reduction_percent = (float)$orderProductXml->unit_reduction_percent;
            $cmsOrderProduct->unit_reduction_amount = (float)$orderProductXml->unit_reduction_percent;
            $cmsOrderProduct->unit_ecotax = (float)$orderProductXml->unit_ecotax;
            $cmsOrderProduct->ecotax_tax_rate = (float)$orderProductXml->ecotax_tax_rate;
            $cmsOrderProduct->warehouse = (string)$orderProductXml->warehouse;
            $cmsOrderProduct->sage_gamme1 = (string)$orderProductXml->sage_gamme1;
            $cmsOrderProduct->sage_gamme2 = (string)$orderProductXml->sage_gamme2;
            $cmsOrderProduct->sage_packaging_name = (string)$orderProductXml->sage_packaging_name;
            $cmsOrderProduct->sage_packaging_quantity = (float)$orderProductXml->sage_packaging_quantity;
            $cmsOrderProduct->sage_do_not_create_components = (string)$orderProductXml->sage_do_not_create_components == '1';
            $cmsOrderProduct->is_text_line = (string)$orderProductXml->is_text_line == '1';
            if ($orderProductXml->custom_fields) {
                $cmsOrderProduct->custom_fields = array();
                foreach ($orderProductXml->custom_fields->custom_field as $custom_field) {
                    $cmsOrderProduct[] = CustomField::createFromXml($custom_field);
                }
            }
            return $cmsOrderProduct;
        }
    }
}
