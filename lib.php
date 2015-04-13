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
 * The flexdates_report block helper functions and callbacks
 *
 * @package   block_flexdates_report
 * @copyright 2015 Joseph Gilgen <gilgenlabs@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


function flexdates_report_render_task_row($level,$count){
    $box = html_writer::div('','flexdates-report-progress-box flexdates-report-'.$level.'-box')."\n";
    $num = html_writer::div($count.' '.get_string('tasks'.$level,'block_flexdates_report'),
                            'flexdates-report-progress-row');
    return html_writer::div($box.$num,'flexdates-report-task-level-row');
}

function flexdates_report_render_progress_counts($gradeitems){
    $levels = array(
        'mastered'=>$gradeitems->mastered,
        'level2'=>$gradeitems->level2,
        'level1'=>$gradeitems->level1,
        'practiced'=>$gradeitems->practiced,
        'notstarted'=>$gradeitems->notstarted);
    $out = '';
    foreach($levels as $k=>$v){
        $out.=flexdates_report_render_task_row($k,$v);
    }
    return html_writer::div($out,'',array("style"=>"display:inline-block;vertical-align:top;text-align:left;"));
}

function flexdates_report_render_progress_cell($mod){
    return html_writer::div('',"flexdates-report-progress-cell flexdates-report-".$mod->masterylevel."-box");
}

function flexdates_clean_data($gradeitems){
    global $DB;
    $mod_map = $DB->get_records_menu('modules');
    foreach($gradeitems as $gradeitem){
        $module = array_search($gradeitem->itemmodule,$mod_map);
        $instance = $gradeitem->iteminstance;
        if($mod = $DB->get_record('course_modules',array('module'=>$module,'instance'=>$instance))){
            $mod->name = $gradeitem->name;
            $mod->grade = $gradeitem->grades->grade;
            $mod->masterylevel = $gradeitem->grades->masterylevel;
            $mods[$mod->id]=$mod;
        }
    }
    return $mods;
}

function flexdates_report_render_progress_cells($course,$gradeitems){
    global $DB;
    $sections = $DB->get_records('course_sections',array("course"=>$course,"visible"=>1));
    $mods = flexdates_clean_data($gradeitems);
    $out = '';
    
    foreach($sections as $section){
        if($section->sequence){
            $section_title = html_writer::div($section->name,'flexdates-report-unit-title')."\n";
            $sections_mods = explode(',',$section->sequence);
            foreach($sections_mods as $k=>$v){
                if(array_key_exists($v,$mods)){
                    $section_title.=flexdates_report_render_progress_cell($mods[$v])."\n";
                }
            }
            $out.=html_writer::div($section_title,"flexdates-report-unit-container clearfix");
        }
    }
    
    return html_writer::div($out,"flexdates-report-progress-cells-container clearfix");
}
#<div id="flexdates-report-progress-cells-container clearfix">
#  <div class="flexdates-report-unit-container clearfix">
#    <div class="flexdates-report-unit-title">Unit 1</div>
#    <div class="flexdates-report-progress-cell flexdates-report-mastered-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-notstarted-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-practiced-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-practiced-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-level1-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-level2-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-struggling-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-level2-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-mastered-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-level1-box"></div>
#  </div>
#  <div class="flexdates-report-unit-container clearfix">
#    <div class="flexdates-report-unit-title">Unit 2</div>
#    <div class="flexdates-report-progress-cell flexdates-report-mastered-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-notstarted-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-practiced-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-practiced-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-level1-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-level2-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-struggling-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-level2-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-mastered-box"></div>
#    <div class="flexdates-report-progress-cell flexdates-report-level1-box"></div>
#  </div>
#</div>


