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
 * General settings
 *
 * @package     theme_trema
 * @copyright   2019-2024 Trema - {@link https://trema.tech/}
 * @copyright   2023-2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_trema_general', get_string('general'));

// Unaddable blocks. Exclude these blocks from the "Add a block" list: Administration, Navigation, Courses and Section links.
$name = 'theme_trema/unaddableblocks';
$title = get_string('unaddableblocks', 'theme_boost');
$description = get_string('unaddableblocks_desc', 'theme_boost');
$default = 'navigation,settings,course_list,section_links';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Background image setting.
$name = 'theme_trema/backgroundimage';
$title = get_string('backgroundimage', 'theme_boost');
$description = get_string('backgroundimage_desc', 'theme_boost');
$setting = new admin_setting_configstoredfile($name, $title, $description, 'backgroundimage');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Links.
$name = 'theme_trema/linkdecoration';
$title = get_string('linkdecoration', 'theme_trema');
$description = get_string('linkdecoration_desc', 'theme_trema');
$default = 'underline';
$choices = ["none" => get_string('none'), 'underline' => get_string('underline', 'editor')];
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Hide selected items in the primary navigation (custom menu).
$hideitemsoptions = [];
$hideitemsoptions['home'] = get_string('home');
if (!empty($CFG->enabledashboard)) {
    $hideitemsoptions['myhome'] = get_string('myhome');
}
$hideitemsoptions['courses'] = get_string('mycourses');
$hideitemsoptions['siteadminnode'] = get_string('administrationsite');
$name = 'theme_trema/hideprimarynavigationitems';
$title = get_string('hideprimarynavigationitems', 'theme_trema', null, true);
$description = get_string('hideprimarynavigationitems_desc', 'theme_trema', null, true);
$setting = new admin_setting_configmulticheckbox($name, $title, $description, [], $hideitemsoptions);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Primary navigation (custom menu) alignment.
$name = 'theme_trema/custommenualignment';
$title = get_string('custommenualignment', 'theme_trema');
$description = get_string('custommenualignment_desc', 'theme_trema');
$default = 'left';
$choices = [
    'left' => get_string('left', 'editor'),
    'center' => get_string('middle', 'editor'),
    'right' => get_string('right', 'editor'),
];
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Hide the User menu / Logout link.
$name = 'theme_trema/showumlogoutlink';
$title = get_string('showumlogoutlink', 'theme_trema');
$description = get_string('showumlogoutlink_desc', 'theme_trema');
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Favicon image setting.
$name = 'theme_trema/favicon';
$title = get_string('favicon', 'theme_trema');
$description = get_string('favicon_desc', 'theme_trema');
$setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Enable Admin Dashboard.
$name = 'theme_trema/enableadmindashboard';
$title = get_string('enableadmindashboard', 'theme_trema');
$description = get_string('enableadmindashboard_desc', 'theme_trema', "$CFG->wwwroot/theme/trema/pix/examples/admindashboard.png");
$default = '1';
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Enable Trema Lines.
$name = 'theme_trema/enabletremalines';
$title = get_string('enabletremalines', 'theme_trema');
$description = get_string('enabletremalines_desc', 'theme_trema', "$CFG->wwwroot/theme/trema/pix/examples/tremalines.png");
$default = '1';
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Enable a softer look by rounding some corners.
$name = 'theme_trema/softness';
$title = get_string('softness', 'theme_trema');
$description = get_string('softness_desc', 'theme_trema');
$default = '1';
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Must add the page after definiting all the settings!
$settings->add($page);
