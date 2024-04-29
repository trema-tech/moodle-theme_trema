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
 * Upgrade file.
 *
 * @package     theme_trema
 * @copyright   2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Michael Milette
 * @copyright   2024 Trema - {@link https://trema.tech/}
 * @author      Rodrigo Mady
 * @copyright   Based on 2022 Willian Mano {@link https://conecti.me}.
 * @author      Based on Willian Mano {@link https://conecti.me}.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade the theme_trema plugin.
 *
 * @param int $oldversion The old version of the plugin.
 * @return bool Returns true if the upgrade was successful, false otherwise.
 */
function xmldb_theme_trema_upgrade($oldversion): bool {
    global $DB;

    if ($oldversion < 2024031000) {
        // Particles background is deprecated.
        if (get_config('theme_trema', 'loginpagestyle') == 'particle-circles') {
            set_config('loginpagestyle', 'none', 'theme_trema');
        }
        upgrade_plugin_savepoint(true, 2024031000, 'theme', 'trema');
    }

    if ($oldversion < 2024040100) {
        // Adds usertours data from Boost.
        $usertours = $DB->get_records('tool_usertours_tours');

        if ($usertours) {
            foreach ($usertours as $usertour) {
                $configdata = json_decode($usertour->configdata);

                if (in_array('boost', $configdata->filtervalues->theme)) {
                    $configdata->filtervalues->theme[] = 'trema';
                }

                $updatedata = new stdClass();
                $updatedata->id = $usertour->id;
                $updatedata->configdata = json_encode($configdata);

                $DB->update_record('tool_usertours_tours', $updatedata);
            }
        }
        upgrade_plugin_savepoint(true, 2024040100, 'theme', 'trema');
    }

    if ($oldversion < 2024042300) {
        // Renamed trema.scss to default.scss.
        if (get_config('theme_trema', 'preset') == 'trema.scss') {
            set_config('preset', 'default.scss', 'theme_trema');
        }
        upgrade_plugin_savepoint(true, 2024042300, 'theme', 'trema');
    }

    return true;
}
