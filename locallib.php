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
 * @copyright   2022-2025 Trema - {@link https://trema.tech/}
 * @copyright   2023-2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Return a array of objects containing all cards settings.
 *
 * @return array of objects
 */
function theme_trema_get_cards_settings() {
    $theme = theme_config::load('trema');
    $cardssettings = [];

    $numberofcards = get_config('theme_trema', 'numberofcards');
    if (get_config('theme_trema', 'frontpageenablecards') && $numberofcards > 1) {
        for ($i = 1; $i <= $numberofcards; $i++) {
            $cardsettings = new stdClass();
            $cardsettings->cardicon = $theme->settings->{'cardicon' . $i};
            $cardsettings->cardiconcolor = $theme->settings->{'cardiconcolor' . $i};
            $cardsettings->cardtitle = \format_string($theme->settings->{'cardtitle' . $i});
            $cardsettings->cardsubtitle = \format_string($theme->settings->{'cardsubtitle' . $i});
            $cardsettings->cardlink = $theme->settings->{'cardlink' . $i};

            $cardssettings[] = $cardsettings;
        }
        return $cardssettings;
    } else {
        return false;
    }
}

/**
 * Get the URL of files from theme settings.
 *
 * @param [type] $setting
 * @param string $filearea
 * @param Theme $theme
 * @return void
 */
function theme_trema_setting_file_url($setting, $filearea, $theme) {
    global $CFG;

    $component  = 'theme_trema';
    $itemid     = 0;
    $filepath   = !empty($theme->settings->$filearea) ? $theme->settings->$filearea : '';

    if (empty($filepath)) {
        return false;
    }
    $syscontext = \context_system::instance();

    $url = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/$syscontext->id/$component/$filearea/$itemid" . $filepath);

    // Now this is tricky because the we can not hardcode http or https here, lets use the relative link.
    // Note: unfortunately moodle_url does not support //urls yet.

    $url = preg_replace('|^https?://|i', '//', $url->out(false));

    return $url;
}
