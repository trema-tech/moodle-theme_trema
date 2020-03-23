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
 * @copyright   2019 Trema - {@link https://trema.tech/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Load the main SCSS and the frontpage banner.
 *
 * @param theme_config $theme
 *            The theme config object.
 * @return string
 */
function theme_trema_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $scss .= file_get_contents("$CFG->dirroot/theme/trema/scss/defaultvariables.scss");
    $scss .= file_get_contents("$CFG->dirroot/theme/trema/scss/styles.scss");

    if ($frontpagebannerurl = $theme->setting_file_url('frontpagebanner', 'frontpagebanner')) {
        $scss .= "#frontpage-banner {background-image: url([[pix:theme|frontpage/overlay]]), url('$frontpagebannerurl');}";
    } else {
        $scss .= "#frontpage-banner {background-image: url([[pix:theme|frontpage/overlay]]), url([[pix:theme|frontpage/banner]]);}";
    }
    return $scss;
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme
 *            The theme config object.
 * @return string
 */
function theme_trema_get_pre_scss($theme) {
    $scss = '';
    $configurable = [
        // Config key => [variableName, ...].
        'primarycolor' => 'primary',
        'secondarycolor' => 'secondary',
        'particles_backgroundcolor' => 'particles-bg',
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $target) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        $scss .= '$' . $target . ': ' . $value . ";\n";
    }

    // Login background image.
    $backgroundimageurl = $theme->setting_file_url('loginbackgroundimage', 'loginbackgroundimage');
    if ($theme->settings->loginpagestyle == 'image' and !empty($backgroundimageurl)) {
        $scss .= "\$login-backgroundimage: '$backgroundimageurl';\n";
    } else {
        $scss .= "\$login-backgroundimage: '[[pix:theme|frontpage/banner]]';\n";
    }

    // Prepend pre-scss.
    if (! empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }
    return $scss;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_trema_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        $theme = theme_config::load('trema');
        // By default, theme files must be cache-able by both browsers and proxies.
        if (! array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}

/**
 * Return a array of objects containing all cards settings.
 *
 * @return array of objects
 */
function theme_trema_get_cards_settings() {
    $theme = theme_config::load('trema');
    $cardssettings = array();

    $numberofcards = get_config('theme_trema', 'numberofcards');
    if (get_config('theme_trema', 'frontpageenablecards') && $numberofcards > 1) {
        for ($i = 1; $i <= $numberofcards; $i ++) {
            $cardsettings = new stdClass();
            $cardsettings->cardicon = $theme->settings->{'cardicon' . $i};
            $cardsettings->cardiconcolor = $theme->settings->{'cardiconcolor' . $i};
            $cardsettings->cardtitle = format_text($theme->settings->{'cardtitle' . $i},FORMAT_HTML);
            $cardsettings->cardsubtitle = format_text($theme->settings->{'cardsubtitle' . $i},FORMAT_HTML);
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
function get_disk_usage() {
    global $CFG;

    $cache = cache::make('theme_trema', 'dashboardadmin');
    $totaldisk = $cache->get('totaldisk');

    if (!$totaldisk) {
        $total = get_directory_size($CFG->dataroot);
        $totaldisk = number_format(ceil($total / 1048576));
        $cache->set('totaldisk', $totaldisk);
    }

    $usageunit = ' MB';
    if ($totaldisk > 1024) {
        $usageunit = ' GB';
    }
    return $totaldisk . $usageunit;
}

/**
 * Count active courses with status 1 and startdate less than today - Cached.
 *
 * @return int number of active courses
 */
function count_active_courses() {
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
function count_courses() {
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
function get_active_courses() {
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
function count_active_enrolments() {
    global $DB;
    $cache = cache::make('theme_trema', 'dashboardadmin');
    $activeenrolments = $cache->get('activeenrolments');
    if (!$activeenrolments) {
        $today = time();
        $activecourses = get_active_courses();
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
function count_users_enrolments() {
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
function get_environment_issues() {
    global $CFG;
    $cache = cache::make('theme_trema', 'dashboardadmin');
    $environmentissues = $cache->get('environmentissues');
    if (!$environmentissues) {
        require_once("$CFG->dirroot/report/performance/locallib.php");
        $lib = new report_performance();
        $issues = [];
        $issues['themedesignermode'] = $lib->report_performance_check_themedesignermode();
        $issues['cachejs'] = $lib->report_performance_check_cachejs();
        $issues['debugmsg'] = $lib->report_performance_check_debugmsg();
        $issues['automatic_backup'] = $lib->report_performance_check_automatic_backup();
        $issues['enablestats'] = $lib->report_performance_check_enablestats();

        // Prevent warnings.
        $environmentissues["ok"] = 0;
        $environmentissues["warning"] = 0;
        foreach ($issues as $issue) {
            if ($issue->status == 'serious' || $issue->status == 'critical' || $issue->status == 'warning') {
                $environmentissues['warning'] ++;
            }
        }
        $cache->set('environmentissues', $environmentissues);
    }
    return $environmentissues;
}