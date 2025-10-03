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
 * Security dashboard box.
 *
 * @package     theme_trema
 * @copyright   2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_trema\dashboard\boxes;

use theme_trema\dashboard\box_interface;

/**
 * Security box class.
 */
class dash_security implements box_interface {
    /**
     * Get box ID.
     *
     * @return string
     */
    public function get_id() {
        return 'security';
    }

    /**
     * Get box name.
     *
     * @return string
     */
    public function get_name() {
        return get_string('dashbox_security', 'theme_trema');
    }

    /**
     * Get template path.
     *
     * @return string
     */
    public function get_template() {
        return 'theme_trema/dashboard/boxes/dash-security';
    }

    /**
     * Get box data.
     *
     * @return array
     */
    public function get_data() {
        global $CFG;
        require_once(__DIR__ . '/../../../locallib.php');
        $issuestatus = $this->get_environment_issues();
        return [
            'haswarning' => !empty($issuestatus['warning']),
            'warningcount' => $issuestatus['warning'] ?? 0,
            'wwwroot' => $CFG->wwwroot,
        ];
    }

    /**
     * Get CSS class.
     *
     * @return string
     */
    public function get_css_class() {
        return 'dashboard-box-security';
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
     * Environment issues Status  - Cached.
     *
     * @return false|mixed
     */
    private function get_environment_issues() {
        $cache = \cache::make('theme_trema', 'dashboardadmin');
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
}
