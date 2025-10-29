<?php
namespace DevExpress\Logify\Collectors;

use DevExpress\Logify\Core\iCollector;
use DevExpress\Logify\Core\Breadcrumb;

class BreadcrumbsCollector implements iCollector {
    
    public $breadcrumbs = null;
    
    function __construct($breadcrumbs) {
        $this->breadcrumbs = $breadcrumbs;
    }
    #region iCollector Members
    function DataName() {
        return 'breadcrumbs';
    }
    function CollectData() {
        $result = array();
        foreach ($this->breadcrumbs as $breadcrumb) {
            if ($breadcrumb instanceof Breadcrumb) {
                $result[] = $breadcrumb->GetBreadcrumbData();
            }
        }
        return $result;
    }
    #endregion
}
?>