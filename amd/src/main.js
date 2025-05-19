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

/**
 * @module theme_trema/main
 * @description Removes the "show" class from the usermenu dropdown to hide an incorrect arrow
 *              that appears on initial load in Moodle versions 3.9, 4.0, 4.1, and 4.2.
 *              This ensures the arrow is hidden the first time the page is loaded.
 * @copyright   2023 Rodrigo Mady <rodrigo.mady@moodle.org>
 * @copyright   2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Michael Milette
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([], function() {
    return {
        init: function() {
            // Get the element with the classes "dropdown" and "show"
            const dropdown = document.querySelector(".dropdown.show");
            // Remove the class "show" from the element if it exists
            if (dropdown) {
                dropdown.classList.remove("show");
            }
        }
    };
});
