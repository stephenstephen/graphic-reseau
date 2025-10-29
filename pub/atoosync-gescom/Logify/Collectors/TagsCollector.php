<?php
namespace DevExpress\Logify\Collectors;

use DevExpress\Logify\Core\iCollector;

class TagsCollector implements iCollector {

    public $tags;
    function __construct($tags) {
        $this->tags = $tags;
    }
    #region iCollector Members
    function DataName() {
        return 'tags';
    }
    public function CollectData() {
        return $this->tags;
    }
    #endregion
}
?>