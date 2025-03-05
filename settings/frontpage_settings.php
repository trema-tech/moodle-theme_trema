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
 * @copyright   2019-2025 Trema - {@link https://trema.tech/}
 * @copyright   2024-2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Frontpage settings.
$page = new admin_settingpage('theme_trema_frontpagecontent', get_string('frontpage', $themename));

// Frontpage image.
$name = 'theme_trema_frontpageimages';
$title = get_string('frontpageimages', $themename);
$description = '';
$format = FORMAT_MARKDOWN;
$setting = new admin_setting_heading($name, $title, $description, $format);
$page->add($setting);

$name = 'theme_trema/numberofimages';
$title = get_string('numberofimages', $themename);
$description = get_string('numberofimages_desc', $themename);
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

// Enable/disable dark banner overlay.
$name = 'theme_trema/frontpageenabledarkoverlay';
$title = get_string('frontpageenabledarkoverlay', $themename);
$description = get_string('frontpageenabledarkoverlay_desc', $themename);
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Frontpage banner text alignment.
$choices = [
    'left' => get_string('left', 'editor'),
    'center' => get_string('middle', 'editor'),
    'right' => get_string('right', 'editor'),
];
$name = 'theme_trema/frontpagebannercontentalign';
$title = get_string('frontpagebannercontentalign', $themename);
$description = get_string('frontpagebannercontentalign_desc', $themename);
$default = 'center';
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Frontpage banner height.
$choices = ['50vh' => '50%', '60vh' => '60%', '70vh' => '70%', '80vh' => '80%', '90vh' => '90%', '100vh' => '100%'];
$name = 'theme_trema/bannerheight';
$title = get_string('bannerheight', $themename);
$description = get_string('bannerheight_desc', $themename);
$default = '100vh';
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Frontpage button class.
$btnchoices = [
    'btn-primary' => 'btn-primary',
    'btn-secondary' => 'btn-secondary',
    'btn-success' => 'btn-success',
    'btn-danger' => 'btn-danger',
    'btn-warning' => 'btn-warning',
    'btn-info' => 'btn-info',
    'btn-light' => 'btn-light',
    'btn-dark' => 'btn-dark',
];

$numberofcarousel = get_config($themename, 'numberofimages');

$frontpagetitledefault = get_string('frontpagetitle_default', $themename);
$frontpagesubtitledefault = get_string('frontpagesubtitle_default', $themename);
$frontpagebuttontextdefault = get_string('frontpagebuttontext_default', $themename);
$cardtitledefault = get_string('cardtitle', $themename);
$cardsubtitledefault = get_string('cardsubtitle', $themename);

// Set some settings so that they can initially have a default value but later be set blank.
if ($numberofcarousel === false) { // Not set yet.
    // Initialize some default values.
    $numberofcarousel = 1;
    set_config('frontpagetitledefault', $frontpagetitledefault, $themename);
    set_config('frontpagesubtitledefault', $frontpagesubtitledefault, $themename);
    set_config('frontpagebuttontextdefault', $frontpagebuttontextdefault, $themename);
    set_config('cardtitledefault', $cardtitledefault, $themename);
}

if ($numberofcarousel == 1) {
    // Frontpage single banner.
    $name = 'theme_trema/frontpagebanner';
    $title = get_string('frontpagebanner', $themename);
    $description = get_string('frontpagebanner_desc', $themename);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'frontpagebanner');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Frontpage title.
    $name = 'theme_trema/frontpagetitle';
    $title = get_string('frontpagetitle', $themename);
    $description = '';
    $default = $frontpagetitledefault;
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Frontpage subtitle.
    $name = 'theme_trema/frontpagesubtitle';
    $title = get_string('frontpagesubtitle', $themename);
    $description = '';
    $default = $frontpagesubtitledefault;
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Frontpage button text.
    $name = 'theme_trema/frontpagebuttontext';
    $title = get_string('frontpagebuttontext', $themename);
    $description = '';
    $default = $frontpagebuttontextdefault;
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Frontpage button link.
    $name = 'theme_trema/frontpagebuttonhref';
    $title = get_string('frontpagebuttonhref', $themename);
    $description = get_string('frontpagebuttonhref_desc', $themename);
    $default = '#topofcontent';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Frontpage button class.
    $name = 'theme_trema/frontpagebuttonclass';
    $title = get_string('frontpagebuttonclass', $themename);
    $description = get_string('frontpagebuttonclass_desc', $themename);
    $default = 'btn-primary';
    $choices = $btnchoices;
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
} else if ($numberofcarousel >= 2) {
    for ($i = 1; $i <= $numberofcarousel; $i++) {
        $name = 'theme_trema_frontpageimage' . $i;
        $title = get_string('frontpageimage', $themename, $i);
        $description = '';
        $format = FORMAT_MARKDOWN;
        $setting = new admin_setting_heading($name, $title, $description, $format);
        $page->add($setting);

        // Carousel image.
        $name = 'theme_trema/frontpageimage' . $i;
        $title = get_string('image', $themename, $i);
        $description = get_string('frontpageimage_desc', $themename, $i);
        $setting = new admin_setting_configstoredfile($name, $title, $description, "frontpageimage{$i}");
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Carousel title.
        $name = 'theme_trema/carrouseltitle' . $i;
        $title = get_string('title', $themename) . " $i";
        $description = get_string('title_desc', $themename, $i);
        $default = get_string('frontpagetitle_default', $themename);
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Carousel description.
        $name = 'theme_trema/carrouselsubtitle' . $i;
        $title = get_string('subtitle', $themename) . " $i";
        $description = get_string('subtitle_desc', $themename, $i);
        $default = get_string('frontpagesubtitle_default', $themename);
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Carousel button text.
        $name = 'theme_trema/carrouselbtntext' . $i;
        $title = get_string('carrouselbtntext', $themename, $i);
        $description = get_string('carrouselbtntext_desc', $themename, $i);
        $default = get_string('frontpagebuttontext_default', $themename);
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Carousel button link.
        $name = 'theme_trema/carrouselbtnhref' . $i;
        $title = get_string('carrouselbtnhref', $themename, $i);
        $description = get_string('carrouselbtnhref_desc', $themename, $i);
        $default = '#topofcontent';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Carousel button class.
        $name = 'theme_trema/carrouselbtnclass' . $i;
        $title = get_string('carrouselbtnclass', $themename, $i);
        $description = get_string('carrouselbtnclass_desc', $themename, $i);
        $default = 'btn-primary';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    }
}

