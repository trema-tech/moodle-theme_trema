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
 * Login settings
 *
 * @package     theme_trema
 * @copyright   2019-2024 Trema - {@link https://trema.tech/}
 * @copyright   2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_trema_login', get_string('login', 'theme_trema'));

// Login page style.
$name = 'theme_trema/loginpagestyle';
$title = get_string('loginpagestyle', 'theme_trema');
$description = '';
$choices = [
    "image" => get_string('image', 'theme_trema', ''),
    "none" => get_string('none'),
];
$default = 'none';
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

if (get_config('theme_trema', 'loginpagestyle') == "image") {
    // Login background image.
    $name = 'theme_trema/loginbackgroundimage';
    $title = get_string('loginbackgroundimage', 'theme_trema');
    $description = '';
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbackgroundimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
}


// Login box text alignment.
$choices = [
    'left'   => get_string('left', 'editor'),
    'center' => get_string('middle', 'editor'),
    'right'  => get_string('right', 'editor'),
];
$name = 'theme_trema/loginboxcontentalign';
$title = get_string('loginboxcontentalign', 'theme_trema');
$description = get_string('loginboxcontentalign_desc', 'theme_trema');
$default = 'center';
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_trema/loginpagecreatefirst';
$title = get_string('loginpagecreatefirst', 'theme_trema');
$description = get_string('loginpagecreatefirst_desc', 'theme_trema');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_trema/loginshowloginform';
$title = get_string('loginshowloginform', 'theme_trema');
$description = get_string('loginshowloginform_desc', 'theme_trema');
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$settings->add($page);
