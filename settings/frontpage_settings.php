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
 * @copyright   2019-2024 Trema - {@link https://trema.tech/}
 * @copyright   2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Frontpage settings.
$page = new admin_settingpage('theme_trema_frontpagecontent', get_string('frontpage', 'theme_trema'));

// Frontpage image.
$name = 'theme_trema_frontpageimages';
$title = get_string('frontpageimages', 'theme_trema');
$description = '';
$format = FORMAT_MARKDOWN;
$setting = new admin_setting_heading($name, $title, $description, $format);
$page->add($setting);

$name = 'theme_trema/numberofimages';
$title = get_string('numberofimages', 'theme_trema');
$description = get_string('numberofimages_desc', 'theme_trema');
$choices = [
    0 => '0',
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
];
$default = 1; // Carousel disable.
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Frontpage button class.
$btnchoices = [
    "btn-primary" => "btn-primary",
    "btn-secondary" => "btn-secondary",
    "btn-success" => "btn-success",
    "btn-danger" => "btn-danger",
    "btn-warning" => "btn-warning",
    "btn-info" => "btn-info",
    "btn-light" => "btn-light",
    "btn-dark" => "btn-dark",
];

$numberofcarousel = get_config('theme_trema', 'numberofimages');

$frontpagetitledefault = get_string('frontpagetitle_default', 'theme_trema');
$frontpagesubtitledefault = get_string('frontpagesubtitle_default', 'theme_trema');
$frontpagebuttontextdefault = get_string('frontpagebuttontext_default', 'theme_trema');
$cardtitledefault = get_string('cardtitle', 'theme_trema');
$cardsubtitledefault = get_string('cardsubtitle', 'theme_trema');

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
    $name = 'theme_trema/frontpagetitle';
    $title = get_string('frontpagetitle', 'theme_trema');
    $description = '';
    $default = $frontpagetitledefault;
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Frontpage subtitle.
    $name = 'theme_trema/frontpagesubtitle';
    $title = get_string('frontpagesubtitle', 'theme_trema');
    $description = '';
    $default = $frontpagesubtitledefault;
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Frontpage button text.
    $name = 'theme_trema/frontpagebuttontext';
    $title = get_string('frontpagebuttontext', 'theme_trema');
    $description = '';
    $default = $frontpagebuttontextdefault;
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Frontpage button link.
    $name = 'theme_trema/frontpagebuttonhref';
    $title = get_string('frontpagebuttonhref', 'theme_trema');
    $description = get_string('frontpagebuttonhref_desc', 'theme_trema');
    $default = '#frontpage-cards';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Frontpage button class.
    $name = 'theme_trema/frontpagebuttonclass';
    $title = get_string('frontpagebuttonclass', 'theme_trema');
    $description = get_string('frontpagebuttonclass_desc', 'theme_trema');
    $default = 'btn-primary';
    $choices = $btnchoices;
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
} else if ($numberofcarousel >= 2) {
    for ($i = 1; $i <= $numberofcarousel; $i++) {
        $name = 'theme_trema_frontpageimage' . $i;
        $title = get_string('frontpageimage', 'theme_trema', $i);
        $description = '';
        $format = FORMAT_MARKDOWN;
        $setting = new admin_setting_heading($name, $title, $description, $format);
        $page->add($setting);

        // Carousel image.
        $name = 'theme_trema/frontpageimage' . $i;
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
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Carousel description.
        $name = 'theme_trema/carrouselsubtitle' . $i;
        $title = get_string('subtitle', 'theme_trema') . " $i";
        $description = get_string('subtitle_desc', 'theme_trema', $i);
        $default = get_string('frontpagesubtitle_default', 'theme_trema');
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Carousel button text.
        $name = 'theme_trema/carrouselbtntext' . $i;
        $title = get_string('carrouselbtntext', 'theme_trema', $i);
        $description = get_string('carrouselbtntext_desc', 'theme_trema', $i);
        $default = get_string('frontpagebuttontext_default', 'theme_trema');
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Carousel button link.
        $name = 'theme_trema/carrouselbtnhref' . $i;
        $title = get_string('carrouselbtnhref', 'theme_trema', $i);
        $description = get_string('carrouselbtnhref_desc', 'theme_trema', $i);
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Carousel button class.
        $name = 'theme_trema/carrouselbtnclass' . $i;
        $title = get_string('carrouselbtnclass', 'theme_trema', $i);
        $description = get_string('carrouselbtnclass_desc', 'theme_trema', $i);
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
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

$name = 'theme_trema/defaultfrontpagebody';
$title = get_string('defaultfrontpagebody', 'theme_trema');
$description = get_string('defaultfrontpagebody_desc', 'theme_trema');
$default = '';
$format = PARAM_RAW;
$setting = new admin_setting_confightmleditor($name, $title, $description, $default, $format);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Show/Hide links to Pages activities on Frontpage. Useful for creating site pages that are available when logged out.
$name = 'theme_trema/showfrontpagelinkstopages';
$title = get_string('showfrontpagelinkstopages', 'theme_trema');
$description = get_string('showfrontpagelinkstopages_desc', 'theme_trema');
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Frontpage cards.

$name = 'theme_trema_cards';
$title = get_string('frontpagecards', 'theme_trema');
$description = '';
$format = FORMAT_MARKDOWN;
$setting = new admin_setting_heading($name, $title, $description, $format);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Enable/disable frontpage cards.
$name = 'theme_trema/frontpageenablecards';
$title = get_string('frontpageenablecards', 'theme_trema');
$description = get_string('frontpageenablecards_desc', 'theme_trema', "$CFG->wwwroot/theme/trema/pix/examples/cards.png");
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

if (get_config('theme_trema', 'frontpageenablecards')) {
    // Title.
    $name = 'theme_trema/frontpagecardstitle';
    $title = get_string('title', 'theme_trema');
    $description = '';
    $default = get_string('cardtitle', 'theme_trema');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Subtitle.
    $name = 'theme_trema/frontpagecardssubtitle';
    $title = get_string('subtitle', 'theme_trema');
    $description = '';
    $default = get_string('cardsubtitle', 'theme_trema');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Number of cards.
    $name = 'theme_trema/numberofcards';
    $title = get_string('numberofcards', 'theme_trema');
    $description = '';
    $default = 4;
    $choices = [
        2 => '2',
        4 => '4',
        6 => '6',
    ];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $numberofcards = get_config('theme_trema', 'numberofcards');
    for ($i = 1; $i <= $numberofcards; $i++) {
        // Card header.
        $name = 'theme_trema_card' . $i;
        $title = get_string('card', 'theme_trema') . ' ' . $i;
        $description = '';
        $format = FORMAT_MARKDOWN;
        $setting = new admin_setting_heading($name, $title, $description, $format);
        $page->add($setting);

        // Card icon.
        $name = 'theme_trema/cardicon' . $i;
        $title = get_string('cardicon', 'theme_trema') . ' ' . $i;
        $description = get_string('cardicon_desc', 'theme_trema');
        $default = 'fa-paper-plane';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Card icon color.
        $name = 'theme_trema/cardiconcolor' . $i;
        $title = get_string('cardiconcolor', 'theme_trema') . ' ' . $i;
        $description = '';
        $default = '#000000';
        $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Card title.
        $name = 'theme_trema/cardtitle' . $i;
        $title = $cardtitledefault . ' ' . $i;
        $description = '';
        $default = get_string('cardtitle_default', 'theme_trema') .  ' ' . $i;
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Card description.
        $name = 'theme_trema/cardsubtitle' . $i;
        $title = $cardsubtitledefault . ' ' . $i;
        $description = '';
        $default = get_string('cardsubtitle_default', 'theme_trema');
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Card link.
        $name = 'theme_trema/cardlink' . $i;
        $title = get_string('cardlink', 'theme_trema') . ' ' . $i;
        $description = '';
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    }
}

$settings->add($page);
