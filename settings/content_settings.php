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

// Frontpage image.
$page->add(new admin_setting_heading('theme_trema_frontpageimages',
    get_string('frontpageimages', 'theme_trema'), '', FORMAT_MARKDOWN));
$name = 'theme_trema/numberofimages';
$title = get_string('numberofimages', 'theme_trema');
$description = get_string('numberofimages_desc', 'theme_trema');
$default = 1; // Carousel disable.
$choices = [
    0 => '0',
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5'
];
$page->add(new admin_setting_configselect($name, $title, $description, $default, $choices));

// Frontpage button class.
$btnchoices = array(
    "btn-primary" => "btn-primary",
    "btn-secondary" => "btn-secondary",
    "btn-success" => "btn-success",
    "btn-danger" => "btn-danger",
    "btn-warning" => "btn-warning",
    "btn-info" => "btn-info",
    "btn-light" => "btn-light",
    "btn-dark" => "btn-dark"
);

$numberofcarousel = get_config('theme_trema', 'numberofimages');

$frontpagetitledefault      = get_string('frontpagetitle_default', 'theme_trema');
$frontpagesubtitledefault   = get_string('frontpagesubtitle_default', 'theme_trema');
$frontpagebuttontextdefault = get_string('frontpagebuttontext_default', 'theme_trema');
$cardtitledefault           = get_string('cardtitle', 'theme_trema');
$cardsubtitledefault        = get_string('cardsubtitle', 'theme_trema');

// Set some settings so that they can initially have a default value but later be set blank.
if ($numberofcarousel === false) { // Not set yet.
    // Initialize some default values.
    $numberofcarousel = 1;
    set_config('frontpagetitledefault', $frontpagetitledefault, 'theme_trema');
    set_config('frontpagesubtitledefault', $frontpagesubtitledefault, 'theme_trema');
    set_config('frontpagebuttontextdefault', $frontpagebuttontextdefault, 'theme_trema');
    set_config('cardtitledefault', $cardtitledefault, 'theme_trema');
}

if ($numberofcarousel == 1) {
    // Frontpage single banner.
    $name = 'theme_trema/frontpagebanner';
    $title = get_string('frontpagebanner', 'theme_trema');
    $description = get_string('frontpagebanner_desc', 'theme_trema');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'frontpagebanner');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Frontpage title.
    $page->add(
        new admin_setting_configtext('theme_trema/frontpagetitle', get_string('frontpagetitle', 'theme_trema'), '',
        $frontpagetitledefault));

    // Frontpage subtitle.
    $page->add(
        new admin_setting_configtext('theme_trema/frontpagesubtitle', get_string('frontpagesubtitle', 'theme_trema'), '',
        $frontpagesubtitledefault));

    // Frontpage button text.
    $page->add(
        new admin_setting_configtext('theme_trema/frontpagebuttontext', get_string('frontpagebuttontext', 'theme_trema'), '',
        $frontpagebuttontextdefault));

    // Frontpage button link.
    $page->add(
        new admin_setting_configtext('theme_trema/frontpagebuttonhref', get_string('frontpagebuttonhref', 'theme_trema'),
            get_string('frontpagebuttonhref_desc', 'theme_trema'), '#frontpage-cards'));

    // Frontpage button class.
    $setting = new admin_setting_configselect('theme_trema/frontpagebuttonclass',
        get_string('frontpagebuttonclass', 'theme_trema'), get_string('frontpagebuttonclass_desc', 'theme_trema'),
        'btn-primary', $btnchoices);
    $page->add($setting);

} else if ($numberofcarousel >= 1) {

    for ($i = 1; $i <= $numberofcarousel; $i ++) {
        $page->add(new admin_setting_heading("theme_trema_frontpageimage{$i}" ,
            get_string('frontpageimage', 'theme_trema', $i), '', FORMAT_MARKDOWN));
        // Carousel image.
        $name = "theme_trema/frontpageimage{$i}";
        $title = get_string('image', 'theme_trema', $i);
        $description = get_string('frontpageimage_desc', 'theme_trema', $i);
        $setting = new admin_setting_configstoredfile($name, $title, $description, "frontpageimage{$i}");
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Carousel title.
        $name = 'theme_trema/carrouseltitle' . $i;
        $title = get_string('title', 'theme_trema') . " $i";
        $description = get_string('title_desc', 'theme_trema', $i);
        $default = get_string('frontpagetitle_default', 'theme_trema');
        $page->add(new admin_setting_configtext($name, $title, $description, $default));

        // Carousel description.
        $name = 'theme_trema/carrouselsubtitle' . $i;
        $title = get_string('subtitle', 'theme_trema') . " $i";
        $description = get_string('subtitle_desc', 'theme_trema', $i);
        $default = get_string('frontpagesubtitle_default', 'theme_trema');
        $page->add(new admin_setting_configtext($name, $title, $description, $default));

        // Carousel button text.
        $name = 'theme_trema/carrouselbtntext' . $i;
        $title = get_string('carrouselbtntext', 'theme_trema', $i);
        $description = get_string('carrouselbtntext_desc', 'theme_trema', $i);
        $default = get_string('frontpagebuttontext_default', 'theme_trema');
        $page->add(new admin_setting_configtext($name, $title, $description, $default));

        // Carousel button link.
        $name = 'theme_trema/carrouselbtnhref' . $i;
        $title = get_string('carrouselbtnhref', 'theme_trema', $i);
        $description = get_string('carrouselbtnhref_desc', 'theme_trema', $i);
        $page->add(new admin_setting_configtext($name, $title, $description, ''));

        // Carousel button class.
        $name = 'theme_trema/carrouselbtnclass' . $i;
        $title = get_string('carrouselbtnclass', 'theme_trema', $i);
        $description = get_string('carrouselbtnclass_desc', 'theme_trema', $i);
        $page->add(new admin_setting_configselect($name, $title, $description, 'btn-primary', $btnchoices));
    }
}

