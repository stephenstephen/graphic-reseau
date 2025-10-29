<?php
/**
 * Magedelight
 * Copyright (C) 2014  Magedelight <info@magedelight.com>
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://opensource.org/licenses/gpl-3.0.html.
 *
 * @category   MD
 * @package    MD_Productpdf
 * @copyright  Copyright (c) 2014 Mage Delight (http://www.magedelight.com/)
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3 (GPL-3.0)
 * @author     Magedelight <info@magedelight.com>
 */

namespace Magedelight\Productpdf\Model\Product;

define('K_TCPDF_EXTERNAL_CONFIG', true);
//define('DS', DIRECTORY_SEPARATOR);
if (!defined('K_PATH_IMAGES')) {
    define('K_PATH_IMAGES', 'value');
}
if (!defined('PDF_HEADER_STRING')) {
    define('PDF_HEADER_STRING', 'value');
}
if (!defined('PDF_HEADER_LOGO')) {
    define('PDF_HEADER_LOGO', 'tcpdf_logo.jpg');
}
if (!defined('K_PATH_CACHE')) {
    define('K_PATH_CACHE', sys_get_temp_dir().'/');
}
if (!defined('K_BLANK_IMAGE')) {
    define('K_BLANK_IMAGE', '_blank.png');
}
if (!defined('PDF_PAGE_FORMAT')) {
    define('PDF_PAGE_FORMAT', 'A4');
}
if (!defined('PDF_PAGE_ORIENTATION')) {
    define('PDF_PAGE_ORIENTATION', 'P');
}
if (!defined('PDF_CREATOR')) {
    define('PDF_CREATOR', 'TCPDF');
}
if (!defined('PDF_AUTHOR')) {
    define('PDF_AUTHOR', 'TCPDF');
}
if (!defined('PDF_HEADER_TITLE')) {
    define('PDF_HEADER_TITLE', '');
}
if (!defined('PDF_UNIT')) {
    define('PDF_UNIT', 'mm');
}
if (!defined('PDF_MARGIN_HEADER')) {
    define('PDF_MARGIN_HEADER', 5);
}
if (!defined('PDF_MARGIN_FOOTER')) {
    define('PDF_MARGIN_FOOTER', 5);
}
if (!defined('PDF_MARGIN_TOP')) {
    define('PDF_MARGIN_TOP', 5);
}
if (!defined('PDF_MARGIN_BOTTOM')) {
    define('PDF_MARGIN_BOTTOM', 10);
}
if (!defined('PDF_MARGIN_LEFT')) {
    define('PDF_MARGIN_LEFT', 5);
}
if (!defined('PDF_MARGIN_RIGHT')) {
    define('PDF_MARGIN_RIGHT', 5);
}
if (!defined('PDF_FONT_NAME_MAIN')) {
    define('PDF_FONT_NAME_MAIN', 'kozminproregular');
}
if (!defined('PDF_FONT_SIZE_MAIN')) {
    define('PDF_FONT_SIZE_MAIN', 10);
}
if (!defined('PDF_FONT_SIZE_DATA')) {
    define('PDF_FONT_SIZE_DATA', 8);
}
/*if (!defined('PDF_FONT_MONOSPACED')) {
    define('PDF_FONT_MONOSPACED', 'courier');
}*/
if (!defined('PDF_IMAGE_SCALE_RATIO')) {
    define('PDF_IMAGE_SCALE_RATIO', 1.25);
}
if (!defined('HEAD_MAGNIFICATION')) {
    define('HEAD_MAGNIFICATION', 1.1);
}
if (!defined('K_CELL_HEIGHT_RATIO')) {
    define('K_CELL_HEIGHT_RATIO', 1.25);
}
if (!defined('K_TITLE_MAGNIFICATION')) {
    define('K_TITLE_MAGNIFICATION', 1.3);
}
if (!defined('K_SMALL_RATIO')) {
    define('K_SMALL_RATIO', 2/3);
}
if (!defined('K_THAI_TOPCHARS')) {
    define('K_THAI_TOPCHARS', true);
}
if (!defined('K_TCPDF_CALLS_IN_HTML')) {
    define('K_TCPDF_CALLS_IN_HTML', true);
}
if (!defined('K_TCPDF_THROW_EXCEPTION_ERROR')) {
    define('K_TCPDF_THROW_EXCEPTION_ERROR', false);
}

