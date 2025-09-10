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
 * Module to enforce required and locked profile fields during user creation.
 *
 * @module     theme_trema/profilefields
 * @author     Rodrigo Mady
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/str', 'core/notification'], function($, Str, Notification) {

    /**
     * Initialize the module.
     *
     * @param {Object} profileFieldsData - Data containing required profile fields
     */
    var init = function(profileFieldsData) {
        setupUserCreationPage(profileFieldsData || {});
    };

    /**
     * Setup handlers for the user creation page.
     *
     * @param {Object} profileFieldsData - Data containing required profile fields
     */
    var setupUserCreationPage = function(profileFieldsData) {
        if (profileFieldsData.length > 0) {
            addFormValidation(profileFieldsData);
        }
    };

    /**
     * Add validation to the user creation form.
     *
     * @param {Array} fields - Array of fields that are required
     */
    var addFormValidation = function(fields) {
        // Get the form element
        var $form = $('#id_mform1');

        // Get the required string for validation messages
        var requiredString = 'Required';
        Str.get_string('required', 'core').then(function(string) {
            requiredString = string;
            return true;
        }).catch(Notification.exception);

        // Process each profile field that is required.
        fields.forEach(function(field) {
            var fieldId = 'id_profile_field_' + field;
            var $field = $('#' + fieldId);

            if ($field.length > 0) {
                var $formGroup = $field.closest('.form-group');

                $field.attr('required', 'required');

                $field.attr('aria-required', 'true');

                var $labelCol = $formGroup.find('.col-form-label');
                var $labelAddon = $labelCol.find('.form-label-addon');

                if ($labelAddon.length === 0) {
                    $labelAddon = $('<div class="form-label-addon d-flex align-items-center align-self-start"></div>');
                    $labelCol.append($labelAddon);
                }

                // Add the required icon if not present
                if ($labelAddon.find('.text-danger').length === 0) {
                    var $requiredIcon = $(
                        '<div class="text-danger" title="Required">' +
                        '<i class="icon fa fa-exclamation-circle text-danger fa-fw " ' +
                        'title="Required" role="img" aria-label="Required"></i>' +
                        '</div>'
                    );
                    $labelAddon.append($requiredIcon);
                }

                var errorId = fieldId + '_error';
                var $felement = $formGroup.find('.felement');
                if ($felement.length > 0 && !$felement.find('#' + errorId).length) {
                    var $errorContainer = $('<div class="form-control-feedback invalid-feedback" id="' + errorId + '"></div>');
                    $felement.append($errorContainer);
                }

                $field.on('blur', function() {
                    validateField($field, requiredString);
                });

                if (!$form.data('validation-added')) {
                    $form.on('submit', function(e) {
                        var isValid = true;

                        // Validate all required profile fields
                        fields.forEach(function(fieldName) {
                            var fieldSelector = '#id_profile_field_' + fieldName;
                            var $fieldToValidate = $(fieldSelector);

                            if ($fieldToValidate.length > 0 && !validateField($fieldToValidate, requiredString)) {
                                isValid = false;
                            }
                        });

                        if (!isValid) {
                            e.preventDefault();
                            // Scroll to the first invalid field
                            var $firstInvalidField = $('.form-group.has-danger').first();
                            if ($firstInvalidField.length > 0) {
                                $('html, body').animate({
                                    scrollTop: $firstInvalidField.offset().top - 100
                                }, 200);
                            }
                        }
                    });

                    $form.data('validation-added', true);
                }
            }
        });
    };

    /**
     * Validate a single field.
     *
     * @param {jQuery} $field - The field to validate
     * @param {String} requiredString - The text to display for required fields
     * @return {boolean} - Whether the field is valid
     */
    var validateField = function($field, requiredString) {
        var fieldId = $field.attr('id');
        var $formGroup = $field.closest('.form-group');
        var $errorContainer = $('#' + fieldId + '_error');

        // Get the field value, handling different input types
        var value = $field.val();
        if ($field.is('input[type="checkbox"]')) {
            value = $field.is(':checked');
        } else if ($field.is('input[type="radio"]')) {
            value = $formGroup.find('input[type="radio"]:checked').length > 0;
        } else if ($field.is('select')) {
            // For select elements, empty string or default "choose" option is invalid
            if (value === '' || value === '0' || value === '-1') {
                value = '';
            }
        }

        // Check if the field has a value
        var isValid = value !== null && value !== '' && value !== false;

        if (isValid) {
            // Field is valid - remove error styling
            $formGroup.removeClass('has-danger');
            $field.removeClass('is-invalid');
            if ($errorContainer.length) {
                $errorContainer.empty();
            }
        } else {
            // Field is invalid - add error styling
            $formGroup.addClass('has-danger');
            $field.addClass('is-invalid');
            if ($errorContainer.length) {
                $errorContainer.text(requiredString);
            }
        }

        return isValid;
    };

    return {
        init: init
    };
});