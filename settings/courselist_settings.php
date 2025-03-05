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

$page = new admin_settingpage('theme_trema_courselist', get_string('courselistsettings', $themename));

// Course Listing.
$name = $themename . '/courselisting';
$title = '';
$description = get_string('courselistsettings_desc', $themename);
$setting = new admin_setting_heading($name, $title, $description);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Courses cards.
$name = 'theme_trema_course_cards';
$title = get_string('coursescards', $themename);
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
$title = get_string('showcategories', $themename);
$description = get_string('showcategories_desc', $themename);
$default = false;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Summary type.
$name = 'theme_trema/summarytype';
$title = get_string('summarytype', $themename);
$description = get_string('summarytype_desc', $themename);
$choices = [
    "" => get_string('none'),
    "modal" => get_string('modal', $themename),
    "popover" => get_string('popover', $themename),
    "link" => get_string('link', $themename),
];
$default = 'modal';
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Show hidden categories on Frontpage course cards.
$name = 'theme_trema/showehiddencategorycourses';
$title = get_string('showehiddencategorycourses', $themename);
$description = get_string('showehiddencategorycourses_desc', $themename);
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Must add the page after definiting all the settings!
$settings->add($page);