class MYPDF extends \TCPDF
{
    protected $_coverpage = false;
    protected $_scopeConfig;
    protected $_storeObject;
    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false, $coverPage = false, \Magento\Store\Model\Store $store, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        /* Set internal character encoding to ASCII */
        $this->_coverpage = $coverPage;
                $this->_storeObject = $store;
                $this->_scopeConfig = $scopeConfig;
        if (function_exists('mb_internal_encoding') and mb_internal_encoding()) {
            $this->internal_encoding = mb_internal_encoding();
            mb_internal_encoding('ASCII');
        }
        // set file ID for trailer
        $serformat = (is_array($format) ? json_encode($format) : $format);
       // $this->file_id = hash('sha256', \TCPDF_STATIC::getRandomSeed('TCPDF'.$orientation.$unit.$serformat.$encoding));
        $this->font_obj_ids = [];
        $this->page_obj_id = [];
        $this->form_obj_id = [];
        // set pdf/a mode
        $this->pdfa_mode = $pdfa;
        $this->force_srgb = false;
        // set disk caching
        $this->diskcache = $diskcache ? true : false;
        // set language direction
        $this->rtl = false;
        $this->tmprtl = false;
        // some checks
        $this->_dochecks();
        // initialization of properties
        $this->isunicode = $unicode;
        $this->page = 0;
        $this->transfmrk[0] = [];
        $this->pagedim = [];
        $this->n = 2;
        $this->buffer = '';
        $this->pages = [];
        $this->state = 0;
        $this->fonts = [];
        $this->FontFiles = [];
        $this->diffs = [];
        $this->images = [];
        $this->links = [];
        $this->gradients = [];
        $this->InFooter = false;
        $this->lasth = 0;
        $this->FontFamily = defined('PDF_FONT_NAME_MAIN')?PDF_FONT_NAME_MAIN:'kozminproregular';
        $this->FontStyle = '';
        $this->FontSizePt = 12;
        $this->underline = false;
        $this->overline = false;
        $this->linethrough = false;
        $this->DrawColor = '0 G';
        $this->FillColor = '0 g';
        $this->TextColor = '0 g';
        $this->ColorFlag = false;
        $this->pdflayers = [];
        // encryption values
        $this->encrypted = false;
        $this->last_enc_key = '';
        // standard Unicode fonts
        $this->CoreFonts = [
            'courier'=>'Courier',
            'courierB'=>'Courier-Bold',
            'courierI'=>'Courier-Oblique',
            'courierBI'=>'Courier-BoldOblique',
            'helvetica'=>'Helvetica',
            'helveticaB'=>'Helvetica-Bold',
            'helveticaI'=>'Helvetica-Oblique',
            'helveticaBI'=>'Helvetica-BoldOblique',
            'times'=>'Times-Roman',
            'timesB'=>'Times-Bold',
            'timesI'=>'Times-Italic',
            'timesBI'=>'Times-BoldItalic',
            'symbol'=>'Symbol',
            'zapfdingbats'=>'ZapfDingbats'
        ];
        // set scale factor
        $this->setPageUnit($unit);
        // set page format and orientation
        $this->setPageFormat($format, $orientation);
        // page margins (1 cm)
        $margin = 28.35 / $this->k;
        $this->SetMargins($margin, $margin);
        $this->clMargin = $this->lMargin;
        $this->crMargin = $this->rMargin;
        // internal cell padding
        $cpadding = $margin / 10;
        $this->setCellPaddings($cpadding, 0, $cpadding, 0);
        // cell margins
        $this->setCellMargins(0, 0, 0, 0);
        // line width (0.2 mm)
        $this->LineWidth = 0.57 / $this->k;
        $this->linestyleWidth = sprintf('%F w', ($this->LineWidth * $this->k));
        $this->linestyleCap = '0 J';
        $this->linestyleJoin = '0 j';
        $this->linestyleDash = '[] 0 d';
        // automatic page break
        $this->SetAutoPageBreak(true, (2 * $margin));
        // full width display mode
        $this->SetDisplayMode('fullwidth');
        // compression
        $this->SetCompression();
        // set default PDF version number
        $this->setPDFVersion();
        $this->tcpdflink = true;
        $this->encoding = $encoding;
        $this->HREF = [];
        $this->getFontsList();
        $this->fgcolor = ['R' => 0, 'G' => 0, 'B' => 0];
        $this->strokecolor = ['R' => 0, 'G' => 0, 'B' => 0];
        $this->bgcolor = ['R' => 255, 'G' => 255, 'B' => 255];
        $this->extgstates = [];
        $this->setTextShadow();
        // signature
        $this->sign = false;
        $this->tsa_timestamp = false;
        $this->tsa_data = [];
        $this->signature_appearance = ['page' => 1, 'rect' => '0 0 0 0', 'name' => 'Signature'];
        $this->empty_signature_appearance = [];
        // user's rights
        $this->ur['enabled'] = false;
        $this->ur['document'] = '/FullSave';
        $this->ur['annots'] = '/Create/Delete/Modify/Copy/Import/Export';
        $this->ur['form'] = '/Add/Delete/FillIn/Import/Export/SubmitStandalone/SpawnTemplate';
        $this->ur['signature'] = '/Modify';
        $this->ur['ef'] = '/Create/Delete/Modify/Import';
        $this->ur['formex'] = '';
        // set default JPEG quality
        $this->jpeg_quality = 75;
        // initialize some settings
        //\TCPDF_FONTS::utf8Bidi([''], '', false, $this->isunicode, $this->CurrentFont);
        // set default font
        $this->SetFont('kozminproregular', '', 9);
        $this->setHeaderFont('kozminproregular', '', 9);
        $this->setFooterFont('kozminproregular', '', 9);
        
