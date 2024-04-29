<?php
// This file is part of the Trema theme for Moodle - https://moodle.org/
//
// Trema is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Trema is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Settings for Profile fields.
 *
 * @package     theme_trema
 * @copyright   2016-2024 TNG Consulting Inc. <https://www.tngconsulting.ca>
 * @author      Michael Milette
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Note: Based on source code from theme_gcweb by TNG Consulting Inc.
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die;

// User Profile Fields tab.
$page = new admin_settingpage('theme_trema_profile', get_string('profile'));

// List of user profile fields sections that we can show or hide.
$profilefieldsections = ['pictureofuser', 'additionalnames', 'interests', 'optional'];

// List of user profile fields that we can show or hide.
$profilefields[] = 'emaildisplay';
$profilefields[] = 'moodlenetprofile';
$profilefields[] = 'city';
$profilefields[] = 'country';
$profilefields[] = 'timezone';
$profilefields[] = 'description';
// User Picture section.
$profilefields[] = 'pictureofuser';
// Additional Names section.
$profilefields[] = 'additionalnames';
// Interests section.
$profilefields[] = 'interests';
// Optional section.
$profilefields[] = 'optional';
$profilefields[] = 'idnumber';
$profilefields[] = 'institution';
$profilefields[] = 'department';
$profilefields[] = 'phone1';
$profilefields[] = 'phone2';
$profilefields[] = 'address';

//
// Show / Hide User Profile fields and sections page heading.
//
$name = 'theme_trema/showprofileeheading';
$title = '';
$description = get_string('showprofile_heading', 'theme_trema');
$setting = new admin_setting_heading($name, $title, $description);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_trema/showprofileeheading_general';
$title = get_string('section') . ' : ' . get_string('general');
$description = '';
$setting = new admin_setting_heading($name, $title, $description);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Create list of profile fields.
$default = 1;
foreach ($profilefields as $field) {
    if (in_array($field, $profilefieldsections)) {
        // Add profile field section header.
        $name = 'theme_trema/showprofileeheading_' . $field;
        $description = '';
        $title = get_string('section') . ' : ' . get_string($field);
        $setting = new admin_setting_heading($name, $title, $description);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        $description = get_string('showprofilesection_desc', 'theme_trema');
    } else {
        $description = '';
    }
    $name = 'theme_trema/showprofile' . $field;
    $title = get_string($field, ($field != 'moodlenetprofile' ? 'moodle' : 'user'));
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
}

// Add the page after definiting all the settings!
$settings->add($page);
