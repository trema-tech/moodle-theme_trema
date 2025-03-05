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
 * Settings file
 *
 * @package     theme_trema
 * @copyright   2019-2025 Trema - {@link https://trema.tech/}
 * @copyright   2024-2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $themename = 'theme_trema';
    $settings = new theme_boost_admin_settingspage_tabs('themesettingtrema', get_string('configtitle', $themename));
    require_once($CFG->dirroot . '/theme/trema/settings/general_settings.php');
    require_once($CFG->dirroot . '/theme/trema/settings/advanced_settings.php');
    require_once($CFG->dirroot . '/theme/trema/settings/fontscolors_settings.php');
    require_once($CFG->dirroot . '/theme/trema/settings/frontpage_settings.php');
    require_once($CFG->dirroot . '/theme/trema/settings/courselist_settings.php');
    require_once($CFG->dirroot . '/theme/trema/settings/course_settings.php');
    require_once($CFG->dirroot . '/theme/trema/settings/footer_settings.php');
    require_once($CFG->dirroot . '/theme/trema/settings/login_settings.php');
    require_once($CFG->dirroot . '/theme/trema/settings/profile_settings.php');
    require_once($CFG->dirroot . '/theme/trema/settings/styleguide_settings.php');
}
