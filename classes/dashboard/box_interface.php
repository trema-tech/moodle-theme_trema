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
 * Dashboard box interface.
 *
 * @package     theme_trema
 * @copyright   2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_trema\dashboard;

/**
 * Interface for dashboard boxes.
 *
 * All dashboard boxes must implement this interface.
 */
interface box_interface {
    /**
     * Get the unique identifier for this box.
     *
     * @return string Box ID (e.g., 'diskusage', 'courses')
     */
    public function get_id();

    /**
     * Get the human-readable name of this box.
     *
     * @return string Box name for display in settings
     */
    public function get_name();

    /**
     * Get the template path for this box.
     *
     * @return string Template path (e.g., 'theme_trema/dashboard/boxes/dash-diskusage')
     */
    public function get_template();

    /**
     * Get the data to pass to the template.
     *
     * @return array Template context data
     */
    public function get_data();

    /**
     * Get the CSS class for this box.
     *
     * @return string CSS class name
     */
    public function get_css_class();

    /**
     * Check if this box requires JavaScript.
     *
     * @return string|false AMD module name or false if no JS needed
     */
    public function requires_js();
}
