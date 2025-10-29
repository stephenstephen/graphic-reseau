<?php
namespace DevExpress\Logify\Collectors;

use DevExpress\Logify\Core\iCollector;
use DevExpress\Logify\Core\iVariables;

class VariablesCollector implements iCollector, iVariables {

    private $name;
    private $variables;
    private $ignoreKeyPattern;
    
    function __construct($name, $variables, $ignoreKeyPattern) {
        $this->name = $name;
        $this->variables = $variables;
        $this->ignoreKeyPattern = $ignoreKeyPattern;
    }
    #region iCollector Members
    function DataName() {
        return $this->name;
    }
    function CollectData() {
        $result = array();
        foreach ($this->variables as $key => $value) {
            $match = null;
            if($this->ignoreKeyPattern != null && $this->ignoreKeyPattern != ''){
                $match = preg_match($this->ignoreKeyPattern, $key);
            }
            if ($match != 1) {
                $result[$key] = $value;
            }
        }
        return $result;
    }
    #endregion
    #region iVariables Members
    function HaveData() {
        return !empty($this->variables);
    }
    #endregion
}
?>