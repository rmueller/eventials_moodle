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
 * The main eventials configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_eventials
 * @copyright  2018 Eventials <relacionamento@eventials.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form
 *
 * @package    mod_eventials
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_eventials_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'title', 'Título', array('size' => '64'));
        $mform->addRule('title', null, 'required', null, 'client');
        $mform->addRule('title', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->setType('title', PARAM_TEXT);

        $mform->addElement('htmleditor', 'description', get_string('description'), array('rows'  => 6, 'cols'  => 40));
        $mform->addRule('description', null, 'required', null, 'client');

        $mform->addElement('date_time_selector', 'start_time', 'Data e horário do webinar');
        $mform->addElement('duration', 'duration', 'Duração');
        $mform->addElement('text', 'speaker_email', 'Email do palestrante', array('size' => '50'));
        $mform->setType('speaker_email', PARAM_NOTAGS);

        // Add standard grading elements.
         $this->standard_grading_coursemodule_elements();

        // Add standard elements, common to all modules.
         $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
    }
}
