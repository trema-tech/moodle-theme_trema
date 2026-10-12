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
 * Help users find form fields with validation errors.
 *
 * When a form fails validation, a summary listing the fields with errors is shown above the form
 * and kept up to date as they are fixed. With one error, the field is focused. With more, the
 * summary is focused. Links to a form field (in the summary, or the missing profile fields notice)
 * expand its section if collapsed, scroll it into view and focus it.
 *
 * Fields with errors are found by the has-danger class, which Moodle sets for both client-side
 * and server-side validation errors in all supported versions.
 *
 * @module     theme_trema/formerrors
 * @copyright  2023-2026 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([], function() {

    /** @var {Object} strings Summary headings: one, many (with {count} placeholder). */
    var strings = {};

    /**
     * Scroll a form field into view and focus its input.
     *
     * @param {HTMLElement} fitem The field.
     */
    var scrollToField = function(fitem) {
        fitem.scrollIntoView({behavior: 'smooth', block: 'center'});
        var input = fitem.querySelector('input:not([type="hidden"]), select, textarea');
        if (input) {
            input.focus({preventScroll: true});
        }
    };

    /**
     * Show a form field, expanding any collapsed form sections that contain it first.
     *
     * @param {HTMLElement} element The field, or an element inside it.
     */
    var showField = function(element) {
        var fitem = element.closest('.fitem') || element;

        // Expand collapsed sections using their own toggle, so Moodle keeps track of their state.
        var expanding = false;
        for (var section = fitem.parentElement; section; section = section.parentElement) {
            if (section.matches('.fcontainer.collapse:not(.show)')) {
                var toggle = document.querySelector('[aria-controls="' + section.id + '"]');
                if (toggle) {
                    toggle.click();
                    expanding = true;
                }
            }
        }
        if (!expanding) {
            scrollToField(fitem);
            return;
        }

        // Wait for the sections to finish opening (or up to 1 second) before scrolling.
        var waited = 0;
        var waitForSections = function() {
            var opening = fitem.closest('.fcontainer.collapsing, .fcontainer.collapse:not(.show)');
            if (opening && waited < 1000) {
                waited += 50;
                setTimeout(waitForSections, 50);
            } else {
                scrollToField(fitem);
            }
        };
        setTimeout(waitForSections, 50);
    };

    /**
     * Get the fields of a form that have a validation error.
     *
     * @param {HTMLFormElement} form
     * @return {HTMLElement[]}
     */
    var getErrors = function(form) {
        return Array.prototype.slice.call(form.querySelectorAll('.fitem.has-danger'));
    };

    /**
     * Create, update or remove the error summary of a form to match its current errors.
     *
     * @param {HTMLFormElement} form
     * @return {HTMLElement|null} The summary, or null if the form has no errors.
     */
    var updateSummary = function(form) {
        // Not form.id: Moodle forms have an input named "id", which hides the form's own id property.
        var summaryid = form.getAttribute('id') + '-errorsummary';
        var summary = document.getElementById(summaryid);
        var errors = getErrors(form);
        if (!errors.length) {
            if (summary) {
                summary.remove();
            }
            return null;
        }

        if (!summary) {
            summary = document.createElement('div');
            summary.id = summaryid;
            summary.className = 'alert alert-danger trema-errorsummary';
            summary.setAttribute('role', 'region');
            summary.setAttribute('aria-labelledby', summaryid + '-heading');
            summary.tabIndex = -1;
            form.parentNode.insertBefore(summary, form);
        }

        var heading = document.createElement('p');
        heading.id = summaryid + '-heading';
        heading.className = 'fw-bold mb-2';
        heading.textContent = errors.length === 1 ? strings.one : strings.many.replace('{count}', errors.length);

        var list = document.createElement('ul');
        list.className = 'mb-0';
        errors.forEach(function(fitem, index) {
            if (!fitem.id) {
                fitem.id = 'fitem_' + summaryid + '_' + index;
            }
            var label = fitem.querySelector('[id$="_label"], label');
            var link = document.createElement('a');
            link.href = '#' + fitem.id;
            link.textContent = (label ? label.textContent : fitem.textContent).replace(/\s+/g, ' ').trim();
            var item = document.createElement('li');
            item.appendChild(link);
            list.appendChild(item);
        });

        summary.replaceChildren(heading, list);
        return summary;
    };

    /**
     * Keep the error summary of a form up to date as errors are fixed or added.
     *
     * @param {HTMLFormElement} form
     */
    var watchForm = function(form) {
        if (form.dataset.tremaErrorSummary) {
            return;
        }
        form.dataset.tremaErrorSummary = 1;
        var pending = false;
        new MutationObserver(function() {
            if (!pending) {
                pending = true;
                setTimeout(function() {
                    pending = false;
                    updateSummary(form);
                }, 100);
            }
        }).observe(form, {subtree: true, attributes: true, attributeFilter: ['class']});
    };

    /**
     * Show the errors of a form: the summary, and focus on the field or the summary.
     *
     * @param {HTMLFormElement} form
     */
    var showErrors = function(form) {
        var summary = updateSummary(form);
        if (!summary) {
            return;
        }
        watchForm(form);
        var errors = getErrors(form);
        if (errors.length === 1) {
            showField(errors[0]);
        } else {
            summary.scrollIntoView({behavior: 'smooth', block: 'start'});
            summary.focus({preventScroll: true});
        }
    };

    return {
        /**
         * Initialise.
         *
         * @param {Object} summarystrings Summary headings: one, many (with {count} placeholder).
         */
        init: function(summarystrings) {
            strings = summarystrings;

            // Links to a form field also expand its section, scroll to it and focus it.
            document.addEventListener('click', function(e) {
                var link = e.target.closest('a[href^="#fitem_"]');
                var fitem = link && document.getElementById(link.getAttribute('href').substring(1));
                if (fitem) {
                    e.preventDefault();
                    showField(fitem);
                }
            });

            // Client-side validation: Moodle cancels the submission when a field has an error.
            document.addEventListener('submit', function(e) {
                var form = e.target;
                if (form.classList && form.classList.contains('mform')) {
                    setTimeout(function() {
                        if (e.defaultPrevented) {
                            showErrors(form);
                        }
                    }, 0);
                }
            });

            // Server-side validation: the form is redisplayed with the errors marked.
            var form = Array.prototype.find.call(document.querySelectorAll('form.mform'), function(form) {
                return form.querySelector('.fitem.has-danger');
            });
            if (form) {
                showErrors(form);
            }
        }
    };
});
