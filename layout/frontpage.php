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
 * Frontpage file.
 *
 * @package     theme_trema
 * @copyright   2019 Trema - {@link https://trema.tech/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once("$CFG->dirroot/theme/trema/locallib.php");

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

$extraclasses = [];
$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));

$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
    $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs');
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions()  && !$PAGE->has_secondary_navigation();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

$adminblockshtml = $OUTPUT->blocks('side-admin');
$pluginsettings = get_config("theme_trema");
$numberofimages = $pluginsettings->numberofimages;

// Frontpage images.
if ($numberofimages > 1) {
    $frontpagecarrousel = [];
    $active = true;
    for ($i = 1; $i <= $numberofimages; $i++) {
        $title    = "carrouseltitle{$i}";
        $subtitle = "carrouselsubtitle{$i}";
        $btntext  = "carrouselbtntext{$i}";
        $btnhref  = "carrouselbtnhref{$i}";
        $btnclass = "carrouselbtnclass{$i}";
        $url      = theme_trema_setting_file_url("frontpageimage{$i}", "frontpageimage{$i}", $PAGE->theme);

        if (!empty($url)) {
            $frontpagecarrousel[$i]['image'] = $url;
        } else {
            $frontpagecarrousel[$i]['image'] = $OUTPUT->image_url('frontpage/banner2', 'theme');
        }
        $frontpagecarrousel[$i]['index']    = $i - 1;
        $frontpagecarrousel[$i]['title']    = !empty($pluginsettings->$title) ?
            format_text($pluginsettings->$title, FORMAT_HTML) : '';
        $frontpagecarrousel[$i]['subtitle'] = !empty($pluginsettings->$subtitle) ?
            format_text($pluginsettings->$subtitle, FORMAT_HTML) : '';
        $frontpagecarrousel[$i]['btntext']  = !empty($pluginsettings->$btntext) ?
            format_text($pluginsettings->$btntext, FORMAT_HTML) : '';
        $frontpagecarrousel[$i]['btnhref']  = !empty($pluginsettings->$btnhref) ?
            $pluginsettings->$btnhref : '';
        $frontpagecarrousel[$i]['btnclass'] = !empty($pluginsettings->$btnclass) ?
            $pluginsettings->$btnclass : '';
        // Must have just one slide active.
        if ($active) {
            $frontpagecarrousel[$i]['active'] = "active";
            $active = false;
        }
    }
    $frontpagecarrousel = array_values($frontpagecarrousel);
} else {
    $frontpagecarrousel = false;
}

$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
$context                = context_course::instance(SITEID);
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, [
        'context' => context_course::instance(SITEID),
        "escape" => false
    ]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'sideadminblocks' => $adminblockshtml,
    'hasblocks' => $hasblocks,
    'hasadminblocks' => is_siteadmin(),
    'bodyattributes' => $bodyattributes,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'headercontent' => $headercontent,
    'overflow' => $overflow,
    'addblockbutton' => $addblockbutton,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => ! empty($regionmainsettingsmenu),
    'defaultfrontpagebody' => !empty($pluginsettings->defaultfrontpagebody) ?
        format_text($pluginsettings->defaultfrontpagebody, FORMAT_HTML) : '',
    'defaultfrontpagefooter' => !empty($pluginsettings->defaultfooter) ?
        format_text($pluginsettings->defaultfooter, FORMAT_HTML) : '',
    'showbanner' => ($numberofimages > 0),
    'frontpagecarrousel' => $frontpagecarrousel,
    'frontpagetitle' => !empty($pluginsettings->frontpagetitle) ?
        format_text($pluginsettings->frontpagetitle, FORMAT_HTML) : '',
    'frontpagesubtitle' => !empty($pluginsettings->frontpagesubtitle) ?
        format_text($pluginsettings->frontpagesubtitle, FORMAT_HTML) : '',
    'frontpagebuttontext' => !empty($pluginsettings->frontpagebuttontext) ?
        format_text($pluginsettings->frontpagebuttontext, FORMAT_HTML) : '',
    'frontpagebuttonclass' => !empty($pluginsettings->frontpagebuttonclass) ?
        $pluginsettings->frontpagebuttonclass : '',
    'frontpagebuttonhref' => !empty($pluginsettings->frontpagebuttonhref) ?
        $pluginsettings->frontpagebuttonhref : '',
    'hascards' => !empty($pluginsettings->frontpageenablecards),
    'cardstitle' => !empty($pluginsettings->frontpagecardstitle) ?
        format_text($pluginsettings->frontpagecardstitle, FORMAT_HTML) : '',
    'cardssubtitle' => !empty($pluginsettings->frontpagecardssubtitle) ?
        format_text($pluginsettings->frontpagecardssubtitle, FORMAT_HTML) : '',
    'cardssettings' => theme_trema_get_cards_settings(),
    'enabletremafooter' => $pluginsettings->enabletremafooter,
    'footerinfo' => format_text($pluginsettings->enablefooterinfo, FORMAT_HTML, ['context' => $context]),
];

echo $OUTPUT->render_from_template('theme_trema/frontpage', $templatecontext);
