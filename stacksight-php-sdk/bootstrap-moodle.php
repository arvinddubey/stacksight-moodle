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

        if ($this->stackSightConfig->accesstoken) {
            $this->ready = true;
        }
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
        }
    }

}

$moodleStackSight = new MoodleBootstrap();
$moodleStackSight->init();