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
 * Lib file.
 *
 * @package     theme_trema
 * @copyright   2022-2024 Trema - {@link https://trema.tech/}
 * @copyright   2023-2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Return a array of objects containing all cards settings.
 *
 * @return array of objects
 */
function theme_trema_get_cards_settings() {
    $theme = theme_config::load('trema');
    $cardssettings = [];

    $numberofcards = get_config('theme_trema', 'numberofcards');
    if (get_config('theme_trema', 'frontpageenablecards') && $numberofcards > 1) {
        for ($i = 1; $i <= $numberofcards; $i++) {
            $cardsettings = new stdClass();
            $cardsettings->cardicon = $theme->settings->{'cardicon' . $i};
            $cardsettings->cardiconcolor = $theme->settings->{'cardiconcolor' . $i};
            $cardsettings->cardtitle = format_text($theme->settings->{'cardtitle' . $i}, FORMAT_HTML);
            $cardsettings->cardsubtitle = format_text($theme->settings->{'cardsubtitle' . $i}, FORMAT_HTML);
            $cardsettings->cardlink = $theme->settings->{'cardlink' . $i};

            $cardssettings[] = $cardsettings;
        }
        return $cardssettings;
    } else {
        return false;
    }
}

/**
 * Get the disk usage - Cached.
 *
 * @return string disk usage plus unit
 */
function theme_trema_get_disk_usage() {
    global $CFG;

    $bytes = @disk_free_space($CFG->dataroot);
    if ($bytes === false || $bytes < 0 || is_null($bytes) || $bytes > 1.0E+26) {
        // If invalid number of bytes, or value is more than about 84,703.29 Yottabyte (YB), assume it is infinite.
        return '&infin;'; // Could not determine, assume infinite.
    }

    $warning = ($bytes <= 104857600); // Warning at 100 MB.
    $freespace = display_size($bytes);
    if (!$warning) {
        $freespace = '<span class="badge badge-danger px-1">' . $freespace . '</span>';
    }

    return $freespace;
}

/**
 * Count active courses with status 1 and startdate less than today - Cached.
 *
 * @return int number of active courses
 */
function theme_trema_count_active_courses() {
    global $DB;
    $cache = cache::make('theme_trema', 'dashboardadmin');
    $activecourses = $cache->get('countactivecourses');
    if (!$activecourses) {
        $today = time();
        $sql = "SELECT COUNT(id) FROM {course}
            WHERE visible = 1 AND startdate <= :today AND (enddate > :today2 OR enddate = 0) AND format != 'site'";
        $activecourses = $DB->count_records_sql($sql, ['today' => $today, 'today2' => $today]);
        $cache->set('countactivecourses', $activecourses);
    }
    return $activecourses;
}

/**
 * Count all courses - Cached.
 *
 * @return  int number of all courses
 */
function theme_trema_count_courses() {
    global $DB;
    $cache = cache::make('theme_trema', 'dashboardadmin');
    $courses = $cache->get('courses');
    if (!$courses) {
        $courses = $DB->count_records('course') - 1; // Delete course site.
        $cache->set('courses', $courses);
    }
    return $courses;
}

/**
 * Get active courses with status 1 and startdate less than today - Cached.
 *
 * @return int number of active courses
 */
function theme_trema_get_active_courses() {
    global $DB;
    $cache = cache::make('theme_trema', 'dashboardadmin');
    $activecourses = $cache->get('activecourses');
    if (!$activecourses) {
        $today = time();
        $sql = "SELECT id FROM {course}
            WHERE visible = 1 AND startdate <= :today AND (enddate > :today2 OR enddate = 0) AND format != 'site'";
        $activecourses = $DB->get_fieldset_sql($sql, ['today' => $today, 'today2' => $today]);;
        $cache->set('activecourses', $activecourses);
    }
    return $activecourses;
}


/**
 * Get all active enrolments from actives courses - Cached.
 *
 * @return void
 */
function theme_trema_count_active_enrolments() {
    global $DB;
    $cache = cache::make('theme_trema', 'dashboardadmin');
    $activeenrolments = $cache->get('activeenrolments');
    if (!$activeenrolments) {
        $today = time();
        $activecourses = theme_trema_get_active_courses();
        if ($activecourses) {
            list($in, $params) = $DB->get_in_or_equal($activecourses, SQL_PARAMS_NAMED);
            $params['today'] = $today;

            $sql = "SELECT COUNT(1) FROM {user_enrolments} ue
            INNER JOIN {enrol} e ON ue.enrolid = e.id
            WHERE ue.status = 0 AND (ue.timeend >= :today OR ue.timeend = 0) AND e.courseid {$in}";
            $activeenrolments = $DB->count_records_sql($sql, $params);
            $cache->set('activeenrolments', $activeenrolments);
        } else {
            $activeenrolments = 0;
            $cache->set('activeenrolments', $activeenrolments);
        }
    }
    return $activeenrolments;
}

/**
 * Get all active enrolments - Cached.
 *
 * @return void
 */
function theme_trema_count_users_enrolments() {
    global $DB;
    $cache = cache::make('theme_trema', 'dashboardadmin');
    $usersenrolments = $cache->get('usersenrolments');
    if (!$usersenrolments) {
        $usersenrolments = $DB->count_records('user_enrolments');
        $cache->set('$usersenrolments', $usersenrolments);
    }
    return $usersenrolments;
}

/**
 * Environment issues Status  - Cached.
 *
 * @return false|mixed
 */
function theme_trema_get_environment_issues() {
    $cache = cache::make('theme_trema', 'dashboardadmin');
    $environmentissues = $cache->get('environmentissues');
    if (!$environmentissues) {
        $issues = \core\check\manager::get_security_checks();

        // Prevent warnings.
        $environmentissues = [];
        $environmentissues["ok"]      = 0;
        $environmentissues["warning"] = 0;
        foreach ($issues as $issue) {
            $result = $issue->get_result()->get_status();
            if ($result == 'serious' || $result == 'critical' || $result == 'warning') {
                $environmentissues['warning']++;
            }
        }
        $cache->set('environmentissues', $environmentissues);
    }
    return $environmentissues;
}

/**
 * Get the URL of files from theme settings.
 *
 * @param [type] $setting
 * @param string $filearea
 * @param Theme $theme
 * @return void
 */
function theme_trema_setting_file_url($setting, $filearea, $theme) {
    global $CFG;

    $component  = 'theme_trema';
    $itemid     = 0;
    $filepath   = !empty($theme->settings->$filearea) ? $theme->settings->$filearea : '';

    if (empty($filepath)) {
        return false;
    }
    $syscontext = context_system::instance();

    $url = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/$syscontext->id/$component/$filearea/$itemid".$filepath);

    // Now this is tricky because the we can not hardcode http or https here, lets use the relative link.
    // Note: unfortunately moodle_url does not support //urls yet.

    $url = preg_replace('|^https?://|i', '//', $url->out(false));

    return $url;
}
