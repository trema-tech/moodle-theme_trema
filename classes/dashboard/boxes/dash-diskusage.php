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
 * Disk usage dashboard box.
 *
 * @package     theme_trema
 * @copyright   2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_trema\dashboard\boxes;

use theme_trema\dashboard\box_interface;

/**
 * Disk usage box class.
 */
class dash_diskusage implements box_interface {
    /**
     * Get box ID.
     *
     * @return string
     */
    public function get_id() {
        return 'diskusage';
    }

    /**
     * Get box name.
     *
     * @return string
     */
    public function get_name() {
        return get_string('dashbox_diskusage', 'theme_trema');
    }

    /**
     * Get template path.
     *
     * @return string
     */
    public function get_template() {
        return 'theme_trema/dashboard/boxes/dash-diskusage';
    }

    /**
     * Get box data.
     *
     * @return array
     */
    public function get_data() {
        require_once(__DIR__ . '/../../../locallib.php');
        return [
            'disk' => $this->get_disk_usage(),
        ];
    }

    /**
     * Get CSS class.
     *
     * @return string
     */
    public function get_css_class() {
        return 'dashboard-box-diskusage';
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
     * Get the disk usage - Cached.
     *
     * @return string disk usage plus unit
     */
    private function get_disk_usage() {
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
}
