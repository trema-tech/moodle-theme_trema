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
 * Course renderer.
 *
 * @package     theme_trema
 * @copyright   2019 Trema - {@link https://trema.tech/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_trema\output\core;

use core_course_category;
use core_course_list_element;
use moodle_url;
use html_writer;
use coursecat_helper;
use stdClass;

/**
 * Class course_renderer
 * @package theme_trema
 * @copyright   2019 Trema - {@link https://trema.tech/}
 * @author      Rodrigo Mady
 * @author      Trevor Furtado
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_renderer extends \core_course_renderer {
    /**
     * Renders the list of courses for frontpage and /course
     *
     * If list of courses is specified in $courses; the argument $chelper is only used
     * to retrieve display options and attributes, only methods get_show_courses(),
     * get_courses_display_option() and get_and_erase_attributes() are called.
     *
     * @param coursecat_helper $chelper various display options
     * @param array $courses the list of courses to display
     * @param int|null $totalcount total number of courses (affects display mode if it is AUTO or pagination if applicable),
     *     defaulted to count($courses)
     * @return string
     */
    protected function coursecat_courses(coursecat_helper $chelper, $courses, $totalcount = null) {
        global $CFG;

        if ($totalcount === null) {
            $totalcount = count($courses);
        }
        if (!$totalcount) {
            // Courses count is cached during courses retrieval.
            return '';
        }
        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_AUTO) {
            // In 'auto' course display mode we analyse if number of courses is more or less than $CFG->courseswithsummarieslimit.
            if ($totalcount <= $CFG->courseswithsummarieslimit) {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED);
            } else {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_COLLAPSED);
            }
        }

        // Prepare content of paging bar if it is needed.
        $paginationurl = $chelper->get_courses_display_option('paginationurl');
        $paginationallowall = $chelper->get_courses_display_option('paginationallowall');
        if ($totalcount > count($courses)) {
            // There are more results that can fit on one page.
            if ($paginationurl) {
                // The option paginationurl was specified, display pagingbar.
                $perpage = $chelper->get_courses_display_option('limit', $CFG->coursesperpage);
                $page = $chelper->get_courses_display_option('offset') / $perpage;
                $pagingbar = $this->paging_bar($totalcount, $page, $perpage,
                    $paginationurl->out(false, array('perpage' => $perpage)));
                if ($paginationallowall) {
                    $pagingbar .= html_writer::tag('div', html_writer::link($paginationurl->out(false, array('perpage' => 'all')),
                        get_string('showall', '', $totalcount)), array('class' => 'paging paging-showall'));
                }
            } else if ($viewmoreurl = $chelper->get_courses_display_option('viewmoreurl')) {
                // The option for 'View more' link was specified, display more link.
                $viewmoretext = $chelper->get_courses_display_option('viewmoretext', new \lang_string('viewmore'));
                $morelink = html_writer::tag('div', html_writer::link($viewmoreurl, $viewmoretext),
                    array('class' => 'paging paging-morelink'));
            }
        } else if (($totalcount > $CFG->coursesperpage) && $paginationurl && $paginationallowall) {
            // There are more than one page of results and we are in 'view all' mode, suggest to go back to paginated view mode.
            $pagingbar = html_writer::tag(
                'div',
                html_writer::link(
                    $paginationurl->out(
                        false,
                        array('perpage' => $CFG->coursesperpage)
                    ),
                    get_string('showperpage', '', $CFG->coursesperpage)
                ),
                array('class' => 'paging paging-showperpage')
            );
        }
        // Display list of courses.
        $attributes = $chelper->get_and_erase_attributes('courses');
        $content = html_writer::start_tag('div', $attributes);

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }

        $coursecount = 1;
        $content .= html_writer::start_tag('div', array('class' => ' row card-deck my-4'));
        foreach ($courses as $course) {
            $content .= $this->coursecat_coursebox($chelper, $course, 'card mb-3 course-card-view');
            $coursecount ++;
        }

        $content .= html_writer::end_tag('div');
        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }

        if (!empty($morelink)) {
            $content .= $morelink;
        }

        $content .= html_writer::end_tag('div'); // End courses.
        return $content;
    }

    /**
     * Displays one course in the list of courses.
     *
     * This is an internal function, to display an information about just one course
     *
     * @param coursecat_helper $chelper various display options
     * @param core_course_list_element|stdClass $course
     * @param string $additionalclasses additional classes to add to the main <div> tag (usually
     *    depend on the course position in list - first/last/even/odd)
     * @return string
     */
    protected function coursecat_coursebox(coursecat_helper $chelper, $course, $additionalclasses = '') {
        global $PAGE;
        if (!isset($this->strings->summary)) {
            $this->strings->summary = get_string('summary');
        }
        if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
            return '';
        }
        if ($course instanceof stdClass) {
            $course = new core_course_list_element($course);
        }

        $content = html_writer::start_tag('div', array('class' => $additionalclasses));
        $classes = '';
        if ($chelper->get_show_courses() >= self::COURSECAT_SHOW_COURSES_EXPANDED) {
            $nametag = 'h5';
        } else {
            $classes .= ' collapsed';
            $nametag = 'div';
        }
        // End coursebox.
        $content .= html_writer::start_tag('div', array(
            'class' => $classes,
            'data-courseid' => $course->id,
            'data-type' => self::COURSECAT_TYPE_COURSE,
        ));
        // Render course enrolment/summary in desired HTML format, as cards or using the full page.
        if ($PAGE->pagetype == 'enrol-index' && $PAGE->theme->settings->courseenrolmentpageformat == 'fullwidth') {
            $content .= parent::coursecat_coursebox_content($chelper, $course);
        } else {
            $content .= $this->coursecat_coursebox_content($chelper, $course);
        }
        $content .= html_writer::end_tag('div');
        // End coursebox.
        $content .= html_writer::end_tag('div');
        // End col-md-4.
        return $content;
    }

    /**
     * Returns HTML to display course content (summary, course contacts and optionally category name)
     *
     * This method is called from coursecat_coursebox() and may be re-used in AJAX
     *
     * @param coursecat_helper $chelper various display options
     * @param stdClass|core_course_list_element $course
     * @return string
     */
    protected function coursecat_coursebox_content(coursecat_helper $chelper, $course) {
        if ($course instanceof stdClass) {
            $course = new core_course_list_element($course);
        }

        // Should we show category names? in search results and optionally on Frontpage.
        static $showcategories;
        if (!isset($showcategories)) {
            global $PAGE;
            // Cache result as it will be the same for all displayed courses on this page.
            $showcategories = $chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_EXPANDED_WITH_CAT ||
                    (get_config('theme_trema', 'showcategories') && $PAGE->pagetype == 'site-index');
        }

        // Course name.
        $coursename = $chelper->get_course_formatted_name($course);
        $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));
        $coursenamelink = html_writer::link($courseurl,
            $coursename, array('class' => $course->visible ? 'aalink' : 'aalink dimmed'));

        $content = html_writer::start_tag('a', array ('href' => $courseurl, 'class' => 'course-card-img', 'aria-hidden' => 'true', 'tabindex' => '-1', 'aria-label' => $coursename));
        $content .= $this->get_course_summary_image($course);
        $content .= html_writer::end_tag('a');

        $content .= html_writer::start_tag('div', array('class' => 'card-body'));
        $content .= "<h4 class='card-title text-center m-1'>". $coursenamelink ."</h4>";
        $content .= html_writer::end_tag('div');

        $content .= html_writer::start_tag('div', array('class' => 'card-block text-center'));

        // Print enrolmenticons.
        if ($icons = enrol_get_course_info_icons($course)) {
            foreach ($icons as $pixicon) {
                $content .= $this->render($pixicon);
            }
        }

        $content .= html_writer::start_tag('div', array('class' => 'pull-right'));
        $content .= html_writer::end_tag('div'); // End pull-right.

        $content .= html_writer::end_tag('div'); // End card-block.

        // Display course contacts. See core_course_list_element::get_course_contacts().
        if ($course->has_course_contacts()) {
            $content .= html_writer::start_tag('div', array('class' => 'teachers pt-2'));
            $content .= html_writer::start_tag('ul', array('class' => 'list-unstyled m-0 px-3 font-weight-light'));
            foreach ($course->get_course_contacts() as $userid => $coursecontact) {
                $name = $coursecontact['rolename'].': '.
                    html_writer::link(new moodle_url('/user/view.php',
                        array('id' => $userid, 'course' => SITEID)),
                        $coursecontact['username']);
                $content .= html_writer::tag('li', $name);
            }
            $content .= html_writer::end_tag('ul'); // End teachers.
            $content .= html_writer::end_tag('div'); // End teachers.
        }

        // Display course category if necessary (for example in search results).
        if ($showcategories) {
            if ($cat = core_course_category::get($course->category, IGNORE_MISSING)) {
                $content .= html_writer::start_tag('div', array('class' => 'coursecat text-center small'));
                $content .= html_writer::tag('span', get_string('category').': ');
                $content .= html_writer::link(new moodle_url('/course/index.php', array('categoryid' => $cat->id)),
                    $cat->get_formatted_name(), array('class' => $cat->visible ? '' : 'dimmed'));
                $content .= html_writer::end_tag('div'); // End coursecat.
            }
        }

        // Display course summary.
        if ($course->has_summary()) {
            $summarytype = get_config('theme_trema', 'summarytype');

            if ($summarytype == 'popover') {
                $content .= html_writer::start_tag('div', array('class' => 'card-see-more text-center'));
                $content .= html_writer::start_tag('div', array('class' => 'btn btn-secondary m-2',
                    'id' => "course-popover-{$course->id}", 'role' => 'button', 'data-region' => 'popover-region-toggle',
                    'data-toggle' => 'popover', 'data-placement' => 'right',
                    'data-content' => $chelper->get_course_formatted_summary($course, ['noclean' => true, 'para' => false]),
                    'data-html' => 'true', 'tabindex' => '0', 'data-trigger' => 'focus'));
                $content .= get_string('seemore', 'theme_trema');
                $content .= html_writer::end_tag('div');
                $content .= html_writer::end_tag('div'); // End summary.
            } else if ($summarytype == 'modal') {
                $modal = [
                    'body' => $chelper->get_course_formatted_summary($course, ['overflowdiv' => true,
                        'noclean' => true, 'para' => false]),
                    'title' => format_text($course->fullname, FORMAT_HTML),
                    'uniqid' => $course->id,
                    'classes' => "modal-$course->id",
                    'courselink' => new moodle_url("/course/view.php", ['id' => $course->id])
                ];
                $content .= $this->output->render_from_template('theme_trema/course_summary_modal', $modal);
            }
        }

        return $content;
    }

    /**
     * Returns the first course's summary issue
     *
     * @param stdClass $course the course object
     * @return string
     */
    protected function get_course_summary_image($course) {
        global $CFG;

        $contentimage = '';
        foreach ($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            $url = new moodle_url("$CFG->wwwroot/pluginfile.php/". $file->get_contextid(). '/'. $file->get_component(). '/'.
                $file->get_filearea(). $file->get_filepath(). $file->get_filename());
            if ($isimage) {
                $contentimage = html_writer::start_tag('div', array('style' => "background-image:url('$url')",
                    'class' => 'card-img-top'));
                $contentimage .= html_writer::end_tag('div');
                break;
            }
        }

        if (empty($contentimage)) {
            $pattern = new \core_geopattern();
            $pattern->setColor($this->coursecolor($course->id));
            $pattern->patternbyid($course->id);
            $contentimage = html_writer::start_tag('div', array('style' => "background-image:url('{$pattern->datauri()}')",
                'class' => 'card-img-top'));
            $contentimage .= html_writer::end_tag('div');
        }

        return $contentimage;
    }

    /**
     * Generate a semi-random color based on the courseid number (so it will always return
     * the same color for a course)
     *
     * @param int $courseid
     * @return string $color, hexvalue color code.
     */
    protected function coursecolor($courseid) {
        // The colour palette is hardcoded for now. It would make sense to combine it with theme settings.
        $basecolors = ['#81ecec', '#74b9ff', '#a29bfe', '#dfe6e9', '#00b894', '#0984e3', '#b2bec3',
            '#fdcb6e', '#fd79a8', '#6c5ce7'];

        $color = $basecolors[$courseid % 10];
        return $color;
    }
}
