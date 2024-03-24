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

// Preset.
$name = 'theme_trema/preset';
$title = get_string('preset', 'theme_trema');
$description = get_string('preset_desc', 'theme_trema');
$default = 'trema.scss';
// These are the built in presets.
$choices = ['trema.scss' => 'trema.scss', 'plain.scss' => 'plain.scss'];
$setting = new admin_setting_configthemepreset($name, $title, $description, $default, $choices, 'trema');
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

// Hide selected items in the primary navigation.
$hideitemsoptions = [];
$hideitemsoptions['home'] = get_string('home');
if ($CFG->enabledashboard) {
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

// Navbar - show my courses.
$choices = [
    0 => "don't show",
    -10000 => "show left",
    10000 => "show right",
];

$name = 'theme_trema/showmycourses';
$title = new lang_string('showmycourses', 'theme_trema');
$description = new lang_string('showmycourses_desc', 'theme_trema');
$default = '-10000';
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Favicon image setting.
$name = 'theme_trema/favicon';
$title = get_string('favicon', 'theme_trema');
$description = get_string('favicon_desc', 'theme_trema');
$setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_trema/enableadmindashboard';
$title = get_string('enableadmindashboard', 'theme_trema');
$description = get_string('enableadmindashboard_desc', 'theme_trema', "$CFG->wwwroot/theme/trema/pix/examples/admindashboard.png");
$default = '1';
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_trema/enabletrematopics';
$title = get_string('enabletrematopics', 'theme_trema');
$description = get_string('enabletrematopics_desc', 'theme_trema', "$CFG->wwwroot/theme/trema/pix/examples/trematopics.png");
$default = '1';
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_trema/enabletremalines';
$title = get_string('enabletremalines', 'theme_trema');
$description = get_string('enabletremalines_desc', 'theme_trema', "$CFG->wwwroot/theme/trema/pix/examples/tremalines.png");
$default = '1';
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Must add the page after definiting all the settings!
$settings->add($page);
