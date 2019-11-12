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
 *
 * @package     theme_trema
 * @copyright   2019 Trema - {@link https://trema.tech/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
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

// Frontpage title.
$page->add(
    new admin_setting_configtext('theme_trema/frontpagetitle', new lang_string('frontpagetitle', 'theme_trema'), '',
        'Lorem ipsum, dolor sit amet'));

// Frontpage subtitle.
$page->add(
    new admin_setting_configtext('theme_trema/frontpagesubtitle', new lang_string('frontpagesubtitle', 'theme_trema'), '',
        'Ut enim ad minim veniam,<br> quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'));

// Frontpage button text.
$page->add(
    new admin_setting_configtext('theme_trema/frontpagebuttontext', new lang_string('frontpagebuttontext', 'theme_trema'), '',
        'Learn more'));

// Frontpage button href.
$page->add(
    new admin_setting_configtext('theme_trema/frontpagebuttonhref', new lang_string('frontpagebuttonhref', 'theme_trema'),
        new lang_string('frontpagebuttonhref_desc', 'theme_trema'), '#frontpage-cards'));

// Frontpage button class.
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
$setting = new admin_setting_configselect('theme_trema/frontpagebuttonclass',
    new lang_string('frontpagebuttonclass', 'theme_trema'), new lang_string('frontpagebuttonclass_desc', 'theme_trema'),
    'btn-primary', $choices);
$page->add($setting);

// HTML to include in the main content of frontpage.
$setting = new admin_setting_confightmleditor('theme_trema/defaultfrontpagebody', get_string('defaultfrontpagebody', 'theme_trema'),
    get_string('defaultfrontpagebody_desc', 'theme_trema'), '', PARAM_RAW);
$page->add($setting);

// Frontpage cards.
$page->add(new admin_setting_heading('theme_trema_cards', get_string('frontpagecards', 'theme_trema'), '', FORMAT_MARKDOWN));

// Enable/disable frontpage cards.
$name = 'theme_trema/frontpageenablecards';
$title = get_string('frontpageenablecards', 'theme_trema');
$description = get_string('frontpageenablecards_desc', 'theme_trema', "$CFG->wwwroot/theme/trema/pix/examples/cards.png");
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$page->add($setting);

if (get_config('theme_trema', 'frontpageenablecards')) {
    // Title.
    $name = 'theme_trema/frontpagecardstitle';
    $title = get_string('title', 'theme_trema');
    $description = '';
    $page->add(new admin_setting_configtext($name, $title, $description, 'MAGNA ETIAM ADIPISCING'));

    // Subtitle.
    $name = 'theme_trema/frontpagecardssubtitle';
    $title = get_string('subtitle', 'theme_trema');
    $description = '';
    $page->add(
        new admin_setting_configtext($name, $title, $description,
            'Consequat sed ultricies rutrum. Sed adipiscing eu amet utem blandit vis ac commodo aliquet vulputate.'));

    // Number of cards.
    $name = 'theme_trema/numberofcards';
    $title = get_string('numberofcards', 'theme_trema');
    $description = '';
    $default = 4;
    $choices = array(
        2 => '2',
        4 => '4',
        6 => '6'
    );
    $page->add(new admin_setting_configselect($name, $title, $description, $default, $choices));

    $numberofcards = get_config('theme_trema', 'numberofcards');
    for ($i = 1; $i <= $numberofcards; $i ++) {
        // Card header.
        $page->add(new admin_setting_heading('theme_trema_card' . $i, get_string('card', 'theme_trema') . $i, ''));

        // Card icon.
        $name = 'theme_trema/cardicon' . $i;
        $title = get_string('cardicon', 'theme_trema') . $i;
        $description = get_string('cardicon_desc', 'theme_trema');
        $page->add(new admin_setting_configtext($name, $title, $description, 'fa-paper-plane'));

        // Card icon color.
        $name = 'theme_trema/cardiconcolor' . $i;
        $title = get_string('cardiconcolor', 'theme_trema') . $i;
        $description = '';
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '#000000');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Card title.
        $name = 'theme_trema/cardtitle' . $i;
        $title = get_string('cardtitle', 'theme_trema') . $i;
        $description = '';
        $page->add(new admin_setting_configtext($name, $title, $description, 'MAGNA ETIAM'));

        // Card description.
        $name = 'theme_trema/cardsubtitle' . $i;
        $title = get_string('cardsubtitle', 'theme_trema') . $i;
        $description = '';
        $page->add(
            new admin_setting_configtext($name, $title, $description,
                'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem accusantium! Harum optio, volup.'));

        // Card link.
        $name = 'theme_trema/cardlink' . $i;
        $title = get_string('cardlink', 'theme_trema') . $i;
        $description = '';
        $page->add(new admin_setting_configtext($name, $title, $description, ''));
    }
}

// Courses cards.
$page->add(new admin_setting_heading('theme_trema_course_cards', get_string('coursescards', 'theme_trema'), '', FORMAT_MARKDOWN));

// Summary type.
$choices = array(
    "modal" => "modal",
    "popover" => "popover"
);
$setting = new admin_setting_configselect('theme_trema/summarytype',
    new lang_string('summarytype', 'theme_trema'), new lang_string('summarytype_desc', 'theme_trema'),
    'btn-primary', $choices);
$page->add($setting);

$settings->add($page);