// Frontpage content.
$name = 'theme_trema_frontpagecontent';
$title = get_string('content');
$description = '';
$format = FORMAT_MARKDOWN;
$setting = new admin_setting_heading($name, $title, $description, $format);
$page->add($setting);

// HTML to include in the main content of frontpage.
$name = 'theme_trema/defaultfrontpagebody';
$title = get_string('defaultfrontpagebody', $themename);
$description = get_string('defaultfrontpagebody_desc', $themename);
$default = '';
$format = PARAM_RAW;
$setting = new admin_setting_confightmleditor($name, $title, $description, $default, $format);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Show/Hide links to Pages activities on Frontpage. Useful for creating site pages that are available when logged out.
$name = 'theme_trema/showfrontpagelinkstopages';
$title = get_string('showfrontpagelinkstopages', $themename);
$description = get_string('showfrontpagelinkstopages_desc', $themename);
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Frontpage cards.

$name = 'theme_trema_cards';
$title = get_string('frontpagecards', $themename);
$description = '';
$format = FORMAT_MARKDOWN;
$setting = new admin_setting_heading($name, $title, $description, $format);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Enable/disable frontpage cards.
$name = 'theme_trema/frontpageenablecards';
$title = get_string('frontpageenablecards', $themename);
$description = get_string('frontpageenablecards_desc', $themename, "$CFG->wwwroot/theme/trema/pix/examples/cards.png");
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

if (get_config($themename, 'frontpageenablecards')) {
    // Title.
    $name = 'theme_trema/frontpagecardstitle';
    $title = get_string('title', $themename);
    $description = '';
    $default = get_string('cardtitle', $themename);
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Subtitle.
    $name = 'theme_trema/frontpagecardssubtitle';
    $title = get_string('subtitle', $themename);
    $description = '';
    $default = get_string('cardsubtitle', $themename);
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Number of cards.
    $name = 'theme_trema/numberofcards';
    $title = get_string('numberofcards', $themename);
    $description = get_string('numberofcards_desc', $themename);
    $default = 4;
    $choices = [
        2 => '2',
        4 => '4',
        6 => '6',
    ];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $numberofcards = get_config($themename, 'numberofcards');
    for ($i = 1; $i <= $numberofcards; $i++) {
        // Card header.
        $name = 'theme_trema_card' . $i;
        $title = get_string('card', $themename) . ' ' . $i;
        $description = '';
        $format = FORMAT_MARKDOWN;
        $setting = new admin_setting_heading($name, $title, $description, $format);
        $page->add($setting);

        // Card icon.
        $name = 'theme_trema/cardicon' . $i;
        $title = get_string('cardicon', $themename) . ' ' . $i;
        $description = get_string('cardicon_desc', $themename);
        $default = 'fa-paper-plane';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Card icon color.
        $name = 'theme_trema/cardiconcolor' . $i;
        $title = get_string('cardiconcolor', $themename) . ' ' . $i;
        $description = '';
        $default = '#000000';
        $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Card title.
        $name = 'theme_trema/cardtitle' . $i;
        $title = $cardtitledefault . ' ' . $i;
        $description = '';
        $default = get_string('cardtitle_default', $themename) .  ' ' . $i;
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Card description.
        $name = 'theme_trema/cardsubtitle' . $i;
        $title = $cardsubtitledefault . ' ' . $i;
        $description = '';
        $default = get_string('cardsubtitle_default', $themename);
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Card link.
        $name = 'theme_trema/cardlink' . $i;
        $title = get_string('cardlink', $themename) . ' ' . $i;
        $description = '';
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    }
}

$settings->add($page);
