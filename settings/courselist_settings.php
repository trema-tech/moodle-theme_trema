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

$page = new admin_settingpage('theme_trema_courselist', get_string('courselistsettings', 'theme_trema'));

// Course Listing.
$themename = 'theme_trema';
$name = $themename . '/courselisting';
$title = '';
$description = get_string('courselistsettings_desc', $themename);
$setting = new admin_setting_heading($name, $title, $description);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Courses cards.
$name = 'theme_trema_course_cards';
$title = get_string('coursescards', 'theme_trema');
$description = '';
$format = FORMAT_MARKDOWN;
$setting = new admin_setting_heading($name, $title, $description, $format);
$page->add($setting);

// Show card contacts.
$name = $themename . '/cardcontacts';
$title = get_string('cardcontacts', $themename);
$description = get_string('cardcontacts_desc', $themename);
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Show categories on Frontpage course cards.
$name = 'theme_trema/showcategories';
$title = get_string('showcategories', 'theme_trema');
$description = get_string('showcategories_desc', 'theme_trema');
$default = false;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Summary type.
$name = 'theme_trema/summarytype';
$title = get_string('summarytype', 'theme_trema');
$description = get_string('summarytype_desc', 'theme_trema');
$choices = [
    "" => get_string('none'),
    "modal" => get_string('modal', 'theme_trema'),
    "popover" => get_string('popover', 'theme_trema'),
    "link" => get_string('link', 'theme_trema'),
];
$default = 'modal';
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Show hidden categories on Frontpage course cards.
$name = 'theme_trema/showehiddencategorycourses';
$title = get_string('showehiddencategorycourses', 'theme_trema');
$description = get_string('showehiddencategorycourses_desc', 'theme_trema');
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Must add the page after definiting all the settings!
$settings->add($page);
