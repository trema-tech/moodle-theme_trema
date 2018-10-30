<?php

/**
 * A login page layout for trema.
 *
 * @package    theme_trema
 * @copyright  2018 Trevor Furtado e Rodrigo Mady
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

//Check if the login page are using particles or image background
$login_style = get_config('theme_trema', 'loginpagestyle');
$additionalclasses = [$login_style == "particle-circles" ? 'style-particles' : 'style-image'];
$bodyattributes = $OUTPUT->body_attributes($additionalclasses);

//Only load particles.js if needed
if($login_style == "particle-circles") {
    $particles_config = json_decode(file_get_contents("$CFG->wwwroot/theme/trema/particles.json"));
    $particles_config->particles->color->value = get_config("theme_trema", "particles_circlescolor");
    
    $PAGE->requires->js_call_amd('theme_trema/tremaparticles', 'init', array(json_encode(($particles_config))));
}

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes
];

echo $OUTPUT->render_from_template('theme_trema/login', $templatecontext);

