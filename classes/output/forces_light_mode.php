<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace theme_trema\output;

/**
 * Keeps the site in light mode until the user logs in.
 *
 * The local_boost_dark plugin writes the data-bs-theme attribute on the <html> tag.
 * For guests and logged-out users it writes "auto", which follows the operating system:
 * a visitor whose computer is in dark mode would see a dark login page they never asked for.
 * The same happens with an old "darkmode" cookie or with ?darkmode=dark in the URL.
 *
 * Rules applied here:
 * - Guests and logged-out users always get light mode.
 * - Logged-in users get the mode they chose with the toggle.
 * - Logged-in users who never used the toggle get light mode (instead of "auto"),
 *   so the page matches the toggle, which shows light mode as off.
 *
 * @package    theme_trema
 * @copyright  2026 Jhon Rangel <jrangelardila@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait forces_light_mode {
    /**
     * Returns the attributes for the <html> tag, with the color mode rules above applied.
     *
     * @return string
     */
    public function htmlattributes() {
        $attributes = parent::htmlattributes();

        if (isloggedin() && !isguestuser()) {
            // The user never chose a mode: use light instead of following the operating system.
            if (get_user_preferences('darkmode') === null) {
                $attributes = str_replace('data-bs-theme="auto"', 'data-bs-theme="light"', $attributes);
            }
            return $attributes;
        }

        // Guests and logged-out users: drop whatever mode the plugin wrote and use light.
        $attributes = preg_replace('/\s*data-bs-theme="[^"]*"/', '', $attributes);
        return $attributes . ' data-bs-theme="light"';
    }
}
