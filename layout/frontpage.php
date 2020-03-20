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
// TODO: add custom HTML area.

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');

if (isloggedin()) {
    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
} else {
    $navdraweropen = false;
}
$extraclasses = [];
if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$adminblockshtml = $OUTPUT->blocks('side-admin');
$pluginsettings = get_config("theme_trema");

$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
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
    'navdraweropen' => $navdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => ! empty($regionmainsettingsmenu),
    'defaultfrontpagebody' => format_text($pluginsettings->defaultfrontpagebody, FORMAT_HTML),
    'defaultfrontpagefooter' => format_text($pluginsettings->defaultfooter, FORMAT_HTML),
    'frontpagetitle' => format_text($pluginsettings->frontpagetitle, FORMAT_HTML),
    'frontpagesubtitle' => format_text($pluginsettings->frontpagesubtitle, FORMAT_HTML),
    'frontpagebuttontext' => format_text($pluginsettings->frontpagebuttontext, FORMAT_HTML),
    'frontpagebuttonclass' => $pluginsettings->frontpagebuttonclass,
    'frontpagebuttonhref' => $pluginsettings->frontpagebuttonhref,
    'hascards' => $pluginsettings->frontpageenablecards,
    'cardstitle' => format_text($pluginsettings->frontpagecardstitle, FORMAT_HTML),
    'cardssubtitle' => format_text($pluginsettings->frontpagecardssubtitle, FORMAT_HTML),
    'cardssettings' => theme_trema_get_cards_settings()
];

$templatecontext['flatnavigation'] = $PAGE->flatnav;
echo $OUTPUT->render_from_template('theme_trema/frontpage', $templatecontext);

