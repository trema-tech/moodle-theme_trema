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
 * Version file.
 *
 * @package     theme_trema
 * @copyright   2019-2025 Trema - {@link https://trema.tech/}
 * @copyright   2023-2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2025052200;
$plugin->release   = '5.0.0.2';
$plugin->maturity  = MATURITY_STABLE;
$plugin->requires  = 2022021800; // Moodle 4.0 - Build: 20220218.
$plugin->component = 'theme_trema';
$plugin->dependencies = [
    'theme_boost'  => 2018051400,
];
