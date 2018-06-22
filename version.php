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
 * @package    theme_trema
 * @copyright  2018 Trevor Furtado e Rodrigo Mady
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2018062102;
$plugin->release  = 'v0.1';
$plugin->maturity  = MATURITY_ALPHA;
$plugin->requires  = 2018042500;
$plugin->component = 'theme_trema';
$plugin->dependencies = array(
    'theme_boost'  => 2017111300,
);