        // check if PCRE Unicode support is enabled
        if ($this->isunicode and (preg_match('/\pL/u', 'a') == 1)) {

            $this->setSpacesRE('/(?!\xa0)[\s\p{Z}]/u');
        } else {
            // PCRE unicode support is turned OFF
            $this->setSpacesRE('/[^\S\xa0]/');
        }
        $this->default_form_prop = ['lineWidth'=>1, 'borderStyle'=>'solid', 'fillColor'=>[255, 255, 255], 'strokeColor'=>[128, 128, 128]];
        // set document creation and modification timestamp
        $this->doc_creation_timestamp = time();
        $this->doc_modification_timestamp = $this->doc_creation_timestamp;
        // get default graphic vars
        $this->default_graphic_vars = $this->getGraphicVars();
        $this->header_xobj_autoreset = false;
        $this->custom_xmp = '';
    }
    public function Footer()
    {
        
        $bgImagePath = $this->_storeObject->getBaseMediaDir().'/theme/productpdf/cover_page/'.$this->_scopeConfig->getValue('md_productpdf/general/cover_image');
        if ($this->_coverpage && $this->PageNo() == 1 && is_file($bgImagePath) && $this->_scopeConfig->getValue('md_productpdf/general/cover_image')) {
            return;
        }
        $regularFontName = 'kozminproregular';
        $boldFontName = 'kozminproregular';
        /*$regularFontName = \TCPDF_FONTS::addTTFfont($regularFontFile, 'TrueTypeUnicode', '', 32);
        $boldFontName = \TCPDF_FONTS::addTTFfont($boldFontFile, 'TrueTypeUnicode', '', 32);*/
        $this->SetFont('kozminproregular', '', 10);
        $text = $this->_scopeConfig->getValue('md_productpdf/header_footer/pdf_footer_text');
        $this->SetTextColorArray($this->footer_text_color);
        //set style for cell border
        $line_width = (0.85 / $this->k);
        
                //print document barcode
                //$pagenumtxt = $this->getAliasNumPage().' / '.$this->getAliasNbPages();
        
                $this->Line(5, $this->getPageHeight() - 12, $this->getPageWidth()-5, $this->getPageHeight() - 12, ['width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $this->footer_line_color]);
        $this->SetY($this->getPageHeight() - 10);
        $html = '
   <table width="100%" border="0" cellpadding="0" cellspacing="0">
       <tr>
           <td width="90%" align="left"><span style="font-family:'.$regularFontName.';text-align:justify;font-size:10px;font-weight:normal;">'.$text.'</span></td>
           <td width="10%" align="right" style="font-family:'.$boldFontName.';text-align:right;font-size:8px;font-weight:bold;">'.$this->getAliasNumPage().'</td>
       </tr>
       
   </table>';

        //Print page number
        if ($this->getRTL()) {
            $this->SetX($this->original_rMargin);
            $this->writeHTML($html, true, false, true, false, '');
        } else {
            $this->SetX($this->original_lMargin);
            $this->writeHTML($html, true, false, true, false, '');
        }
    }
}
