<?php
namespace DevExpress\Logify\Collectors;

use DevExpress\Logify\Core\iCollector;
use DevExpress\Logify\Core\BreadcrumbCollection;

class ReportCollector implements iCollector {

    private $collectors = array();
    function __construct($exeption, $globalVariablesPermissions, $collectExtensions, $userId, $appName, $appVersion) {
        $this->collectors[] = new ProtocolVersionCollector();
        $this->collectors[] = new DateTimeCollector();
        $this->collectors[] = new LogifyAppCollector($appName, $appVersion, $userId);
        $this->collectors[] = ExceptionCollector::GetInstance($exeption);
        if ($collectExtensions === true) {
            $this->collectors[] = new ExtensionsCollector();
        }
        $this->collectors[] = new GlobalVariablesCollector($globalVariablesPermissions);
        $this->collectors[] = new OSCollector();
        $this->collectors[] = new MemoryCollector();
        $this->collectors[] = new DevPlatformCollector();
        $this->collectors[] = new UserAgentCollector();
        $this->collectors[] = new PHPVersionCollector();
    }
    #region iCollector Members
    function DataName() {
        return '';
    }
    function CollectData() {
        $result = array();
        foreach ($this->collectors as $collector) {
            $currentData = $collector->CollectData();
            if (is_string($currentData) || (is_array($currentData) && count($currentData) > 0 )) {
                $result[$collector->DataName()] = $currentData;
            }
        }
        return $result;
    }
    #endregion
    function AddCustomData($customData) {
        if ($customData === null || count($customData) === 0) {
            return;
        }
        $this->collectors[] = new CustomDataCollector($customData);
    }
    function AddTags($tags) {
        if ($tags === null || count($tags) === 0) {
            return;
        }
        $this->collectors[] = new TagsCollector($tags);
    }
    function AddAttachments($attachments) {
        if ($attachments === null || count($attachments) === 0) {
            return;
        }
        $this->collectors[] = new AttachmentsCollector($attachments);
    }
    function AddBreadcrumbs($breadcrumbs) {
        $breadcrumbsArray = null;
        if($breadcrumbs instanceof BreadcrumbCollection){
            $breadcrumbsArray = $breadcrumbs->get();
        }
        if ($breadcrumbsArray === null || count($breadcrumbsArray) === 0) {
            return;
        }
        $this->collectors[] = new BreadcrumbsCollector($breadcrumbsArray);
    }
}
?>