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
 * Enrolments dashboard box.
 *
 * @package     theme_trema
 * @copyright   2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_trema\dashboard\boxes;

use theme_trema\dashboard\box_interface;

/**
 * Enrolments box class.
 */
class dash_enrolments implements box_interface {
    /**
     * Get box ID.
     *
     * @return string
     */
    public function get_id() {
        return 'enrolments';
    }

    /**
     * Get box name.
     *
     * @return string
     */
    public function get_name() {
        return get_string('dashbox_enrolments', 'theme_trema');
    }

    /**
     * Get template path.
     *
     * @return string
     */
    public function get_template() {
        return 'theme_trema/dashboard/boxes/dash-enrolments';
    }

    /**
     * Get box data.
     *
     * @return array
     */
    public function get_data() {
        require_once(__DIR__ . '/../../../locallib.php');
        return [
            'activeenrolments' => $this->count_active_enrolments(),
            'enrolments' => $this->count_users_enrolments(),
        ];
    }

    /**
     * Get CSS class.
     *
     * @return string
     */
    public function get_css_class() {
        return 'dashboard-box-enrolments';
    }

    /**
     * Check if requires JS.
     *
     * @return string|false
     */
    public function requires_js() {
        return false;
    }

    /**
     * Get all active enrolments from actives courses - Cached.
     *
     * @return void
     */
    private function count_active_enrolments() {
        global $DB;
        $cache = \cache::make('theme_trema', 'dashboardadmin');
        $activeenrolments = $cache->get('activeenrolments');
        if (!$activeenrolments) {
            $today = time();
            $activecourses = $this->get_active_courses();
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
    private function count_users_enrolments() {
        global $DB;
        $cache = \cache::make('theme_trema', 'dashboardadmin');
        $usersenrolments = $cache->get('usersenrolments');
        if (!$usersenrolments) {
            $usersenrolments = $DB->count_records('user_enrolments');
            $cache->set('$usersenrolments', $usersenrolments);
        }
        return $usersenrolments;
    }

    /**
     * Get active courses with status 1 and startdate less than today - Cached.
     *
     * @return int number of active courses
     */
    private function get_active_courses() {
        global $DB;
        $cache = \cache::make('theme_trema', 'dashboardadmin');
        $activecourses = $cache->get('activecourses');
        if (!$activecourses) {
            $today = time();
            $sql = "SELECT id FROM {course}
                WHERE visible = 1 AND startdate <= :today AND (enddate > :today2 OR enddate = 0) AND format != 'site'";
            $activecourses = $DB->get_fieldset_sql($sql, ['today' => $today, 'today2' => $today]);
            $cache->set('activecourses', $activecourses);
        }
        return $activecourses;
    }
}
