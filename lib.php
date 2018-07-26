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
 * @package    theme_trema
 * @copyright  2018 Trevor Furtado e Rodrigo Mady
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function theme_trema_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';

    $scss .= file_get_contents($CFG->dirroot . '/theme/trema/scss/defaultvariables.scss');

    $scss .= file_get_contents($CFG->dirroot . '/theme/trema/scss/styles.scss');
    
    if($frontpagebannerurl = $theme->setting_file_url('frontpagebanner', 'frontpagebanner')) {
        $scss .= "#frontpage-banner {background-image: url([[pix:theme|frontpage/overlay]]), url('$frontpagebannerurl');}";
    } else {
        $scss .= "#frontpage-banner {background-image: url([[pix:theme|frontpage/overlay]]), url([[pix:theme|frontpage/banner]]);}";
    }
    
    return $scss;
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return array
 */
function theme_trema_get_pre_scss($theme) {    
    $scss = '';
    $configurable = [
        // Config key => [variableName, ...].
        'primarycolor' => 'primary',
        'secondarycolor' => 'secondary'
    ];
    
    // Prepend variables first.
    foreach ($configurable as $configkey => $target) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }        
        $scss .= '$' . $target . ': ' . $value . ";\n";            
    }
    
    // Prepend pre-scss.
    if (!empty($theme->settings->scsspre)) {
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
//    if ($context->contextlevel == CONTEXT_SYSTEM && ($filearea === 'logo' || $filearea === 'backgroundimage' || $filearea === 'favicon' || $filearea === 'frontpagebanner')) {
    if ($context->contextlevel == CONTEXT_SYSTEM ) {
        $theme = theme_config::load('trema');
        // By default, theme files must be cache-able by both browsers and proxies.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}

/**
 * Return a array of objects containing all cards settings.
 *
 * @return array of objects
 */
function theme_trema_get_cards_settings() {
    $theme = theme_config::load('trema');
    $cards_settings = array();
    
    $numberofcards = get_config('theme_trema', 'numberofcards');
    if(get_config('theme_trema', 'frontpageenablecards') &&  $numberofcards > 1) {
        for ($i = 1; $i <= $numberofcards; $i++) {
            $card_settings = new stdClass();
            $card_settings->cardicon = $theme->settings->{'cardicon'.$i};
            $card_settings->cardiconcolor = $theme->settings->{'cardiconcolor'.$i};
            $card_settings->cardtitle = $theme->settings->{'cardtitle'.$i};
            $card_settings->cardsubtitle = $theme->settings->{'cardsubtitle'.$i};
            $card_settings->cardlink = $theme->settings->{'cardlink'.$i};
            
            $cards_settings[] = $card_settings;
        }
        
        return $cards_settings;
    } else {
        return false;
    } 
}