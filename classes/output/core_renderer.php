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

        // Use favicon configured in theme's settings.
        $favicon = $this->page->theme->setting_file_url('favicon', 'favicon');

        if (empty($favicon)) {
            if (file_exists($this->page->theme->dir . '/pix/favicon.ico')) {
                // Use pix/favicon.ico stored in the theme directory.
                $favicon = $this->page->theme->image_url('favicon', 'theme');
            } else {
                // Fallback to the webserver's favicon.ico.
                $parsedurl = parse_url($CFG->wwwroot);
                $favicon = $parsedurl['scheme'] . '://' . $parsedurl['host'] . '/favicon.ico';
                // If there isn't any, the browser will fallback to its own default favicon.
            }
        }
        return $favicon;
    }

    /**
     * Always show the compact logo when its defined.
     *
     * @return bool
     */
    public function should_display_navbar_logo() {
        $logo = $this->get_compact_logo_url();
        return !empty($logo);
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
     * Renders the lang menu on the login page.
     *
     * @return mixed
     */
    public function login_lang_menu() {
        return $this->render_lang_menu(true);
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
     * Add icons to custom menu.
     *
     * @param custom_menu $menu
     * @return string
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    protected function render_custom_menu(custom_menu $menu) {
        // Change Fontawesome's codes by HTML.
        $content = '';
        foreach ($menu->get_children() as $item) {
            $context = $item->export_for_template($this);
            $context->text = preg_replace('/^fa-(\w|-)+/', '<i class="fa \0 mr-1" aria-hidden="true"></i>', $context->text);
            $context->title = trim(preg_replace('/^fa-(\w|-)+/', '', $context->title));
            $content .= $this->render_from_template('core/custom_menu_item', $context);
        }

        return $content;
    }

    /**
     * We want to show the custom menus as a list of links in the footer on small screens.
     * Just return the menu object exported so we can render it differently.
     *
     * @return array
     */
    public function custom_menu_flat() {
        global $CFG;

        // Render standard custom_menu_flat without the language menu.
        $oldlangmenu = $CFG->langmenu;
        $CFG->langmenu = '';
        $context = parent::custom_menu_flat();
        $CFG->langmenu = $oldlangmenu;

        // Replace FontAwesome codes with HTML.
        foreach ($context->children as &$item) {
            $item->text = preg_replace('/^fa-(\w|-)+/', '<i class="fa \0 mr-1" aria-hidden="true"></i>', $item->text);
            $item->title = trim(preg_replace('/^fa-(\w|-)+/', '', $item->title));
        }

        return $context;
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
}
