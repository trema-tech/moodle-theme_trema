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
 * Lib file.
 *
 * @package     theme_trema
 * @copyright   2019-2024 Trema - {@link https://trema.tech/}
 * @copyright   2023-2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme
 *            The theme config object.
 * @return string
 */
function theme_trema_get_pre_scss($theme) {
    global $CFG;

    $scss = '';

    $scss .= file_get_contents("$CFG->dirroot/theme/trema/scss/defaultvariables.scss");

    $configurable = [
        // Target SCSS variable name => Trema theme setting.
        'primary' => 'primarycolor',
        'secondary' => 'secondarycolor',
        'body-bg-color' => 'bodybackgroundcolor',
        'body-font-family' => 'sitefont',
        'h1-font-family' => 'h1font',
        'hx-font-family' => 'hxfont',
        'text-transform' => 'texttransform',
        'banner-title-transform' => 'bannertitletransform',
        'banner-title-spacing' => 'bannertitlespacing',
        'custom-menu-alignment' => 'custommenualignment',
        'links-decoration' => 'linkdecoration',
        'dropdown-bg-color' => 'bodybackgroundcolor',
        'footer-opacity' => 'footeropacity',
    ];

    // Prepend variables first.
    foreach ($configurable as $scssvar => $themesetting) {
        if (isset($theme->settings->{$themesetting})) {
            $value = $theme->settings->{$themesetting};
        } else {
            continue;
        }
        $scss .= '$' . $scssvar . ': ' . $value . ";\n";
    }

    // ....
    // Colors
    // ....

    {
    // Background color of page header.
    $headerbgcolor = get_config('theme_trema', 'headerbgcolor');
    if (strpos('$#', substr($headerbgcolor, 0, 1)) === false) {
        $headerbgcolor = get_config('theme_trema', $headerbgcolor);
    }
    $scss .= '$header-bg-color: ' . $headerbgcolor . " !default;\n";

    // Background color of Log In button in page header.
    $loginbtnbgcolor = get_config('theme_trema', 'loginbtnbgcolor');
    if (strpos('$#', substr($loginbtnbgcolor, 0, 1)) === false) {
        $loginbtnbgcolor = get_config('theme_trema', $loginbtnbgcolor);
    }
    $scss .= '$loginbtn-bg-color: ' . $loginbtnbgcolor . " !default;\n";

    // Background color of drawers.
    $drawerbgcolor = get_config('theme_trema', 'drawerbgcolor');
    if (strpos('$#', substr($drawerbgcolor, 0, 1)) === false) {
        $drawerbgcolor = get_config('theme_trema', $drawerbgcolor);
    }
    $scss .= '$drawer-bg-color: ' . $drawerbgcolor . " !default;\n";

    // Background color of footer.
    $footerbgcolor = get_config('theme_trema', 'footerbgcolor');
    if (strpos('$#', substr($footerbgcolor, 0, 1)) === false) {
        $footerbgcolor = get_config('theme_trema', $footerbgcolor);
    }
    $scss .= '$footer-bg-color: ' . $footerbgcolor . " !default;\n";
    }

    // ....
    // Fonts
    // ....

    $fonts = [
        'Arial, Helvetica, sans-serif' => 'Arial',
        'Verdana, Tahoma, sans-serif' => 'Verdana',
        '"Times New Roman", Times, serif' => 'TimesNewRoman',
        'Georgia, serif' => 'Georgia',
        '"Courier New", Courier, monospace' => 'CourierNew',
        'Alegreya, serif' => 'Alegreya',
        '"CrimsonText", serif' => 'CrimsonText',
        '"EBGaramond", sans-serif' => 'EBGaramond',
        'Lato, sans-serif' => 'Lato',
        'Montserrat, sans-serif' => 'Montserrat',
        '"NotoSans", sans-serif' => 'NotoSans',
        '"OpenSans", sans-serif' => 'OpenSans',
        '"PlayfairDisplay", serif' => 'PlayfairDisplay',
        'Poppins, sans-serif' => 'Poppins',
        'Roboto, Arial, Helvetica, sans-serif' => 'Roboto',
    ];

    $scss .= '$bodyfontfile: "' . $fonts[$theme->settings->sitefont] . '";' . PHP_EOL;
    $scss .= '$h1fontfile: "' . $fonts[$theme->settings->h1font] . '";' . PHP_EOL;
    $scss .= '$hxfontfile: "' . $fonts[$theme->settings->hxfont] . '";' . PHP_EOL;

    // ....
    // Show/hide User profile fields.
    // ....

    $fields = [];

    // Section: General.
    $fields['showprofileemaildisplay'] = '#fitem_id_maildisplay'; // Email display.
    if (empty($theme->settings->showmoodlenetprofile)) {
        $fields['showmoodlenetprofile'] = '#fitem_id_moodlenetprofile'; // MoodleNet Profile.
    }
    $fields['showprofilecity'] = '#fitem_id_city'; // City.
    $fields['showprofilecountry'] = '#fitem_id_country'; // Country.
    $fields['showprofiletimezone'] = '#fitem_id_timezone'; // Timezone.
    $fields['showprofiledescription'] = '#fitem_id_description_editor'; // Description.

    // Section: User Picture.
    $fields['showprofilepictureofuser'] = '#id_moodle_picture';

    // Section: Additional Names.
    $fields['showprofileadditionalnames'] = '#id_moodle_additional_names';

    // Section: Interests.
    $fields['showprofileinterests'] = '#id_moodle_interests';

    // Section: Optional.
    $fields['showprofileoptional'] = '#id_moodle_optional';

    // MoodleNet profile ID.
    $fields['showprofilemoodlenetprofile'] = '#fitem_id_moodlenetprofile';
    // ID number.
    $fields['showprofileidnumber'] = '#fitem_id_idnumber';
    // Institution.
    $fields['showprofileinstitution'] = '#fitem_id_institution';
    // Department.
    $fields['showprofiledepartment'] = '#fitem_id_department';
    // Phone.
    $fields['showprofilephone1'] = '#fitem_id_phone1';
    // Mobile phone.
    $fields['showprofilephone2'] = '#fitem_id_phone2';
    // Address.
    $fields['showprofileaddress'] = '#fitem_id_address';

    //
    // Show/hide other elements.
    //

    // Activity module icons.
    $fields['showactivityicons'] = '.page-header-image,.activityiconcontainer.courseicon';

    // Login form.
    $fields['loginshowloginform'] = '#login, .loginform .login-form, .login-form-forgotpassword form-group';

    // User menu - Hide the Logout link.
    $fields['showumlogoutlink'] = '#carousel-item-main a:last-of-type, #carousel-item-main .dropdown-divider:last-of-type';

    // Links to Moodle 'Page' activities on Frontpage unless in edit mode on the front page.
    $fields['showfrontpagelinkstopages'] = '#page-site-index:not(.editing) #page-content .modtype_page';

    // Moodle branding.
    $fields['showbranding'] = '.sitelink,.footer-section.p-3:not(.border-bottom)';

    $customscss = '';
    // Automatically hide guest login button if Auto-login Guests is enabled and Guest Login button is visible.
    if (!empty($CFG->autologinguests) && !empty($CFG->guestloginbutton)) {
        $customscss .= '#guestlogin,';
    }

    // ....
    // Combine all of the fields that we need to hide.
    // ....

    foreach ($fields as $setting => $field) {
        if (empty($theme->settings->$setting)) {
            $customscss .= $field . ',' . PHP_EOL;
        }
    }
    // If there is something to hide, hide it.
    if (!empty($customscss)) {
        $scss .= $customscss . 'displaynone {display: none;}';
    }

    // ....
    // Login page
    // ....

    // Background image.
    $backgroundimageurl = $theme->setting_file_url('loginbackgroundimage', 'loginbackgroundimage');
    if ($theme->settings->loginpagestyle == 'image' && !empty($backgroundimageurl)) {
        $scss .= "\$login-backgroundimage: '$backgroundimageurl';\n";
    } else {
        $scss .= "\$login-backgroundimage: '[[pix:theme|frontpage/banner]]';\n";
    }

    // Not image in settings.
    if ($theme->settings->loginpagestyle !== 'image') {
        $scss .= "body.pagelayout-login #page-wrapper { background-image: none; }\n";
    }

    // ....
    // Other settings.
    // ....

    // Softness: Rounding some corners.
    $scss .= '$softness: ' . (!empty($theme->settings->softness) ? '.5rem' : '0') . ";\n";

    // ....
    // Prepend pre-scss.
    // ....

    if (! empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Load the main SCSS and the frontpage banner.
 *
 * @param theme_config $theme
 *            The theme config object.
 * @return string
 */
function theme_trema_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';

    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : 'default.scss';
    $scss .= file_get_contents("$CFG->dirroot/theme/trema/scss/preset/{$filename}");

    if (!empty($theme->settings->enabletrematopics)) {
        $scss .= file_get_contents("$CFG->dirroot/theme/trema/scss/trema/topics.scss");
    }

    if (empty($theme->settings->enabletremalines)) {
        $scss .= "%border-frequency { &:before, &:after { content: none !important;}}";
    }

    // Frontpage banner.
    if (!empty($theme->settings->frontpageenabledarkoverlay)) {
        $darkoverlay = "url([[pix:theme|frontpage/overlay]]),";
    } else {
        $darkoverlay = "";
    }

    if ($frontpagebannerurl = $theme->setting_file_url('frontpagebanner', 'frontpagebanner')) {
        $scss .= "#frontpage-banner {background-image: $darkoverlay url('$frontpagebannerurl');}";
    } else {
        $scss .= "#frontpage-banner {background-image: $darkoverlay url([[pix:theme|frontpage/banner]]);}";
    }

    return $scss;
}

/**
 * Inject additional SCSS for images.
 *
 * @param theme_config $theme Theme config object.
 * @return string
 */
function theme_trema_get_extra_scss($theme) {
    $scss = '';

    // Sets the background image and its settings.
    $imageurl = $theme->setting_file_url('backgroundimage', 'backgroundimage');
    if (!empty($imageurl)) {
        $scss .= '@media (min-width: 768px) { ';
        $scss .= '    body { ';
        $scss .= '        background-image: url("' . $imageurl . '"); ';
        $scss .= '        background-size: cover; ';
        $scss .= '        background-attachment: fixed; ';
        $scss .= '    } ';
        $scss .= "}\n";
    }

    // Always return the background image with the scss when we have it.
    return !empty($theme->settings->scss) ? "{$theme->settings->scss}  \n  {$scss}" : $scss;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_trema_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        $theme = theme_config::load('trema');
        // By default, theme files must be cache-able by both browsers and proxies.
        if (! array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}
