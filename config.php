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
 * Config file.
 *
 * @package     theme_trema
 * @copyright   2019-2024 Trema - {@link https://trema.tech/}
 * @copyright   2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Check the file is being called internally from within Moodle.
defined('MOODLE_INTERNAL') || die();

// Call the theme lib file.
require_once(__DIR__ . '/lib.php');

// Theme name.
$THEME->name = 'trema';

// Inherit from parent theme - Boost.
$THEME->parents = ['boost'];

// Call main theme scss.
$THEME->scss = function ($theme) {
    return theme_trema_get_main_scss_content($theme);
};

// Docking is not currently supported in Boost family themes.
$THEME->enable_dock = false;

// Override renderes from core.
$THEME->rendererfactory = 'theme_overridden_renderer_factory';

// Additional theme options.
$THEME->supportscssoptimisation = false;
$THEME->yuicssmodules = [];
$THEME->requiredblocks = '';

$THEME->layouts = [
    // Most backwards compatible layout without the blocks - this is the layout used by default.
    'base' => [
        'file' => 'drawers.php',
        'regions' => [],
    ],
    // Standard layout with blocks, this is recommended for most pages with general information.
    'standard' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Main course page.
    'course' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
        'options' => ['langmenu' => true],
    ],
    'coursecategory' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Part of course, typical for modules - default page layout if $cm specified in require_login().
    'incourse' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // The site home page.
    'frontpage' => [
        'file' => 'frontpage.php',
        'regions' => ['side-pre', 'side-admin'],
        'defaultregion' => 'side-pre',
        'options' => ['nonavbar' => true],
    ],
    // Server administration scripts.
    'admin' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // My dashboard page.
    'mydashboard' => [
        'file' => 'mydashboard.php',
        'regions' => ['side-pre', 'side-admin'],
        'defaultregion' => 'side-pre',
        'options' => ['nonavbar' => true, 'langmenu' => true, 'nocontextheader' => true],
    ],
    // My public page.
    'mypublic' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    'login' => [
        'file' => 'login.php',
        'regions' => [],
        'options' => ['langmenu' => true],
    ],

    // Pages that appear in pop-up windows - no navigation, no blocks, no header.
    'popup' => [
        'file' => 'columns1.php',
        'regions' => [],
        'options' => ['nofooter' => true, 'nonavbar' => true],
    ],
    // No blocks and minimal footer - used for legacy frame layouts only!
    'frametop' => [
        'file' => 'columns1.php',
        'regions' => [],
        'options' => ['nofooter' => true, 'nocoursefooter' => true],
    ],
    // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible.
    'embedded' => [
        'file' => 'embedded.php',
        'regions' => [],
    ],
    // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
    // This must not have any blocks, links, or API calls that would lead to database or cache interaction.
    // Please be extremely careful if you are modifying this layout.
    'maintenance' => [
        'file' => 'maintenance.php',
        'regions' => [],
    ],
    // Should display the content and basic headers only.
    'print' => [
        'file' => 'columns1.php',
        'regions' => [],
        'options' => ['nofooter' => true, 'nonavbar' => false],
    ],
    // The pagelayout used when a redirection is occuring.
    'redirect' => [
        'file' => 'embedded.php',
        'regions' => [],
    ],
    // The pagelayout used for reports.
    'report' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // The pagelayout used for safebrowser and securewindow.
    'secure' => [
        'file' => 'secure.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
];

// Remove some of the default primary navigation items.
$THEME->removedprimarynavitems = explode(',', get_config('theme_trema', 'hideprimarynavigationitems'));
$THEME->extrascsscallback = 'theme_trema_get_extra_scss';
$THEME->prescsscallback = 'theme_trema_get_pre_scss';
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;
$THEME->haseditswitch = true;
$THEME->usescourseindex = empty($THEME->settings->shownactivitynavigation);
// By default, all boost theme do not need their titles displayed.
$THEME->activityheaderconfig = [
    'notitle' => true,
];
