<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Design\Model;

use DOMDocument;
use Magedelight\Productpdf\Helper\Data;
use Magedelight\Productpdf\Model\Product\Pdf;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Image\Factory;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Store\Model\StoreManager;
use Psr\Log\LoggerInterface;

class ProductPdf extends Pdf
{
    protected DirectoryList $_dir;

    // store data
    protected string $_pubUrl;

    public function __construct(
        StringUtils $string,
        Escaper $_escaper,
        FilterProvider $filterProvider,
        StoreManager $storeManager,
        ScopeConfigInterface $_scopeConfig,
        Data $printHelper,
        Factory $imageFactory,
        LoggerInterface $logger,
        Filesystem $fileSystem,
        DirectoryList $dir,
        array $data = []
    ) {
        parent::__construct(
            $string,
            $_escaper,
            $filterProvider,
            $storeManager,
            $_scopeConfig,
            $printHelper,
            $imageFactory,
            $logger,
            $fileSystem,
            $data
        );
        $this->_dir = $dir;
    }

    protected function _drawQuickViewBlock($description, &$pdf)
    {
        $html = '';
        if (strlen($description) > 0) {

            $x = new DOMDocument();
            @$x->loadHTML($description); // @ hotfix 410
            /* Magedelight encoding fix */
            //$description = $x->saveXML();
            /* end fix */

            /* 410 override remove title */

            $html .= '<tr><td><span class="attribute-values">'.$description.'</span></td></tr>';
        }

        return $html;
    }

    protected function _drawDescriptionBlock($description, &$pdf)
    {
        // echo "<pre>";print_r($description);
        $filterManager =  $this->_filterprovider->getPageFilter()->filter($description);

        $description = preg_replace('</br>', 'br/', $filterManager);
        $html = '';
        if (strlen($description) > 0) {

            $x = new DOMDocument();
            @$x->loadHTML($description); // @ hotfix 410
            /* Magedelight encoding fix */
            //$description = $x->saveXML();
            /* end fix */
            //echo htmlspecialchars_decode($description); exit;
            $html .= '<table cellpadding="0" cellspacing="0" width="100%"><tr><td style="border-bottom:2px solid #cccccc;"><span class="attribute-label">'.__('Details').'</span></td></tr>';
            $html .= '<tr><td><span class="attribute-values">'.htmlspecialchars_decode($description).'</span></td></tr></table>';
        }

        return $html;
    }

    protected function _getBundleProductOptions($bunbdleOptions, &$pdf)
    {

        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr><td style="border-bottom:2px solid #cccccc;"><span class="attribute-label">'.__('Product Options').'</span></td></tr>';
        $html .= '<tr><td>&nbsp;</td></tr>';
        foreach ($bunbdleOptions as $option) {
            /* 410 override remove additionnal text */

            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<b><span class="option-label">&nbsp;&nbsp;&nbsp;'.$option['default_title'].'</span></b><br />';
            foreach ($option['selections'] as $selection) {

                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="option-selection">'.$selection['title'].'</span>';
                if (strlen($selection['price']) > 0) {

                    $html .= '&nbsp;<span class="option-selection-price">+&nbsp;'.$selection['price'].'</span>';
                }
                $html .= '<br />';
            }

            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</table><br /><br />';

        return $html;
    }

    protected function _getConfigurableOptions($configurableOptions, &$pdf)
    {

        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr><td style="border-bottom:2px solid #cccccc;"><span class="attribute-label">'.__('Configurable Product Options').'</span></td></tr>';
        $html .= '<tr><td>&nbsp;</td></tr>';
        foreach ($configurableOptions as $option) {
            /* 410 override remove additional text */

            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<b><span class="option-label">&nbsp;&nbsp;&nbsp;'.$option['label'].'</span></b><br />';
            foreach ($option['values'] as $selection) {

                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="option-selection">'.$selection['label'].'</span>';
                if (isset($selection['pricing_value']) && $selection['pricing_value'] > 0) {

                    $html .= '&nbsp;<span class="option-selection-price">+&nbsp;'.$this->_coreHelper->formatPrice($selection['pricing_value'], false).'</span>';
                }
                $html .= '<br />';
            }

            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</table><br /><br />';

        return $html;
    }

    protected function _getCustomOptions($customOptions, &$pdf)
    {

        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr><td style="border-bottom:2px solid #cccccc;"><span class="attribute-label">'.__('Product Options').'</span></td></tr>';
        $html .= '<tr><td>&nbsp;</td></tr>';
        foreach ($customOptions as $option) {
            /* 410 override remove additional text */

            $title = (isset($option['store_title']) && $option['store_title'] != '') ? $option['store_title']: $option['default_title'];
            $price = (isset($option['store_price']) && $option['store_price'] != '') ? $option['store_price']: $option['price'];
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<b><span class="option-label">&nbsp;&nbsp;&nbsp;'.$title.'</span></b>';
            if (strlen($price) > 0) {

                $html .= '&nbsp;<span class="option-selection-price">+&nbsp;'.$price.'</span>';
            }

            $html .= '<br />';
            if (isset($option['option_values'])) {

                foreach ($option['option_values'] as $selection) {

                    $title = (isset($selection['store_title']) && $selection['store_title'] != '') ? $selection['store_title']: $selection['default_title'];
                    $price = (isset($selection['store_price']) && $selection['store_price'] != '') ? $selection['store_price']: $selection['default_price'];
                    $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="option-selection">'.$title.'</span>';
                    if (strlen($price) > 0) {

                        $html .= '&nbsp;<span class="option-selection-price">+&nbsp;'.$price.'</span>';
                    }
                    $html .= '<br />';
                }
            }
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</table><br /><br />';

        return $html;
    }

    public function drawProducts($collection, &$pdf, $bookmarkCategory = null, $bookmarkAsFirstLevel = true)
    {
        $collection['base_image'] = $this->_fixImageUrl($collection['base_image']);

        parent::drawProducts($collection, $pdf, $bookmarkCategory, $bookmarkAsFirstLevel);
    }

    protected function _drawMediaGalleryBox($mediaImages, &$pdf)
    {

        $mediaHtml = '';
        $counter = 0;
        if (count($mediaImages) > 0) {

            $mediaHtml .= '<tr>';
            $mediaHtml .= '<td align="left">&nbsp;';

            foreach ($mediaImages as $idx => $mediaG) {
                /* 410 override */
                $mediaG = $this->_fixImageUrl($mediaG);

                if ($counter > 3) {
                    $mediaHtml .= '</td></tr><tr><td>&nbsp;';
                    $counter = 0;
                }
                $mediaHtml .= '<img src="'.$mediaG.'" height="75" width="75" style="border:1px solid #c1c1c1;margin-right:5px;" />&nbsp;&nbsp;';
                $counter++;
            }
            $mediaHtml .= '</td>';
            $mediaHtml .= '</tr>';
        }

        return $mediaHtml;
    }

    // 410 utils
    protected function _getPubUrl() : string
    {
        if (!isset($this->_pubUrl)) {
            try {
                $this->_pubUrl = $this->_dir->getPath('pub');
            } catch (FileSystemException $e) {
                $this->_pubUrl = '';
            }
        }

        return $this->_pubUrl;
    }

    protected function _fixImageUrl(string $url) : string
    {
        // Fix image Magedelight (improved)
        $pubPath = $this->_getPubUrl();
        if (strpos($url, $pubPath) !== false) {
            $url = str_replace($pubPath, '', $url);
        }

        return $url;
    }
}
