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
 * Course settings
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

// Content settings.
$page = new admin_settingpage('theme_trema_courses', get_string('courses'));

// Settings for Courses.
$name = 'theme_trema_courses';
$title = get_string('courses');
$description = '';
$format = FORMAT_MARKDOWN;
$setting = new admin_setting_heading($name, $title, $description, $format);
$page->add($setting);

// Course enrolment page format.
$name = 'theme_trema/courseenrolmentpageformat';
$title = get_string('courseenrolmentpageformat', 'theme_trema');
$description = get_string('courseenrolmentpageformat_desc', 'theme_trema');
$choices = [
    "card" => get_string('courseenrolmentpagecard', 'theme_trema'),
    "fullwidth" => get_string('courseenrolmentpagefull', 'theme_trema'),
];
$default = 'fullwidth';
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Show activity navigation.
$name = 'theme_trema/shownactivitynavigation';
$title = get_string('shownactivitynavigation', 'theme_trema');
$description = get_string('shownactivitynavigation_desc', 'theme_trema');
$default = false;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Show activity icons.
$name = 'theme_trema/showactivityicons';
$title = get_string('showactivityicons', 'theme_trema');
$description = get_string('showactivityicons_desc', 'theme_trema');
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$settings->add($page);
