<?php
namespace DevExpress\Logify;

use DevExpress\Logify\Collectors\ReportCollector;
use LogifyAlert;
use DevExpress\Logify\Core\ReportSender;
use DevExpress\Logify\Core\BreadcrumbCollection;

class LogifyAlertClient {

    #region static
    public static function get_instance() {
        if (!array_key_exists('LogifyAlertClient', $GLOBALS)) {
            $GLOBALS['LogifyAlertClient'] = new LogifyAlertClient();
        }
        return $GLOBALS['LogifyAlertClient'];
    }
    #endregion
    #region handlers
    private $beforeReportException = null;
    private $afterReportException = null;
    private $canReportException = null;
    #endregion
    #region Properties
    public $apiKey;
    public $appName;
    public $appVersion;
    public $attachments = null;
    public $customData = null;
    public $tags = null;
    public $userId;
    
    public $ignoreKeyPattern = null;
    public $ignoreGetBody = null;
    public $ignorePostBody = null;
    public $ignoreCookies = null;
    public $ignoreFilesBody = null;
    public $ignoreEnvironmentBody = null;
    public $ignoreRequestBody = null;
    public $ignoreServerVariables = null;

    public $pathToConfigFile = null;
    public $serviceUrl;
    public $collectExtensions = null;
    public $breadcrumbsMaxCount = null;
    public $offlineReportsCount = null;
    public $offlineReportsDirectory = '';
    public $offlineReportsEnabled = null;
    public $breadcrumbs = null;
    
    protected $sender = null;
    
    private $globalVariablesPermissions = array();
    #endregion
    
    public function __construct() {
        $this->breadcrumbs = new BreadcrumbCollection();
    }
    
