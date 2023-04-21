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
 * Manage user scroll in Moodle for future floating elements.
 *
 * @copyright  2023 Rodrigo Mady <rodrigo.mady@moodle.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Trema main.
 *
 * @class MoodleScroll
 */
export const init = () => {
    // Get the element with the classes "dropdown" and "show"
    const dropdown = document.querySelector(".dropdown.show");
    // Remove the class "show" from the element
    dropdown.classList.remove("show");
};
