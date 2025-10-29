<?php
/*
 *
 * @copyright Copyright © 2020 410-Gone. All rights reserved.
 * @author    contact@410-gone.fr
 *
 */

namespace Gone\Cloaking\Helper;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Exception;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Gone\Cloaking\Ui\Component\Listing\Column\Cms\Block\CloakingOptions as BlockCloakingOption;

class Replace extends AbstractHelper
{

    public const ALL_LINKS = 1;
    public const WITH_CLASS = 2;
    public const REPLACE_LINK_WITH_JS = 2;
    public const LINK_TO_CLOAK_CLASS = 'quatrecentdix'; //msut be change in css and JS files
    public const STORE_URL_REGEX = "/(\{\{store\ +url=([\'\`\\\"]?)(.*?)([\'\`\\\"]?)\}\})(.*)/si";

    public function isCloakingEnable()
    {
        return $this->scopeConfig->getValue('cloaking/param/enable');
    }

    public function getReplaceSelector($replaceContent)
    {
        $return = null;
        if ($replaceContent == true) {
            $return = self::ALL_LINKS;
        } else {
            $return = self::WITH_CLASS;
        }
        return $return;
    }

    public function getCloakingFromContent($content, $replaceMethod = null)
    {
        $contentCloaked = $content;

        try {
            if (in_array($replaceMethod, [BlockCloakingOption::CLOAK_LINK_BY_CLASS, BlockCloakingOption::CLOAK_LINK_BY_CLASS_NOT_HOME])
                && strpos($content, self::LINK_TO_CLOAK_CLASS) !== false) {
                $dom = new DOMDocument();
                @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                $xpath = new DOMXPath($dom);
                $links = $xpath->query("//a[contains(@class,'" . self::LINK_TO_CLOAK_CLASS . "')]");
                /** @var DOMElement $link */
                foreach ($links as $link) {

                    $replaceTexte = $dom->createElement('span', $link->textContent);
                    $href = $link->getAttribute('href');
                    // replace the {{store url='aaa'}} with the real url forthe store
                    if (preg_match_all(self::STORE_URL_REGEX, $href, $matches, PREG_SET_ORDER)) {

                        if (isset($matches[0][3])) {
                            $href = $this->_getUrl($matches[0][3]);
                            if (isset($matches[0][5])) {
                                $href .= $matches[0][5];
                            }
                        }
                    }

                    $replaceTexte->setAttribute('data-atc', base64_encode($href));

                    $replaceTexte->setAttribute('class', $link->getAttribute('class'));
                    if (!empty($link->getAttribute('id'))) {
                        $replaceTexte->setAttribute('id', $link->getAttribute('id'));
                    }
                    $link->parentNode->replaceChild($replaceTexte, $link);

                }
                $contentCloaked = $dom->saveHTML();

                $contentCloaked = str_replace('%7B', '{', $contentCloaked);
                $contentCloaked = str_replace('%7D', '}', $contentCloaked);
                $contentCloaked = str_replace('%22', "\"", $contentCloaked);
                $contentCloaked = str_replace('%20', ' ', $contentCloaked);

            }
        } catch (Exception $e) {
            //Do nothing
        }


        return $contentCloaked;
    }
}
