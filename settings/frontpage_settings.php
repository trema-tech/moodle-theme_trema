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
 * Frontpage settings and HTML
 * @package   theme_trema
 * @copyright 2018 Trevor Furtado e Rodrigo Mady
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Advanced settings.
$page = new admin_settingpage('theme_trema_frontpagecontent', get_string('frontpagecontent', 'theme_trema'));

// HTML to include in the main content of frontpage.
$setting = new admin_setting_confightmleditor('theme_trema/defaultfrontpagebody',
    get_string('defaultfrontpagebody', 'theme_trema'), get_string('defaultfrontpagebody_desc', 'theme_trema'), '', PARAM_RAW);
$page->add($setting);

// HTML to include in the footer content of frontpage.
$footer_html = '<div class="container container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div id="footer-col1" class="col-lg-4 footer-col">
                <div class="footer-item">
                    <h3 class="-align-center">Trema Soluções em Tecnologia</h3>
    </div></div></div></div>';
$setting = new admin_setting_confightmleditor('theme_trema/defaultfrontpagefooter',
    get_string('defaultfrontpagefooter', 'theme_trema'), get_string('defaultfrontpagefooter_desc', 'theme_trema'), $footer_html, PARAM_RAW);
$page->add($setting);

$settings->add($page);
