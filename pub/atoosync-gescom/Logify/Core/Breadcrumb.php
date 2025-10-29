<?php
namespace DevExpress\Logify\Core;
class Breadcrumb{
    public $dateTime;
    public $level;
    public $event;
    public $category;
    public $message;
    public $className;
    public $methodName;
    public $line = 0;
    public $customData;
    
    function __construct($dateTime = null){
        if($dateTime != NULL){
        $this->dateTime = $dateTime;
        } else {
            $this->dateTime = date_create('UTC')->format('Y-m-d H:i:s');
        }
    }
    function GetBreadcrumbData() {
        $result = array();
        $result['dateTime'] = $this->dateTime;
        $result['level'] = $this->level;
        $result['event'] = $this->event;
        $result['category'] = $this->category;
        $result['message'] = $this->message;
        $result['className'] = $this->className;
        $result['methodName'] = $this->methodName;
        $result['line'] = $this->line;
        $result['customData'] = $this->customData;
        $result['a'] = FALSE;
        return $result;
    }    
}
?>