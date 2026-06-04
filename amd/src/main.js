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
 *              (Moodle 3.9-4.2 quirk), explicitly initializes the frontpage
 *              carousel so it auto-cycles, and adds left/right touch swipe support.
 *              Bootstrap 5's data-API auto-init can miss carousels when the JS module
 *              loads after DOMContentLoaded, and Bootstrap's native touch handling is
 *              inconsistent across the BS4/BS5 versions this theme supports, so swipe
 *              gestures are wired up explicitly.
 * @copyright   2023 Rodrigo Mady <rodrigo.mady@moodle.org>
 * @copyright   2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Michael Milette
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'theme_boost/bootstrap/carousel'], function($, CarouselModule) {
    const Carousel = (CarouselModule && (CarouselModule.default || CarouselModule)) || null;
    return {
        init: function() {
            const dropdown = document.querySelector(".dropdown.show");
            if (dropdown) {
                dropdown.classList.remove("show");
            }
            const carouselEl = document.getElementById('carouselTrema');
            if (!carouselEl) {
                return;
            }
            let goNext = function() {};
            let goPrev = function() {};
            // Bootstrap 5 (Moodle 4.5+): static getOrCreateInstance.
            if (Carousel && typeof Carousel.getOrCreateInstance === 'function') {
                const instance = Carousel.getOrCreateInstance(carouselEl, {ride: 'carousel'});
                goNext = function() {
                    instance.next();
                };
                goPrev = function() {
                    instance.prev();
                };
            } else if ($.fn && typeof $.fn.carousel === 'function') {
                // Bootstrap 4 (Moodle 4.1-4.4): jQuery plugin.
                $(carouselEl).carousel({ride: 'carousel'});
                goNext = function() {
                    $(carouselEl).carousel('next');
                };
                goPrev = function() {
                    $(carouselEl).carousel('prev');
                };
            }
            // Left/right touch swipe. Passive listeners: we only read coordinates and
            // never preventDefault, so vertical page scrolling is unaffected.
            const swipeThreshold = 40;
            let startX = 0;
            let startY = 0;
            carouselEl.addEventListener('touchstart', function(e) {
                startX = e.changedTouches[0].screenX;
                startY = e.changedTouches[0].screenY;
            }, {passive: true});
            carouselEl.addEventListener('touchend', function(e) {
                const deltaX = e.changedTouches[0].screenX - startX;
                const deltaY = e.changedTouches[0].screenY - startY;
                // Only act on a dominant horizontal swipe beyond the threshold; this
                // ignores vertical scrolls and accidental taps.
                if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > swipeThreshold) {
                    if (deltaX < 0) {
                        goNext();
                    } else {
                        goPrev();
                    }
                }
            }, {passive: true});
        }
    };
});
