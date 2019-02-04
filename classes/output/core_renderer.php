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
 * Trema theme.
 *
 * @package    theme_trema
 * @copyright  2018 Trevor Furtado e Rodrigo Mady
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_trema\output;

use custom_menu;
use custom_menu_item;
use stdClass;
use moodle_url;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot."/course/format/lib.php");

class core_renderer extends \theme_boost\output\core_renderer {

    /**
     * Returns the url of the custom favicon.
     */
    public function favicon() {
        $favicon = $this->page->theme->setting_file_url('favicon', 'favicon');

        if (empty($favicon)) {
            return $this->page->theme->image_url('favicon', 'theme');
        } else {
            return $favicon;
        }
    }

    /**
     *
     * Always show the compact logo when its defined.
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
     * Renders the lang menu
     *
     * @return mixed
     */
    public function render_lang_menu() {
        $langs = get_string_manager()->get_list_of_translations();
        $haslangmenu = $this->lang_menu() != '';
        $menu = new custom_menu;

        if ($haslangmenu) {
            $strlang = get_string('language');
            $currentlang = current_language();
            if (isset($langs[$currentlang])) {
                $currentlang = $langs[$currentlang];
            } else {
                $currentlang = $strlang;
            }
            $this->language = $menu->add($currentlang, new moodle_url('#'), $strlang, 10000);
            foreach ($langs as $langtype => $langname) {
                $this->language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }

            foreach ($menu->get_children() as $item) {
                $context = $item->export_for_template($this);
            }

            if (isset($context)) {
                return $this->render_from_template('theme_trema/lang_menu', $context);
            }
        }
    }
    
    /*
     * Overriding the custom_menu function ensures the custom menu is
     * always shown, even if no menu items are configured in the global
     * theme settings page.
     */
    public function custom_menu($custommenuitems = '') {
        global $CFG;
        
        if (empty($custommenuitems) && !empty($CFG->custommenuitems)) {
            $custommenuitems = $CFG->custommenuitems;
        }
        $custommenu = new custom_menu($custommenuitems, current_language());
        return $this->render_custom_menu($custommenu);
    }

    protected function render_custom_menu(custom_menu $menu) {       
        if($showmycourses = get_config('theme_trema', 'showmycourses')) {        
            $mycourses = $this->page->navigation->get('mycourses');
            
            if (isloggedin() && $mycourses && $mycourses->has_children()) {
                $branchlabel = get_string('mycourses');
                $branchurl   = new moodle_url('/course/index.php');
                $branchtitle = $branchlabel;
                $branchsort  = $showmycourses;
                
                $branch = $menu->add($branchlabel, $branchurl, $branchtitle, $branchsort);
                
                foreach ($mycourses->children as $coursenode) {
                    $branch->add($coursenode->get_content(), $coursenode->action, $coursenode->get_title());
                }
            }
        }
        
        return parent::render_custom_menu($menu);
    }
}