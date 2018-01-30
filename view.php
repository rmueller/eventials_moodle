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
 * Prints a particular instance of eventials
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_eventials
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/vendor/autoload.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... eventials instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('eventials', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $eventials  = $DB->get_record('eventials', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $eventials  = $DB->get_record('eventials', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $eventials->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('eventials', $eventials->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

// redirect("https://www.eventials.com/rafael_88?ev="+$eventials.);

require_login($course, true, $cm);

$event = \mod_eventials\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $eventials);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/eventials/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($eventials->name));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('eventials-'.$somevar);
 */

// Output starts here.
echo $OUTPUT->header();
// Conditions to show the intro can change to look for own settings or whatever.
if ($eventials->intro) {
    echo $OUTPUT->box(format_module_intro('eventials', $eventials, $cm->id), 'generalbox mod_introbox', 'eventialsintro');
}

// TODO pegar o email do usuário para poder inscrever ele no webinar e então apresentar o link
// TODO no link é possível já colocar o e-mail do aluno
// access_url": "https://www.eventials.com/eventials/webinar-eventials/?email=jose@eventials.com"
// após o login com app token e secret, é possível buscar o username com https://api.eventials.com/v1/me
// talvez devemos sempre buscar o username para evitar algo hardcoded

$client = new \GuzzleHttp\Client();
$res = $client->request('GET', 'https://api.github.com/repos/guzzle/guzzle');
echo $OUTPUT->box("faasfdf" + $res->getStatusCode());

echo $OUTPUT->box(var_dump($eventials));

// Replace the following lines with you own code.
echo $OUTPUT->heading('Acesse <a href="https://www.eventials.com">https://www.eventials.com</a> para acompanhar o webinar.');

// Finish the page.
echo $OUTPUT->footer();
