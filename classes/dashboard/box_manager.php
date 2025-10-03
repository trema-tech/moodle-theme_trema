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
 * Dashboard box manager.
 *
 * @package     theme_trema
 * @copyright   2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_trema\dashboard;

/**
 * Manager class for dashboard boxes.
 */
class box_manager {
    /**
     * Get all available box classes.
     *
     * @return array Array of box class names
     */
    public static function get_available_boxes() {
        $boxdir = __DIR__ . '/boxes';
        $boxes = [];

        if (is_dir($boxdir)) {
            $files = scandir($boxdir);
            foreach ($files as $file) {
                if (substr($file, 0, 5) === 'dash-' && substr($file, -4) === '.php') {
                    // Extract box ID from filename (e.g., 'dash-diskusage.php' -> 'diskusage').
                    $boxid = str_replace(['dash-', '.php'], '', $file);

                    // Build class name (e.g., 'theme_trema\dashboard\boxes\dash_diskusage').
                    $classname = 'theme_trema\\dashboard\\boxes\\dash_' . $boxid;

                    // Try to instantiate the class.
                    try {
                        if (class_exists($classname)) {
                            $box = new $classname();
                            if ($box instanceof box_interface) {
                                $boxes[$box->get_id()] = $box->get_name();
                            }
                        }
                    } catch (\Exception $e) {
                        // Skip boxes that fail to instantiate.
                        debugging('Failed to load dashboard box: ' . $classname . ' - ' . $e->getMessage(), DEBUG_DEVELOPER);
                    }
                }
            }
        }

        return $boxes;
    }

    /**
     * Get boxes for rendering.
     *
     * @param array $boxids Array of box IDs to render
     * @param \renderer_base $output Output renderer
     * @return array Array of box data for template
     */
    public static function get_boxes_for_rendering($boxids, $output) {
        global $PAGE, $CFG;

        $boxes = [];
        foreach ($boxids as $boxid) {
            if (empty($boxid) || $boxid === 'none') {
                continue;
            }

            // Manually require the box file (autoloader may not work here).
            $filepath = $CFG->dirroot . '/theme/trema/classes/dashboard/boxes/dash-' . $boxid . '.php';
            if (file_exists($filepath)) {
                require_once($filepath);
            }

            $classname = 'theme_trema\\dashboard\\boxes\\dash_' . $boxid;
            if (class_exists($classname)) {
                $box = new $classname();
                if ($box instanceof box_interface) {
                    // Render box template.
                    $content = $output->render_from_template($box->get_template(), $box->get_data());

                    // Load JS if needed.
                    if ($jsmodule = $box->requires_js()) {
                        $PAGE->requires->js_call_amd($jsmodule, 'init');
                    }

                    $boxes[] = [
                        'content' => $content,
                        'cssclass' => $box->get_css_class(),
                    ];
                }
            }
        }

        return $boxes;
    }

    /**
     * Get column class based on number of boxes.
     *
     * @param int $count Number of boxes
     * @return string Bootstrap column class
     */
    public static function get_column_class($count) {
        return match($count) {
            1 => 'col-12',
            2 => 'col-sm-6',
            3 => 'col-sm-4',
            4 => 'col-sm-3',
            default => 'col-sm-3'
        };
    }
}
