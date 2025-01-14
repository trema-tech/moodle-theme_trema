<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace theme_trema;

/**
 * Unit tests for scss compilation.
 *
 * @package   theme_trema
 * @copyright 2024-2025 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author    Michael Milette
 * @copyright 2016 onwards Ankit Agarwal
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class scss_test extends \advanced_testcase {
    /**
     * Test that trema can be compiled using SassC (the defacto implemention).
     *
     * @covers \theme_trema
     *
     * @return void
     */
    public function test_scss_compilation_with_sassc(): void {
        if (!defined('PHPUNIT_PATH_TO_SASSC')) {
            $this->markTestSkipped('Path to SassC not provided');
        }

        $this->resetAfterTest();
        set_config('pathtosassc', PHPUNIT_PATH_TO_SASSC);

        $this->assertNotEmpty(
            \theme_config::load('trema')->get_css_content_debug('scss', null, null)
        );
    }
}
