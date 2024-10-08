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
 * Core navigation.
 *
 * @package   theme_trema
 * @category  navigation
 * @copyright 2022-2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author    Michael Milette
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_trema\output;

use filter_manager;
use renderer_base;
use custom_menu;

/**
 * Custom primary navigation class for the Trema theme.
 *
 * This class extends the core primary navigation class and adds functionality
 * for handling custom menu items. Custom menu items reside on the same level
 * as the original nodes.
 */
class primary_navigation extends \core\navigation\output\primary {
    /**
     * Custom menu items reside on the same level as the original nodes.
     * Fetch and convert the nodes to a standardised array.
     *
     * @param renderer_base $output The renderer base instance.
     * @return array An array of custom menu items. If no custom menu items exist, an empty array is returned.
     */
    protected function get_custom_menu(renderer_base $output): array {
        global $CFG;

        // Early return if a custom menu does not exists.
        if (empty($CFG->custommenuitems)) {
            return [];
        }

        $custommenuitems = $CFG->custommenuitems;

        // If filtering of the primary custom menu is enabled, apply only the string filters.
        if (!empty(get_config('theme_trema', 'navfilter')) && strpos($CFG->custommenuitems, '}') !== false) {
            // Apply filters that are enabled for Content and Headings.
            $filtermanager = filter_manager::instance();
            $CFG->custommenuitems = $filtermanager->filter_string($CFG->custommenuitems, \context_system::instance());
        }
        $nodes = parent::get_custom_menu($output);

        $CFG->custommenuitems = $custommenuitems;

        return $nodes;
    }
}
