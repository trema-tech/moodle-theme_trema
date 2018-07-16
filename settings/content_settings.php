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
$page = new admin_settingpage('theme_trema_frontpagecontent', get_string('content', 'theme_trema'));

// Frontpage banner.
$name = 'theme_trema/frontpagebanner';
$title = get_string('frontpagebanner', 'theme_trema');
$description = get_string('frontpagebanner_desc', 'theme_trema');
$setting = new admin_setting_configstoredfile($name, $title, $description, 'frontpagebanner');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Frontpage title
$page->add(new admin_setting_configtext('theme_trema/frontpagetitle', new lang_string('frontpagetitle', 'theme_trema'), '', 'Lorem ipsum, dolor sit amet'));

// Frontpage subtitle
$page->add(new admin_setting_configtext('theme_trema/frontpagesubtitle', new lang_string('frontpagesubtitle', 'theme_trema'), '', 'Ut enim ad minim veniam,<br> quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'));

// Frontpage button text
$page->add(new admin_setting_configtext('theme_trema/frontpagebuttontext', new lang_string('frontpagebuttontext', 'theme_trema'), '', 'Learn more'));

// Frontpage button href
$page->add(new admin_setting_configtext('theme_trema/frontpagebuttonhref', new lang_string('frontpagebuttonhref', 'theme_trema'), new lang_string('frontpagebuttonhref_desc', 'theme_trema'), '#page-content'));

// Frontpage button class
$choices = array(
    "btn-primary" => "btn-primary",
    "btn-secondary" => "btn-secondary",
    "btn-success" => "btn-success",
    "btn-danger" => "btn-danger",
    "btn-warning" => "btn-warning",
    "btn-info" => "btn-info",
    "btn-light" => "btn-light",
    "btn-dark" => "btn-dark"
);
$setting = new admin_setting_configselect('theme_trema/frontpagebuttonclass', new lang_string('frontpagebuttonclass', 'theme_trema'), new lang_string('frontpagebuttonclass_desc', 'theme_trema'), 'btn-primary', $choices);
$page->add($setting);

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
$setting = new admin_setting_confightmleditor('theme_trema/defaultfooter',
    get_string('defaultfooter', 'theme_trema'), get_string('defaultfooter_desc', 'theme_trema'), $footer_html, PARAM_RAW);
$page->add($setting);

// Raw SCSS to include before the content.
$setting = new admin_setting_scsscode('theme_trema/scsspre',
    get_string('rawscsspre', 'theme_trema'), get_string('rawscsspre_desc', 'theme_trema'), '', PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Raw SCSS to include after the content.
$setting = new admin_setting_scsscode('theme_trema/scss', get_string('rawscss', 'theme_trema'),
    get_string('rawscss_desc', 'theme_trema'), '', PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$settings->add($page);
