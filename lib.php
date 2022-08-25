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
 * Lib file.
 *
 * @package     theme_trema
 * @copyright   2019 Trema - {@link https://trema.tech/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Load the main SCSS and the frontpage banner.
 *
 * @param theme_config $theme
 *            The theme config object.
 * @return string
 */
function theme_trema_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $scss .= file_get_contents("$CFG->dirroot/theme/trema/scss/defaultvariables.scss");

    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : 'trema.scss';

    $scss .= file_get_contents("$CFG->dirroot/theme/trema/scss/preset/{$filename}");

    if (!empty($theme->settings->enabletrematopics)) {
        $scss .= file_get_contents("$CFG->dirroot/theme/trema/scss/trema/topics.scss");
    }

    if (empty($theme->settings->enabletremalines)) {
        $scss .= "%border-frequency { &:before, &:after { content: none !important;}}";
    }

    // Frontpage banner.
    if (!empty($theme->settings->frontpageenabledarkoverlay)) {
        $darkoverlay = "url([[pix:theme|frontpage/overlay]]),";
    } else {
        $darkoverlay = "";
    }
    if ($frontpagebannerurl = $theme->setting_file_url('frontpagebanner', 'frontpagebanner')) {
        $scss .= "#frontpage-banner {background-image: $darkoverlay url('$frontpagebannerurl');}";
    } else {
        $scss .= "#frontpage-banner {background-image: $darkoverlay url([[pix:theme|frontpage/banner]]);}";
    }
    return $scss;
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme
 *            The theme config object.
 * @return string
 */
function theme_trema_get_pre_scss($theme) {
    $scss = '';
    $configurable = [
        // Config key => [variableName, ...].
        'primarycolor' => 'primary',
        'secondarycolor' => 'secondary',
        'particles_backgroundcolor' => 'particles-bg',
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $target) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        $scss .= '$' . $target . ': ' . $value . ";\n";
    }

    // Login background image.
    $backgroundimageurl = $theme->setting_file_url('loginbackgroundimage', 'loginbackgroundimage');
    if ($theme->settings->loginpagestyle == 'image' && !empty($backgroundimageurl)) {
        $scss .= "\$login-backgroundimage: '$backgroundimageurl';\n";
    } else {
        $scss .= "\$login-backgroundimage: '[[pix:theme|frontpage/banner]]';\n";
    }
    // Not image in settings.
    if ($theme->settings->loginpagestyle !== 'image') {
        $scss .= "body.pagelayout-login #page-wrapper { background-image: none; }";
    }

    // Prepend pre-scss.
    if (! empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }
    return $scss;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_trema_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        $theme = theme_config::load('trema');
        // By default, theme files must be cache-able by both browsers and proxies.
        if (! array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}
