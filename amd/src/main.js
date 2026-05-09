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
 * @description Frontpage init: hides a stray dropdown "show" class on initial load
 *              (Moodle 3.9-4.2 quirk) and explicitly initializes the frontpage
 *              carousel so it auto-cycles. Bootstrap 5's data-API auto-init can
 *              miss carousels when the JS module loads after DOMContentLoaded.
 * @copyright   2023 Rodrigo Mady <rodrigo.mady@moodle.org>
 * @copyright   2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Michael Milette
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['theme_boost/bootstrap/carousel'], function(CarouselModule) {
    const Carousel = CarouselModule.default || CarouselModule;
    return {
        init: function() {
            const dropdown = document.querySelector(".dropdown.show");
            if (dropdown) {
                dropdown.classList.remove("show");
            }
            const carouselEl = document.getElementById('carouselTrema');
            if (carouselEl) {
                Carousel.getOrCreateInstance(carouselEl, {ride: 'carousel'});
            }
        }
    };
});
