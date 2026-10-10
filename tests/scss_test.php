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

#[\PHPUnit\Framework\Attributes\CoversFunction('theme_trema_get_pre_scss')]
#[\PHPUnit\Framework\Attributes\CoversFunction('theme_trema_get_main_scss_content')]
#[\PHPUnit\Framework\Attributes\CoversFunction('theme_trema_get_extra_scss')]
/**
 * Unit tests for scss compilation.
 *
 * Keep annotations for PHPUnit 9 and attributes for PHPUnit 10+.
 * Attributes precede the docblock so older Moodle code checkers can read the annotations.
 *
 * @covers ::theme_trema_get_pre_scss
 * @covers ::theme_trema_get_main_scss_content
 * @covers ::theme_trema_get_extra_scss
 *
 * @package   theme_trema
 * @copyright 2024-2026 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author    Michael Milette
 * @copyright 2016 onwards Ankit Agarwal
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class scss_test extends \advanced_testcase {
    /**
     * Test that trema can be compiled using SassC (the defacto implemention).
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

    /**
     * Test that trema can be compiled using scssphp (the built-in PHP implementation).
     *
     * @return void
     */
    public function test_scss_compilation_with_scssphp(): void {
        $this->resetAfterTest();

        $this->assertNotEmpty(
            \theme_config::load('trema')->get_css_content_debug('scss', null, null)
        );
    }

    /**
     * Regression test: banner carousel images must not use background-attachment: fixed.
     *
     * background-attachment: scroll, fixed positions the banner image relative to the
     * viewport rather than the element, causing a zoom/parallax effect. This test ensures
     * the fix remains in place.
     *
     * @return void
     */
    public function test_banner_background_attachment_not_fixed(): void {
        $this->resetAfterTest();

        $css = \theme_config::load('trema')->get_css_content_debug('scss', null, null);

        // The bug pattern must not exist: "scroll, fixed" is the culprit.
        $this->assertStringNotContainsString(
            'background-attachment: scroll, fixed',
            $css,
            'Banner must not use "background-attachment: scroll, fixed" (causes zoom/parallax bug)'
        );

        // Regression guard: both overlay and banner must scroll naturally.
        $this->assertStringContainsString(
            'background-attachment: scroll',
            $css,
            'Banner must use "background-attachment: scroll" for natural scrolling'
        );
    }
}
