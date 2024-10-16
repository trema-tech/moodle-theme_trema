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
 * Core renderer.
 *
 * @package     theme_trema
 * @copyright   2019-2024 Trema - {@link https://trema.tech/}
 * @copyright   2023-2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_trema\output;

use custom_menu;
use stdClass;
use moodle_url;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/course/format/lib.php');

/**
 * Class core_renderer.
 *
 * @package theme_trema
 * @copyright   2019-2024 Trema - {@link https://trema.tech/}
 * @copyright   2023-2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @author      Michael Milette
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \theme_boost\output\core_renderer {
    /**
     * Returns the url of the favicon.
     *
     * Moodle's logo is not used as this would be a violation of their trademark.
     * Instead, we provide an option to upload your own favicon. If none has been uploaded,
     * then we fall back on using a pix/favicon.ico file in the theme folder if it exists.
     * Otherwise, the browser will default to the webserver's favicon.ico if it exists.
     * If none of these options are available, the web browser's default icon will be used.
     *
     * @return moodle_url|string
     */
    public function favicon() {
        global $CFG;
        static $favicon;

        // Return cached value if available.
        if (!empty($favicon)) {
            return $favicon;
        }

        // Use favicon configured in theme's settings.
        $favicon = $this->page->theme->setting_file_url('favicon', 'favicon');

        // If no favicon set in the theme, see if theme includeds a favicon.ico file.
        if (empty($favicon) && (file_exists($this->page->theme->dir . '/pix/favicon.ico'))) {
            // Use pix/favicon.ico stored in the theme directory.
            $favicon = $this->page->theme->image_url('favicon', 'theme');
        }

        // If no favicon found yet, check favicon settings in Moodle's Appearance/Logo settings.
        // Note: Only available in Moodle 4.1+.
        if (empty($favicon) && $CFG->branch >= 401) {
            // Use $CFG->themerev to prevent browser caching when the file changes.
            $favicon = \moodle_url::make_pluginfile_url(
                \context_system::instance()->id,
                'core_admin',
                'favicon',
                '64x64/',
                theme_get_revision(),
                get_config('core_admin', 'favicon')
            );
        }

        // If still no favicon found, fallback to the webserver's favicon.ico.
        if (empty($favicon)) {
            $parsedurl = parse_url($CFG->wwwroot);
            $favicon = $parsedurl['scheme'] . '://' . $parsedurl['host'] . '/favicon.ico';
            // If there isn't any, the browser will fallback to its own default favicon.
        }

        return $favicon;
    }

    /**
     * Always show the compact logo when its defined.
     *
     * @return bool
     */
    public function should_display_navbar_logo() {
        static $logo;

        if (!isset($logo)) {
            $logo = $this->get_compact_logo_url();
            $logo = !empty($logo);
        }
        return $logo;
    }

    /**
     * Return the frontpage settings menu.
     *
     * @return string HTML to display the main header.
     */
    public function frontpage_settings_menu() {
        $header = new stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        return $this->render_from_template('theme_trema/frontpage_settings_menu', $header);
    }

    /**
     * Renders the lang menu
     *
     * @param bool $showlang Optional false = just the globe, or 'showlang' to force display globe + current language.
     * @return string Rendered language menu in HTML.
     */
    public function render_lang_menu($showlang = false) {
        $langs = get_string_manager()->get_list_of_translations();
        $haslangmenu = $this->lang_menu() != '';
        $menu = new custom_menu();

        if ($haslangmenu) {
            $currlang = current_language();
            if (isset($langs[$currlang])) {
                $currentlang = $langs[$currlang];
            } else {
                $currentlang = get_string('language');
            }

            // Determine label for top level menu item.
            if ($showlang) { // Globe + current language name.
                $strlang = $currentlang;
            } else { // Just the globe.
                $strlang = '';
            }
            // Add top level menu item.
            $this->language = $menu->add($currentlang, new moodle_url('#'), $strlang, 10000);

            // Make first language in the list the current language.
            if (isset($langs[$currlang])) {
                $langs = [ $currlang => $langs[$currlang] ] + $langs;
            }
            // Add languages for dropdown menu.
            foreach ($langs as $langtype => $langname) {
                $lang = str_replace('_', '-', $langtype);
                $this->language->add($langname, new moodle_url($this->page->url, ['lang' => $langtype]), $lang);
            }

            foreach ($menu->get_children() as $item) {
                $context = $item->export_for_template($this);
            }

            if (isset($context)) {
                return $this->render_from_template('theme_trema/lang_menu', $context);
            }
        }
        return '';
    }

    /**
     * Renders the login form.
     *
     * @param \core_auth\output\login $form The renderable.
     * @return string
     */
    public function render_login(\core_auth\output\login $form) {
        global $CFG, $SITE;

        $context = $form->export_for_template($this);

        // Override because rendering is not supported in template yet.
        if ($CFG->rememberusername == 0) {
            $context->cookieshelpiconformatted = $this->help_icon('cookiesenabledonlysession');
        } else {
            $context->cookieshelpiconformatted = $this->help_icon('cookiesenabled');
        }

        $context->rememberusername = $CFG->rememberusername == 2;
        $context->errorformatted = $this->error_text($context->error);
        $url = $this->get_logo_url();
        if ($url) {
            $url = $url->out(false);
        }
        $context->logourl = $url;
        $sitename = format_string($SITE->fullname, true, ['context' => \context_course::instance(SITEID), 'escape' => false]);
        $context->sitename = $sitename;

        $context->loginpagecreatefirst = get_config('theme_trema', 'loginpagecreatefirst');

        return $this->render_from_template('core/loginform', $context);
    }

    /**
     * Generates the standard HTML for the head section of the page.
     *
     * This method temporarily modifies the global `$CFG->additionalhtmlhead` to apply formatting
     * options before calling the parent method to generate the standard HTML head. It ensures that
     * any additional HTML specified in the site configuration is processed through Moodle filters
     * and included in the head section of the page. After the parent method is called, it restores
     * the original `$CFG->additionalhtmlhead` value.
     *
     * @return string The HTML content for the head section of the page.
     */
    public function standard_head_html() {
        global $CFG;

        $additionalhtmlhead = $CFG->additionalhtmlhead;

        // If filtering of the primary custom menu is enabled, apply only the string filters.
        if (!empty(get_config('theme_trema', 'navfilter')) && strpos($CFG->additionalhtmlhead, '}') !== false) {
            // Apply filters that are enabled for Content and Headings.
            $filtermanager = \filter_manager::instance();
            $CFG->additionalhtmlhead = $filtermanager->filter_string($CFG->additionalhtmlhead, \context_system::instance());
        }

        $output = parent::standard_head_html();
        $CFG->additionalhtmlhead = $additionalhtmlhead;
        return $output;
    }

    /**
     * Adds additional HTML at the top of the body for every page.
     *
     * This method temporarily modifies the global `$CFG->additionalhtmltopofbody` to apply formatting
     * options before calling the parent method to generate the top of the HTML body. It ensures that
     * any additional HTML specified in the site configuration is processed through Moodle filters
     * and included in the beginning of the body of the page. After the parent method is called, it
     * restores the original `$CFG->additionalhtmltopofbody` value.
     *
     * Credit: GCWeb theme by TNG Consulting Inc.
     *
     * @return string Additional HTML to be placed at the top of the body.
     */
    public function standard_top_of_body_html() {
        global $CFG;
        $additionalhtmltopofbody = $CFG->additionalhtmltopofbody;
        if (strpos($additionalhtmltopofbody, '}') !== false) {
            $CFG->additionalhtmltopofbody = format_text($CFG->additionalhtmltopofbody,
                FORMAT_HTML, ['noclean' => true, $this->page->context]);
        }
        $output = parent::standard_top_of_body_html();
        $CFG->additionalhtmltopofbody = $additionalhtmltopofbody;
        return $output;
    }

    /**
     * Adds additional HTML at the end of the body for every page.
     *
     * Credit: GCWeb theme by TNG Consulting Inc.
     *
     * This method temporarily modifies the global `$CFG->additionalhtmlfooter` to apply formatting
     * options before calling the parent method to generate the content at the bottom of the HTML body.
     * It ensures that any additional HTML specified in the site configuration is processed through
     * Moodle filters and included in the footer section of the page. After the parent method is called,
     * it restores the original `$CFG->additionalhtmlfooter` value.
     *
     * @return string Additional HTML to be placed at the end of the body.
     */
    public function standard_end_of_body_html() {
        global $CFG;
        $additionalhtmlfooter = $CFG->additionalhtmlfooter;
        if (strpos($additionalhtmlfooter, '}') !== false) {
            $CFG->additionalhtmlfooter = format_text($CFG->additionalhtmlfooter,
                FORMAT_HTML, ['noclean' => true, 'context' => $this->page->context]);
        }
        $output = parent::standard_end_of_body_html();
        $CFG->additionalhtmlfooter = $additionalhtmlfooter;
        return $output;
    }
}
