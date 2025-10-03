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
 * My dashboard file.
 *
 * @package     theme_trema
 * @copyright   2019-2025 Trema - {@link https://trema.tech/}
 * @copyright   2024-2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($CFG->branch <= 402) {
    user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
}
require_once($CFG->libdir . '/behat/lib.php');
require_once("$CFG->dirroot/theme/trema/locallib.php");

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();
$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();
$pluginsettings = get_config("theme_trema");

if (isloggedin()) {
    $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else if (isset($pluginsettings->frontpagedraweropen) && $pluginsettings->frontpagedraweropen) {
    $blockdraweropen = true;
} else {
    $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING') && get_user_preferences('behat_keep_drawer_closed') != 1) {
    $blockdraweropen = true;
}

$extraclasses = [];
$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
if (!$hasblocks) {
    $blockdraweropen = false;
}
$adminblockshtml = $OUTPUT->blocks('side-admin');

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

if ($CFG->branch > 400) {
    $primary = new theme_trema\output\primary_navigation($PAGE);
} else {
    $primary = new core\navigation\output\primary($PAGE);
}

$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions()  && !$PAGE->has_secondary_navigation();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

$pluginsettings = get_config("theme_trema");
$context = \context_system::instance();
$databs = $CFG->branch >= 500 ? 'bs-' : '';

$templatecontext = [
    'sitename' => \format_string($SITE->shortname, true, ['context' => $context, 'escape' => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasadminblocks' => is_siteadmin(),
    'sideadminblocks' => $adminblockshtml,
    'hasblocks' => $hasblocks,
    'blockdraweropen' => $blockdraweropen,
    'forceblockdraweropen' => $forceblockdraweropen,
    'showdashboardadmin' => false,
    'bodyattributes' => $bodyattributes,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'headercontent' => $headercontent,
    'overflow' => $overflow,
    'addblockbutton' => $addblockbutton,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'defaultfooter' => \format_text($pluginsettings->defaultfooter, FORMAT_HTML, ['context' => $context, 'noclean' => true]),
    'enabletremafooter' => $pluginsettings->enabletremafooter,
    'footerinfo' => !empty($pluginsettings->enablefooterinfo),
    'showbranding' => !empty($pluginsettings->showbranding),
    'databs' => $databs,
];

if (is_siteadmin() && !empty($pluginsettings->enableadmindashboard)) {
    $templatecontext['showdashboardadmin'] = true;

    // Get configured dashboard boxes.
    $boxids = [
        get_config('theme_trema', 'dashboardbox1') ?: 'diskusage',
        get_config('theme_trema', 'dashboardbox2') ?: 'courses',
        get_config('theme_trema', 'dashboardbox3') ?: 'enrolments',
        get_config('theme_trema', 'dashboardbox4') ?: 'security',
    ];

    // Use box manager to get boxes for rendering.
    $boxes = \theme_trema\dashboard\box_manager::get_boxes_for_rendering($boxids, $OUTPUT);
    $templatecontext['dashboardadmin'] = [
        'boxes' => $boxes,
        'colclass' => \theme_trema\dashboard\box_manager::get_column_class(count($boxes)),
    ];
}

echo $OUTPUT->render_from_template('theme_trema/mydashboard', $templatecontext);
