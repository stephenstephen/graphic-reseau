<?php
/**
 *   Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 *   See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace Gone\Design\Block\Html;

use Gone\Base\Helper\Utils;
use Gone\Cloaking\Helper\Replace;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\View\Element\Template;

/**
 * Html page top menu block
 *
 * @api
 * @since 100.0.2
 */
class Topmenu extends \Magento\Theme\Block\Html\Topmenu
{

    protected $_isHomePage;
    protected $_cloakingReplaceHelper;
    protected $_baseUtilsHelper;

    public function __construct(
        Template\Context $context,
        NodeFactory $nodeFactory,
        TreeFactory $treeFactory,
        Replace $cloakingReplaceHelper,
        Utils $baseUtilsHelper,
        array $data = []
    )
    {
        $this->_cloakingReplaceHelper = $cloakingReplaceHelper;
        $this->_baseUtilsHelper = $baseUtilsHelper;
        parent::__construct(
            $context,
            $nodeFactory,
            $treeFactory,
            $data
        );
    }

    /**
     * Add identity
     *
     * @param string|array $identity
     * @return void
     */
    public function addIdentity($identity)
    {
        if (!in_array($identity, $this->identities)) {
            $this->identities[] = $identity;
        }
    }

    /**
     * Get block cache life time
     *
     * @return int
     * @since 100.1.0
     */
    protected function getCacheLifetime()
    {
        return parent::getCacheLifetime() ?: 3600;
    }

    /**
     * Recursively generates top menu html from data that is specified in $menuTree
     *
     * @param Node $menuTree
     * @param string $childrenWrapClass
     * @param int $limit
     * @param array $colBrakes
     * @return string
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _getHtml(
        Node $menuTree,
        $childrenWrapClass,
        $limit,
        array $colBrakes = []
    )
    {
        $html = '';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

        $counter = 1;
        $itemPosition = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        /** @var Node $child */
        foreach ($children as $child) {
            if ($childLevel === 0 && $child->getData('is_parent_active') === false) {
                continue;
            }
            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $currentClass = $child->getClass();

                if (empty($currentClass)) {
                    $child->setClass($outermostClass);
                } else {
                    $child->setClass($currentClass . ' ' . $outermostClass);
                }
            }

            if (is_array($colBrakes) && count($colBrakes) && $colBrakes[$counter]['colbrake']) {
                $html .= '</ul></li><li class="column"><ul>';
            }

            $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
            if ($this->isHomePage()) {
                $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
                        $child->getName()
                    ) . '</span></a>' . $this->_addSubMenu(
                        $child,
                        $childLevel,
                        $childrenWrapClass,
                        $limit
                    ) . '</li>';
            } else {

                if (empty($outermostClassCode)) {
                    $outermostClassCode = 'class="clk-link ' . Replace::LINK_TO_CLOAK_CLASS . '"';
                } else {
                    $outermostClassCode = substr(rtrim($outermostClassCode), 0, -1) . ' clk-link ' . Replace::LINK_TO_CLOAK_CLASS . '"';
                }

                $obfcOptions = "";
                if (false !== strpos($this->_getRenderedMenuItemAttributes($child), 'parent') && $child->getLevel() < 2) {
                    $obfcOptions = "data-options='";
                    $obfcOptions .= '{"follow":false}';
                    $obfcOptions .= "'";
                }

                $html .= '<span ' . $outermostClassCode . ' data-atc="' . base64_encode($child->getUrl()) . '" ' . $obfcOptions . '><span>' . $this->escapeHtml(
                        $child->getName()
                    ) . '</span></span>' . $this->_addSubMenu(
                        $child,
                        $childLevel,
                        $childrenWrapClass,
                        $limit
                    ) . '</li>';
            }

            $itemPosition++;
            $counter++;
        }

        if ($childLevel == 2) {
            if ($this->isHomePage()) {
                $html .= "<li><a class='menu-link-see-more' href='" . $menuTree->getUrl() . "'>Voir plus</a> </li>";
            } else {
                $html .= "<li><span class='menu-link-see-more clk-link " . Replace::LINK_TO_CLOAK_CLASS . "'
                data-atc='" . base64_encode($menuTree->getUrl()) . "'>Voir plus</span></li>";
            }
        }

        if (is_array($colBrakes) && count($colBrakes) && $limit) {
            $html = '<li class="column"><ul>' . $html . '</ul></li>';
        }

        return $html;
    }

    public function isHomePage()
    {
        if (!isset($this->_isHomePage)) {
            $this->_isHomePage = $this->_baseUtilsHelper->isHomePage();
        }
        return $this->_isHomePage;
    }

    /**
     * Get tags array for saving cache
     *
     * @return array
     * @since 100.1.0
     */
    protected function getCacheTags()
    {
        return array_merge(parent::getCacheTags(), $this->getIdentities());
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return $this->identities;
    }
}
