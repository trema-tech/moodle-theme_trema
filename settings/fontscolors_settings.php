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
 * @author      Michael Milette
 * @author      Trevor Furtado
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_trema_fontscolors', get_string('fontscolorssettings', 'theme_trema'));

// We use an empty default value because the default colour is defined in scss/defaultvariables.
$name = 'theme_trema/primarycolor';
$title = get_string('primarycolor', 'theme_trema');
$description = get_string('primarycolor_desc', 'theme_trema');
$setting = new admin_setting_configcolourpicker($name, $title, $description, '#FD647A');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// We use an empty default value because the default colour is defined in scss/defaultvariables.
$name = 'theme_trema/secondarycolor';
$title = get_string('secondarycolor', 'theme_trema');
$description = get_string('secondarycolor_desc', 'theme_trema');
$setting = new admin_setting_configcolourpicker($name, $title, $description, '#373A3C');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// We use an empty default value because the default colour is defined in scss/defaultvariables.
$name = 'theme_trema/bodybackgroundcolor';
$title = get_string('bodybackgroundcolor', 'theme_trema');
$description = get_string('bodybackgroundcolor_desc', 'theme_trema');
$setting = new admin_setting_configcolourpicker($name, $title, $description, '#f1f1f1');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Fonts.
$fonts = [
    'Arial, Helvetica, sans-serif' => 'Arial',
    'Verdana, Tahoma, sans-serif' => 'Verdana',
    '"Times New Roman", Times, serif' => 'TimesNewRoman',
    'Georgia, serif' => 'Georgia',
    '"Courier New", Courier, monospace' => 'CourierNew',
    'Alegreya, serif' => 'Alegreya',
    '"CrimsonText", serif' => 'CrimsonText',
    '"EBGaramond", sans-serif' => 'EBGaramond',
    'Lato, sans-serif' => 'Lato',
    'Montserrat, sans-serif' => 'Montserrat',
    '"NotoSans", sans-serif' => 'NotoSans',
    '"OpenSans", sans-serif' => 'OpenSans',
    '"PlayfairDisplay", serif' => 'PlayfairDisplay',
    'Poppins, sans-serif' => 'Poppins',
    'Roboto, Arial, Helvetica, sans-serif' => 'Roboto',
];

// Site Font settings.
$name = 'theme_trema/sitefont';
$title = get_string('sitefont', 'theme_trema');
$description = get_string('sitefont_desc', 'theme_trema');
$setting = new admin_setting_configselect($name, $title, $description, 'Lato', $fonts);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Title/H1 Font settings.
$name = 'theme_trema/h1font';
$title = get_string('h1font', 'theme_trema');
$description = get_string('h1font_desc', 'theme_trema');
$setting = new admin_setting_configselect($name, $title, $description, 'Lato', $fonts);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Hx Font settings.
$name = 'theme_trema/hxfont';
$title = get_string('hxfont', 'theme_trema');
$description = get_string('hxfont_desc', 'theme_trema');
$setting = new admin_setting_configselect($name, $title, $description, 'Lato', $fonts);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Must add the page after definiting all the settings!
$settings->add($page);
