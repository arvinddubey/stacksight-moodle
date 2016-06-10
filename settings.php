<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Link to CSV course upload.
 *
 * @package    tool_uploadcourse
 * @copyright  2011 Piers Harding
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
      
    $settings = new admin_settingpage('local_stacksight', get_string('pluginadministration', 'local_stacksight'));
    $ADMIN->add('localplugins', $settings);

    //stacksight configuration settings
    $settings->add(new admin_setting_heading('stacksight/settings', get_string('settings', 'local_stacksight'), ''));

    $settings->add(new admin_setting_configtext('stacksight/accesstoken', get_string('accesstoken', 'local_stacksight'), get_string('accesstokendesc', 'local_stacksight'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('stacksight/appgroup', get_string('appgroup', 'local_stacksight'), get_string('appgroupdesc', 'local_stacksight'), '', PARAM_TEXT));

    //Included features 
    $settings->add(new admin_setting_heading('stacksight/features', get_string('features', 'local_stacksight'), ''));
    $options = $DB->get_records_sql("SELECT * FROM {clks_course_type}");

    $settings->add(new admin_setting_configcheckbox('stacksight/includelogs', get_string('includelogs', 'local_stacksight'), get_string('includelogsdesc', 'local_stacksight'), '0'));

    $settings->add(new admin_setting_configcheckbox('stacksight/includehealth', get_string('includehealth', 'local_stacksight'), get_string('includehealthdesc', 'local_stacksight'), '0'));

    $settings->add(new admin_setting_configcheckbox('stacksight/includeinventory', get_string('includeinventory', 'local_stacksight'), get_string('includeinventorydesc', 'local_stacksight'), '0'));

    $settings->add(new admin_setting_configcheckbox('stacksight/includeevents', get_string('includeevents', 'local_stacksight'), get_string('includeeventsdesc', 'local_stacksight'), '0'));
    
    $settings->add(new admin_setting_configcheckbox('stacksight/includeupdates', get_string('includeupdates', 'local_stacksight'), get_string('includeupdatesdesc', 'local_stacksight'), '0'));
}