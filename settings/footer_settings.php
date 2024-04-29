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
 * @copyright   2019 Trema - {@link https://trema.tech/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_trema_footer', get_string('defaultfooter', 'theme_trema'));

// Toggle between Moodle 4.x footer and a configurable traditional footer.
$name = 'theme_trema/enabletremafooter';
$title = get_string('enabletremafooter', 'theme_trema');
$description = get_string('enabletremafooter_desc', 'theme_trema', "$CFG->wwwroot/theme/trema/pix/examples/footerinfo.png");
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Select footer opacity.
$options = [];
for ($i = 0; $i <= 100; $i += 10) {
    $options['' . ($i / 100)] = $i;
}
$name = 'theme_trema/footeropacity';
$title = get_string('footeropacity', 'theme_trema');
$description = get_string('footeropacity_desc', 'theme_trema');
$default = '1';
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// HTML to include in the footer content.
$name = 'theme_trema/defaultfooter';
$title = get_string('defaultfooter', 'theme_trema');
$description = get_string('defaultfooter_desc', 'theme_trema');
$default = get_string('defaultfooter_default', 'theme_trema');
$setting = new admin_setting_confightmleditor($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Show/hide footer information.
$name = 'theme_trema/enablefooterinfo';
$title = get_string('enablefooterinfo', 'theme_trema');
$description = get_string('enablefooterinfo_desc', 'theme_trema', "$CFG->wwwroot/theme/trema/pix/examples/footerinfo.png");
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Show/Hide Moodle branding (e.g. Moodle logo, Powered by Moodle).
$name = 'theme_trema/showbranding';
$title = get_string('showbranding', 'theme_trema');
$description = get_string('showbranding_desc', 'theme_trema');
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Must add the page after definiting all the settings!
$settings->add($page);
