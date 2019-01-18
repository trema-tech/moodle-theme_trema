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
 * @package   theme_trema
 * @copyright 2018 Trevor Furtado e Rodrigo Mady
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_trema_general', get_string('generalsettings', 'theme_trema'));

// Favicon image setting.
$name = 'theme_trema/favicon';
$title = get_string('favicon', 'theme_trema');
$description = get_string('favicon_desc', 'theme_trema');
$setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// We use an empty default value because the default colour is defined in scss/defaultvariables.
$name = 'theme_trema/primarycolor';
$title = get_string('primarycolor', 'theme_trema');
$description = get_string('primarycolor_desc', 'theme_trema');
$setting = new admin_setting_configcolourpicker($name, $title, $description, '');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// We use an empty default value because the default colour is defined in scss/defaultvariables.
$name = 'theme_trema/secondarycolor';
$title = get_string('secondarycolor', 'theme_trema');
$description = get_string('secondarycolor_desc', 'theme_trema');
$setting = new admin_setting_configcolourpicker($name, $title, $description, '');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_trema/enableadmindashboard';
$title = get_string('enableadmindashboard', 'theme_trema');
$description = get_string('enableadmindashboard_desc', 'theme_trema', "$CFG->wwwroot/theme/trema/pix/examples/admindashboard.png");
$setting = new admin_setting_configcheckbox($name, $title, $description, '1');
$page->add($setting);

// Must add the page after definiting all the settings!
$settings->add($page);

