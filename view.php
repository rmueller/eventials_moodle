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
 * @copyright  2018 Eventials <relacionamento@eventials.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once(dirname(__FILE__).'/vendor/autoload.php');
global $USER;

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
$PAGE->set_cacheable(false);

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('eventials-'.$somevar);
 */
$PAGE->set_pagelayout('popup');
/*
// Conditions to show the intro can change to look for own settings or whatever.
if ($eventials->intro) {
    echo $OUTPUT->box(format_module_intro('eventials', $eventials, $cm->id), 'generalbox mod_introbox', 'eventialsintro');
}
*/
echo $OUTPUT->header();

$PAGE->set_url('/mod/eventials/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($eventials->name));
$PAGE->set_heading(format_string($course->fullname));

if($USER->id == $eventials->speaker_email){
    $link = "{$eventials->webinar_uri}?transmission=true";
    echo $OUTPUT->heading("Acesse <a href='{$link}'>A -{$link}</a> para acompanhar o webinar.");
} else {
    echo $OUTPUT->box("
            <iframe src=\"{$eventials->webinar_embed_player}?email={$USER->email}&custom_var={email:{$USER->email}}\" width=\"640\" height=\"354\" webkitAllowFullScreen mozallowfullscreen allowFullScreen frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"></iframe>
            <iframe src=\"{$eventials->webinar_embed_chat}?username={$USER->username}\" width=\"340\" height=\"354\" webkitAllowFullScreen mozallowfullscreen allowFullScreen frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"></iframe>
        ");

}

