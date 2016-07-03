<?php
require_once('SSClientBase.php');
require_once('SSHttpRequest.php');
require_once('requests/SSHttpInterface.php');
require_once('requests/SSHttpRequestCurl.php');
require_once('requests/SSHttpRequestMultiCurl.php');
require_once('requests/SSHttpRequestSockets.php');
require_once('requests/SSHttpRequestThread.php');
require_once('SSLogsTracker.php');
require_once('SSUtilities.php');
require_once('platforms/SSMoodleClient.php');

global $ss_client;

class MoodleBootstrap {

    private $ready = false;
    private $stackSightConfig = false;
    protected $ss_client;

    public function __construct() {
        global $ss_client, $DB;
        $this->ss_client = & $ss_client;

        $this->stackSightConfig = get_config('stacksight');
         
        if (!empty($this->stackSightConfig) && isset($this->stackSightConfig->accesstoken)) {
            $this->ready = true;
        }
        define('STACKSIGHT_DEBUG', true);
        define('STACKSIGHT_DEBUG_MODE', true);
    }

    public function init() {
        if ($this->ready == true) {
            if ($this->stackSightConfig->appgroup) {
                define('STACKSIGHT_GROUP', $this->stackSightConfig->appgroup);
            }
            $this->ss_client = new SSMoodleClient($this->stackSightConfig->accesstoken, SSClientBase::PLATFORM_MOODLE);

            $handle_errors = TRUE;
            $handle_fatal_errors = TRUE;

            if ($this->stackSightConfig->includelogs) {
                new SSLogsTracker($this->ss_client, $handle_errors, $handle_fatal_errors);
            }
            
            if($this->stackSightConfig->includeupdates) {
                $this->ss_client->sendUpdates(array('data' => $this->getPluginUpdateInfo()));
            }
        }
    }
    
    private function getPluginUpdateInfo (){
        global $CFG;
        require_once($CFG->dirroot . '/lib/classes/plugin_manager.php');
        $pluginMan = core_plugin_manager::instance();
        $plugins = $pluginMan->get_plugins();
        $upd = array();
        if ($plugins) {
            foreach ($plugins as $key => $plugin) {
                if ($plugin) {
                    foreach ($plugin as $pluginname => $details) {
                        $type = 5; //no update available
                        $updateInfo = '';
                        if (isset($details->availableupdates) && !empty($details->availableupdates)) {
                            $type = 1; //update is available
                            $updateInfo = array_pop($details->availableupdates);
                        }
                        $upd[] = array(
                            'title' => $details->displayname,
                            'release_ts' => strtotime(substr($details->versiondb, 0, 4) . '-' . substr($details->versiondb, 4, 2) . '-' . substr($details->versiondb, 6, 2)),
                            'current_version' => $details->versiondb,
                            'latest_version' => empty($updateInfo) ? $details->versiondb : $updateInfo->version,
                            'type' => $key,
                            'status' => $type,
                            'link' => false,
                            'release_link' => !empty($updateInfo) && isset($updateInfo->url) ? $updateInfo->url : false,
                            'download_link' => !empty($updateInfo) && isset($updateInfo->download) ? $updateInfo->download : false,
                        );
                    }
                }
            }
        }
        return $upd;
    }
    
}

$moodleStackSight = new MoodleBootstrap();
$moodleStackSight->init();
