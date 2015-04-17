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
 * flexdates_report block caps.
 *
 * @package    block_flexdates_report
 * @copyright  2015 Joseph Gilgen <gilgenlabs@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__) . '/../../local/flexdates/lib.php');
require_once(dirname(__FILE__) . '/../../local/flexdates/renderer.php');

class block_flexdates_report extends block_base {
    
    function init() {
        $this->title = get_string('pluginname', 'block_flexdates_report');
    }

    function get_content() {
        global $CFG,$DB,$OUTPUT,$COURSE,$USER;
        $this->page->requires->js('/blocks/flexdates_report/script.js');
        
        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }
        if($record = $DB->get_record('local_fd_trackcourse',array('courseid'=>$COURSE->id))){
            if($record->track){
                $sql = "SELECT ue.timestart
                          FROM {$CFG->prefix}user_enrolments ue
                          JOIN {$CFG->prefix}enrol e ON e.id=ue.enrolid
                         WHERE (e.enrol = 'manual' OR e.enrol = 'mnet')
                               AND e.courseid = {$COURSE->id}
                               AND ue.userid = {$USER->id};";
                $enrol_record = $DB->get_record_sql($sql, array(), $strictness=MUST_EXIST);
                $data = new stdClass;
                $data->id  = $COURSE->id;
                $data->title = $COURSE->shortname;
                $data->name = $COURSE->fullname;
                $data->summary = $COURSE->summary;
                $data->startdate = $enrol_record->timestart;
                $data->grades = flexdates_get_student_grades($COURSE->id,$USER->id);
                
                $course_grade_data = new flexdates_course($data,$USER->id);
                $grade_graph = flexdates_make_anglelist($course_grade_data->num_assign,
                                                   $course_grade_data->completed,
                                                   $course_grade_data->mastered,
                                                   $course_grade_data->expected,
                                                   $course_grade_data->course_grade);
                                                   
                $grade_graph = flexdates_makesvg($grade_graph->anglelist, $grade_graph->course_grade, $cx = 100, $cy = 100, $radius=95);
                
                $progress_counts = flexdates_report_render_progress_counts($course_grade_data);
                $mods = flexdates_report_render_progress_cells($COURSE->id,$course_grade_data->grades->items);
                
            $text ='
<div class="flexdates-report-progress-container">
<div class="flexdates-report-progress-circle">
'.$grade_graph.'
</div>
<div style="text-align:center;">
'.$progress_counts.'
</div>'.
#<div id="flexdates-report-toggle-tasks-container">
#  <a href="#" id="flexdates-report-show-skills">Show Skills</a>
#</div>
$mods.'
</div>';
            } else{
                $text = get_string('coursenottracked','block_flexdates_report');
            }
        } else{
            $text = get_string('coursetrackingnotsetup','block_flexdates_report');
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();

        $this->content->text = $text;//$out->render_info();
        $this->content->footer = '';

        // user/index.php expect course context, so get one if page has module context.
#        $currentcontext = $this->page->context->get_course_context(false);

#        if (! empty($this->config->text)) {
#            $this->content->text = $this->config->text;
#        }

#        $this->content = '';
#        if (empty($currentcontext)) {
#            return $this->content;
#        }
#        if ($this->page->course->id == SITEID) {
#            $this->context->text .= "site context";
#        }

#        if (! empty($this->config->text)) {
#            $this->content->text .= $this->config->text;
#        }

        return $this->content;
    }

    // my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats() {
        return array('all' => false,
                     'site' => true,
                     'site-index' => true,
                     'course-view' => true, 
                     'course-view-social' => false,
                     'mod' => true, 
                     'mod-quiz' => false);
    }

    public function instance_allow_multiple() {
          return false;
    }

    function has_config() {return true;}

    public function cron() {
            mtrace( "Hey, my cron script is running" );
             
                 // do something
                  
                      return true;
    }
}
