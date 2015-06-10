<?php

define('AJAX_SCRIPT', true);

require('../../../config.php');
global $DB,$USER;

// Get parameters
$instanceid = required_param('instance',PARAM_INT);
$moduleid = required_param('modid',PARAM_INT);

// Get mod data
$mod = $DB->get_record('course_modules',array('module'=>$moduleid,'instance'=>$instanceid));
$cms = get_fast_modinfo($mod->course)->cms;
$cm = $cms[$mod->id];
$id = 'modal-'.$mod->section.'-'.$mod->indent;

// Create mod container
$out = '<div class="khanesque-upnext-container" id="khanesque-'.$cm->module.'-'.$cm->instance.'">'."\n";
$out.= '<div data-toggle="modal" data-target="#'.$id.'" onClick="khanesqueGrabFrame(\''.$cm->url.'\',\''.$id.'\')" style="display:block;">'."\n";
$out.= '<div class="khanesque-task-link">'."\n";
$out.= '<div class="khanesque-info-container">'."\n";
$out.= '<div style="text-align:center;vertical-align:middle;border-radius:50%; width:50px; height: 50px;background-color:#18bc9c;display:inline-block;">';
$out.= '<span class="glyphicon glyphicon-inverse glyphicon-star-empty" style="color:white;font-size:40px;line-height:50px;"></span></div> '.$cm->name.'</div>'."\n";
$out.= "</div>\n</div>";
$out.='</div>'."\n";

// Send back mod html
echo $out;

if(!$record = $DB->get_record('format_khanesque',array('courseid'=>$mod->course,'userid'=>$USER->id))){
  $dataobject = new stdClass;
  $dataobject->courseid = $mod->course;
  $dataobject->userid = $USER->id;
  $dataobject->modid = $mod->id;
  $dataobject->added = 1;
  $DB->insert_record('format_khanesque',$dataobject);
} else{
    $record->added = 1;
    $DB->update_record('local_fd_mod_duration', $record);
}