    public function send($exception, $customData = null, $attachments = null) {
        $response = 0;
        $canReportException = $this->canReportException === null ? true : call_user_func($this->canReportException, $exception);
        if ($canReportException) {
            $this->rise_before_report_exception_callback();
            $this->configure();
            $this->create_report_sender();
            $report = $this->get_report_collector($exception, $customData, $attachments);
            $response = $this->sender->send($report->CollectData());
            $this->rise_after_report_exception_callback($response);
            return $response;
        }
        return $response;
    }
    public function send_offline_reports() {
        $this->configure();
        $this->create_report_sender();
        $this->sender->send_offline_reports();
    }
    #region Exception Handlers
    public function start_exceptions_handling() {
        set_exception_handler(array($this, 'exception_handler'));
        set_error_handler(array($this, 'error_handler'));
    }
    public function stop_exceptions_handling() {
        restore_exception_handler();
        restore_error_handler();
    }
    public function error_handler($severity, $message, $file, $line) {
        if (!(error_reporting() & $severity)) {
            return;
        }
        $this->send(new \ErrorException($message, 0, $severity, $file, $line));
    }
    public function exception_handler($exception) {
        $this->send($exception);
    }
    #endregion
    #region Configure
    protected function configure() {
        if ($this->pathToConfigFile == null || !file_exists($this->pathToConfigFile)) {
            return;
        }
        $included = include_once($this->pathToConfigFile);
        if (!$included) {
            return;
        }
        $configs = new LogifyAlert();
        if (property_exists($configs, 'settings')) {
            $this->configureSettings($configs->settings);
        }
        $this->configureProperties($configs);
        $this->configureGlobalVariablesPermissions($configs);
    }
    protected function get_report_collector($exception, $customData = null, $attachments = null) {
        $report = new ReportCollector($exception, $this->globalVariablesPermissions, $this->collectExtensions, $this->userId, $this->appName, $this->appVersion);
        $report->AddCustomData($customData !== null ? $customData : $this->customData);
        $report->AddTags($this->tags);
        $report->AddAttachments($attachments !== null ? $attachments : $this->attachments);
        $report->AddBreadcrumbs($this->breadcrumbs);
        return $report;
    }
    private function configureSettings($settings) {
        if ($settings === null) {
            return;
        }
        if (empty($this->apiKey) && key_exists('apiKey', $settings)) {
            $this->apiKey = $settings['apiKey'];
        }
        if (empty($this->serviceUrl) && key_exists('serviceUrl', $settings)) {
            $this->serviceUrl = $settings['serviceUrl'];
        }
        if (empty($this->userId) && key_exists('userId', $settings)) {
            $this->userId = $settings['userId'];
        }
        if (empty($this->appName) && key_exists('appName', $settings)) {
            $this->appName = $settings['appName'];
        }
        if (empty($this->appVersion) && key_exists('appVersion', $settings)) {
            $this->appVersion = $settings['appVersion'];
        }
    }
    private function configureProperties($configs) {
        if ($this->collectExtensions === null && property_exists($configs, 'collectExtensions') && $configs->collectExtensions !== null) {
            $this->collectExtensions = $configs->collectExtensions;
        }
        if ($this->offlineReportsCount === null && property_exists($configs, 'offlineReportsCount') && $configs->offlineReportsCount !== null) {
            $this->offlineReportsCount = $configs->offlineReportsCount;
        }
        if (empty($this->offlineReportsDirectory) && property_exists($configs, 'offlineReportsDirectory')) {
            $this->offlineReportsDirectory = $configs->offlineReportsDirectory;
        }
        if ($this->offlineReportsEnabled === null && property_exists($configs, 'offlineReportsEnabled') && $configs->offlineReportsEnabled !== null) {
            $this->offlineReportsEnabled = $configs->offlineReportsEnabled;
        }
        if ($this->breadcrumbsMaxCount === null && property_exists($configs, 'breadcrumbsMaxCount') && $configs->breadcrumbsMaxCount !== null) {
            $this->breadcrumbsMaxCount = $configs->breadcrumbsMaxCount;
            if($this->breadcrumbs == null){
                $this->breadcrumbs = new BreadcrumbCollection($this->breadcrumbsMaxCount);
            }
        }
    }
    private function configureGlobalVariablesPermissions($configs) {
        if (!is_array($this->globalVariablesPermissions)) {
            $this->globalVariablesPermissions = array();
        }
        $this->globalVariablesPermissions['get'] = $this->getActualIgnoreValue('ignoreGetBody', $configs) != true;
        $this->globalVariablesPermissions['post'] = $this->getActualIgnoreValue('ignorePostBody', $configs) != true;
        $this->globalVariablesPermissions['cookie'] = $this->getActualIgnoreValue('ignoreCookies', $configs) != true;
        $this->globalVariablesPermissions['files'] = $this->getActualIgnoreValue('ignoreFilesBody', $configs) != true;
        $this->globalVariablesPermissions['environment'] = $this->getActualIgnoreValue('ignoreEnvironmentBody', $configs) != true;
        $this->globalVariablesPermissions['request'] = $this->getActualIgnoreValue('ignoreRequestBody', $configs) != true;
        $this->globalVariablesPermissions['server'] = $this->getActualIgnoreValue('ignoreServerVariables', $configs) != true;
        
        $this->globalVariablesPermissions['ignoreKeyPattern'] = $this->getActualIgnoreValue('ignoreKeyPattern', $configs);
    }
    private function getActualIgnoreValue($ignoreName, $configs) {
        $clientIgnore = $this->$ignoreName;
        $configIgnore = property_exists($configs, $ignoreName)? $configs->$ignoreName: null;
        return $clientIgnore != null ? $clientIgnore : $configIgnore;
    }
    #endregion
    #region Sender
    protected function create_report_sender(){
        $this->sender = new ReportSender($this->apiKey, $this->serviceUrl);
    }
    #endregion
    #region CanReportExceptionCallback
    public function set_can_report_exception_callback(callable $canReportExceptionHandler) {
        $this->canReportException = $canReportExceptionHandler;
    }
    #endregion
    #region BeforeReportExceptionCallback
    public function set_before_report_exception_callback(callable $beforeReportExceptionHandler) {
        $this->beforeReportException = $beforeReportExceptionHandler;
    }
    protected function rise_before_report_exception_callback() {
        if ($this->beforeReportException !== null) {
            call_user_func($this->beforeReportException);
        }
    }
    #endregion
    #region AfterReportExceptionCallback
    public function set_after_report_exception_callback(callable $afterReportExceptionHandler) {
        $this->afterReportException = $afterReportExceptionHandler;
    }
    protected function rise_after_report_exception_callback($response) {
        if ($this->afterReportException !== null) {
            call_user_func($this->afterReportException, $response);
        }
    }
    #endregion
}
?>