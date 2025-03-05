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
 * @copyright   2019-2025 Trema - {@link https://trema.tech/}
 * @copyright   2023-2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_trema_fontscolors', get_string('fontscolorssettings', $themename));

// Heading for Colors section.
$name = 'theme_trema_colors';
$title = get_string('colors', $themename);
$description = '';
$format = FORMAT_MARKDOWN;
$setting = new admin_setting_heading($name, $title, $description, $format);
$page->add($setting);

// We use an empty default value because the default colour is defined in scss/defaultvariables.
$name = 'theme_trema/primarycolor';
$title = get_string('primarycolor', $themename);
$description = get_string('primarycolor_desc', $themename);
$default = '#1c6ca3';
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// We use an empty default value because the default colour is defined in scss/defaultvariables.
$name = 'theme_trema/secondarycolor';
$title = get_string('secondarycolor', $themename);
$description = get_string('secondarycolor_desc', $themename);
$default = '#343a40';
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// We use an empty default value because the default colour is defined in scss/defaultvariables.
$name = 'theme_trema/bodybackgroundcolor';
$title = get_string('bodybackgroundcolor', $themename);
$description = get_string('bodybackgroundcolor_desc', $themename);
$default = '#f1f1f1';
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Background colour options for header, footer and drawers.
$coloroptions = [
    '$white' => get_string('white', $themename),
    '$black' => get_string('black', $themename),
    '$gray-200' => get_string('light', $themename),
    '$gray-800' => get_string('dark', $themename),
    'primarycolor' => get_string('sameprimarycolor', $themename),
    'secondarycolor' => get_string('samesecondarycolor', $themename),
    'bodybackgroundcolor' => get_string('samebasecolor', $themename),
];

// Background for header.
$name = 'theme_trema/headerbgcolor';
$title = get_string('headerbgcolor', $themename);
$description = get_string('headerbgcolor_desc', $themename);
$default = '$white';
$setting = new admin_setting_configselect($name, $title, $description, $default, $coloroptions);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Background for Log In button in header.
$name = 'theme_trema/loginbtnbgcolor';
$title = get_string('loginbtnbgcolor', $themename);
$description = get_string('loginbtnbgcolor_desc', $themename);
$default = 'primarycolor';
$setting = new admin_setting_configselect($name, $title, $description, $default, $coloroptions);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Background for drawers.
$name = 'theme_trema/drawerbgcolor';
$title = get_string('drawerbgcolor', $themename);
$description = get_string('drawerbgcolor_desc', $themename);
$default = '$gray-200';
$setting = new admin_setting_configselect($name, $title, $description, $default, $coloroptions);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Background for footer.
$name = 'theme_trema/footerbgcolor';
$title = get_string('footerbgcolor', $themename);
$description = get_string('footerbgcolor_desc', $themename);
$default = '$gray-800';
$setting = new admin_setting_configselect($name, $title, $description, $default, $coloroptions);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Heading for Fonts section.
$name = 'theme_trema_fonts';
$title = get_string('fonts', $themename);
$description = '';
$format = FORMAT_MARKDOWN;
$setting = new admin_setting_heading($name, $title, $description, $format);
$page->add($setting);

// Fonts.
$fonts = [
    'Alegreya, serif' => 'Alegreya',
    'Arial, Helvetica, sans-serif' => 'Arial',
    '"Courier New", Courier, monospace' => 'CourierNew',
    '"CrimsonText", serif' => 'CrimsonText',
    '"EBGaramond", sans-serif' => 'EBGaramond',
    'Georgia, serif' => 'Georgia',
    'Lato, sans-serif' => 'Lato',
    'Montserrat, sans-serif' => 'Montserrat',
    '"NotoSans", sans-serif' => 'NotoSans',
    '"OpenSans", sans-serif' => 'OpenSans',
    '"PlayfairDisplay", serif' => 'PlayfairDisplay',
    'Poppins, sans-serif' => 'Poppins',
    'Roboto, Arial, Helvetica, sans-serif' => 'Roboto',
    '"Times New Roman", Times, serif' => 'TimesNewRoman',
    'Verdana, Tahoma, sans-serif' => 'Verdana',
];

// Site Font settings.
$name = 'theme_trema/sitefont';
$title = get_string('sitefont', $themename);
$description = get_string('sitefont_desc', $themename);
$default = 'Lato, sans-serif';
$setting = new admin_setting_configselect($name, $title, $description, $default, $fonts);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Front page banner titles font settings.
$name = 'theme_trema/bannertitlesfont';
$title = get_string('bannertitlesfont', $themename);
$description = get_string('bannertitlesfont_desc', $themename);
$default = 'Lato, sans-serif';
$setting = new admin_setting_configselect($name, $title, $description, $default, $fonts);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// H1 Font settings.
$name = 'theme_trema/h1font';
$title = get_string('h1font', $themename);
$description = get_string('h1font_desc', $themename);
$default = 'Lato, sans-serif';
$setting = new admin_setting_configselect($name, $title, $description, $default, $fonts);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Hx Font settings.
$name = 'theme_trema/hxfont';
$title = get_string('hxfont', $themename);
$description = get_string('hxfont_desc', $themename);
$default = 'Lato, sans-serif';
$setting = new admin_setting_configselect($name, $title, $description, $default, $fonts);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Heading for Appearance section.
$name = 'theme_trema_appearance';
$title = get_string('appearance');
$description = '';
$format = FORMAT_MARKDOWN;
$setting = new admin_setting_heading($name, $title, $description, $format);
$page->add($setting);

$transformoptions = [
    'none' => get_string('texttransform_none', $themename),
    'uppercase' => get_string('texttransform_uppercase', $themename),
    'lowercase' => get_string('texttransform_lowercase', $themename),
    'capitalize' => get_string('texttransform_capitalize', $themename),
];
$default = 'none';

// Text transform for backwards compatibility.
$name = 'theme_trema/texttransform';
$title = get_string('texttransform', $themename);
$description = get_string('texttransform_desc', $themename);
$setting = new admin_setting_configselect($name, $title, $description, $default, $transformoptions);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Banner title transform for backwards compatibility.
$name = 'theme_trema/bannertitletransform';
$title = get_string('bannertitletransform', $themename);
$description = get_string('bannertitletransform_desc', $themename);
$setting = new admin_setting_configselect($name, $title, $description, $default, $transformoptions);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Banner title letter spacing for backwards compatibility.
$options = [
    'normal' => 'Normal',
    '0.5rem' => 'Medium',
    '1rem' => 'Large',
];
$default = 'normal';

$name = 'theme_trema/bannertitlespacing';
$title = get_string('bannertitlespacing', $themename);
$description = get_string('bannertitlespacing_desc', $themename);
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Must add the page after definiting all the settings!
$settings->add($page);
