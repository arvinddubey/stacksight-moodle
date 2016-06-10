# stacksight-moodle
Integrate moodle with stacksight
This plugin directory name must be "stacksight"
Place this plugin inside MOODLE_ROOT/local directory
Add below line most end of the MOODLE_ROOT/config.php file.
require_once $CFG->dirroot.'/local/stacksight/stacksight-php-sdk/bootstrap-moodle.php';