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
 * General settings
 *
 * @package     theme_trema
 * @copyright   2019 Trema - {@link https://trema.tech/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_trema_footer', get_string('defaultfooter', 'theme_trema'));

$name = 'theme_trema/enabletremafooter';
$title = get_string('enabletremafooter', 'theme_trema');
$description = get_string('enabletremafooter_desc', 'theme_trema', "$CFG->wwwroot/theme/trema/pix/examples/footerinfo.png");
$setting = new admin_setting_configcheckbox($name, $title, $description, '1');
$page->add($setting);

// HTML to include in the footer content of frontpage.
$footerhtml = '<div class="row">
	<div class="col-md-8">
		<h3 class="-align-center">Trema Soluções em Tecnologia</h3>
	</div>
	<div class="col-md-4">
		<h3>Contact Us</h3>

		<ul class="labeled-icons">
			<li>
                <span class="fa fa-globe" aria-hidden="true"></span>
                <a href="https://trema.tech/" target="_blank">https://trema.tech/</a>
            </li>
			<li>
                <span class="fa fa-github" aria-hidden="true"></span>
                <a href="https://github.com/trema-tech/" target="_blank">https://github.com/trema-tech/</a>
            </li>
		</ul>
	</div>
</div>';
$setting = new admin_setting_confightmleditor('theme_trema/defaultfooter', get_string('defaultfooter', 'theme_trema'),
    get_string('defaultfooter_desc', 'theme_trema'), $footerhtml, PARAM_RAW);
$page->add($setting);

$name = 'theme_trema/enablefooterinfo';
$title = get_string('enablefooterinfo', 'theme_trema');
$description = get_string('enablefooterinfo_desc', 'theme_trema', "$CFG->wwwroot/theme/trema/pix/examples/footerinfo.png");
$setting = new admin_setting_configcheckbox($name, $title, $description, '1');
$page->add($setting);

// Must add the page after definiting all the settings!
$settings->add($page);