// Enable/disable dark overlay on banner.
$name = 'theme_trema/frontpageenabledarkoverlay';
$title = get_string('frontpageenabledarkoverlay', 'theme_trema');
$description = get_string('frontpageenabledarkoverlay_desc', 'theme_trema');
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
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
    $page->add(new admin_setting_configtext($name, $title, $description, get_string('cardtitle', 'theme_trema')));

    // Subtitle.
    $name = 'theme_trema/frontpagecardssubtitle';
    $title = get_string('subtitle', 'theme_trema');
    $description = '';
    $page->add(
        new admin_setting_configtext($name, $title, $description, get_string('cardsubtitle', 'theme_trema')));

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
        $page->add(new admin_setting_heading('theme_trema_card' . $i, get_string('card', 'theme_trema') . ' ' . $i, ''));

        // Card icon.
        $name = 'theme_trema/cardicon' . $i;
        $title = get_string('cardicon', 'theme_trema') . ' ' . $i;
        $description = get_string('cardicon_desc', 'theme_trema');
        $page->add(new admin_setting_configtext($name, $title, $description, 'fa-paper-plane'));

        // Card icon color.
        $name = 'theme_trema/cardiconcolor' . $i;
        $title = get_string('cardiconcolor', 'theme_trema') . ' ' . $i;
        $description = '';
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '#000000');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Card title.
        $name = 'theme_trema/cardtitle' . $i;
        $title = $cardtitledefault . ' ' . $i;
        $description = '';
        $page->add(new admin_setting_configtext($name, $title, $description,
            get_string('cardtitle_default', 'theme_trema') .  ' ' . $i));

        // Card description.
        $name = 'theme_trema/cardsubtitle' . $i;
        $title = $cardsubtitledefault . ' ' . $i;
        $description = '';
        $page->add(new admin_setting_configtext($name, $title, $description, get_string('cardsubtitle_default', 'theme_trema')));

        // Card link.
        $name = 'theme_trema/cardlink' . $i;
        $title = get_string('cardlink', 'theme_trema') . ' ' . $i;
        $description = '';
        $page->add(new admin_setting_configtext($name, $title, $description, ''));
    }
}

// Course Enrolment page.
$page->add(new admin_setting_heading('theme_trema_course_enrolmentpage', get_string('courseenrolmentpage', 'theme_trema'),
        '', FORMAT_MARKDOWN));

// Course enrolment page format.
$choices = array(
    "card" => get_string('courseenrolmentpagecard', 'theme_trema'),
    "fullwidth" => get_string('courseenrolmentpagefull', 'theme_trema')
);
$setting = new admin_setting_configselect('theme_trema/courseenrolmentpageformat',
    get_string('courseenrolmentpageformat', 'theme_trema'), get_string('courseenrolmentpageformat_desc', 'theme_trema'),
    'fullwidth', $choices);
$page->add($setting);

// Courses cards.
$page->add(new admin_setting_heading('theme_trema_course_cards', get_string('coursescards', 'theme_trema'), '', FORMAT_MARKDOWN));

// Summary type.
$choices = array(
    "modal" => "modal",
    "popover" => "popover"
);
$setting = new admin_setting_configselect('theme_trema/summarytype',
    get_string('summarytype', 'theme_trema'), get_string('summarytype_desc', 'theme_trema'),
    'btn-primary', $choices);
$page->add($setting);

// Show categories on Frontpage course cards.
$name = 'theme_trema/showcategories';
$title = get_string('showcategories', 'theme_trema');
$description = get_string('showcategories_desc', 'theme_trema');
$default = false;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$page->add($setting);

$settings->add($page);
