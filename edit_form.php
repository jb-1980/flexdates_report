<?php

class block_flexdates_report_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        global $DB,$COURSE;
        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('masterylevels', 'block_flexdates_report'));

        // A sample string variable with a default value.
        if(!$levels = $DB->get_records('block_flexdates_report',array('courseid'=>$COURSE->id))){
            $levels = new stdClass;
            $levels->mastered = 0.95;
            $levels->level2 = 0.85;
            $levels->level1 = 0.75;
            $levels->practiced = 0.65;
        }
        $mform->addElement('text', 'levelmastered', get_string('levelmastered', 'block_flexdates_report'));
        $mform->setDefault('levelmastered', $levels->mastered*100);
        $mform->setType('levelmastered', PARAM_INT);
        
        $mform->addElement('text', 'level2', get_string('level2', 'block_flexdates_report'));
        $mform->setDefault('level2', $levels->level2*100);
        $mform->setType('level2', PARAM_INT);
        
        $mform->addElement('text', 'level1', get_string('level2', 'block_flexdates_report'));
        $mform->setDefault('level1', $levels->level1*100);
        $mform->setType('level1', PARAM_INT);
        
        $mform->addElement('text', 'practiced', get_string('levelpracticed', 'block_flexdates_report'));
        $mform->setDefault('practiced', $levels->practiced*100);
        $mform->setType('practiced', PARAM_INT);
        
        

    }
}
