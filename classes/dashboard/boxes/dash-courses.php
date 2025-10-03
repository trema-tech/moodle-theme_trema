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
 * Courses dashboard box.
 *
 * @package     theme_trema
 * @copyright   2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_trema\dashboard\boxes;

use theme_trema\dashboard\box_interface;

/**
 * Courses box class.
 */
class dash_courses implements box_interface {
    /**
     * Get box ID.
     *
     * @return string
     */
    public function get_id() {
        return 'courses';
    }

    /**
     * Get box name.
     *
     * @return string
     */
    public function get_name() {
        return get_string('dashbox_courses', 'theme_trema');
    }

    /**
     * Get template path.
     *
     * @return string
     */
    public function get_template() {
        return 'theme_trema/dashboard/boxes/dash-courses';
    }

    /**
     * Get box data.
     *
     * @return array
     */
    public function get_data() {
        require_once(__DIR__ . '/../../../locallib.php');
        return [
            'activecourses' => $this->count_active_courses(),
            'totalcourses' => $this->count_courses(),
        ];
    }

    /**
     * Get CSS class.
     *
     * @return string
     */
    public function get_css_class() {
        return 'dashboard-box-courses';
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
     * Count active courses with status 1 and startdate less than today - Cached.
     *
     * @return int number of active courses
     */
    private function count_active_courses() {
        global $DB;
        $cache = \cache::make('theme_trema', 'dashboardadmin');
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
    private function count_courses() {
        global $DB;
        $cache = \cache::make('theme_trema', 'dashboardadmin');
        $courses = $cache->get('courses');
        if (!$courses) {
            $courses = $DB->count_records('course') - 1; // Delete course site.
            $cache->set('courses', $courses);
        }
        return $courses;
    }
}
