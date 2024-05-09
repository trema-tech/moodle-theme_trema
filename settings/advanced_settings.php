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
 * Advanced settings
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

$page = new admin_settingpage('theme_trema_advanced', get_string('advanced'));

// Preset.
$name = 'theme_trema/preset';
$title = get_string('preset', 'theme_boost');
$description = get_string('preset_desc', 'theme_boost');
$default = 'default.scss';
// These are the built in presets.
$choices = ['default.scss' => 'default.scss', 'plain.scss' => 'plain.scss'];
$setting = new admin_setting_configthemepreset($name, $title, $description, $default, $choices, 'trema');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Preset files setting.
$name = 'theme_trema/presetfiles';
$title = get_string('presetfiles', 'theme_boost');
$description = get_string('presetfiles_desc', 'theme_boost');
$restrictions = ['maxfiles' => 20, 'accepted_types' => ['.scss']];
$setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0, $restrictions);
$page->add($setting);

// Raw SCSS to include before the content.
$name = 'theme_trema/scsspre';
$title = get_string('rawscsspre', 'theme_boost');
$description = get_string('rawscsspre_desc', 'theme_boost');
$default = '';
$format = PARAM_RAW;
$setting = new admin_setting_scsscode($name, $title, $description, $default, $format);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Raw SCSS to include after the content.
$name = 'theme_trema/scss';
$title = get_string('rawscss', 'theme_boost');
$description = get_string('rawscss_desc', 'theme_boost');
$default = '';
$format = PARAM_RAW;
$setting = new admin_setting_scsscode($name, $title, $description, $default, $format);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Must add the page after definiting all the settings!
$settings->add($page);
