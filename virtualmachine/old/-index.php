<?php
require_once("../../config.php");
require_once("lib.php");

$course = verify_access_and_get_course();

$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);


$navlinks = array();
$navlinks[] = array('name' => "Virtual Machines", 'link' => '', 'type' => 'activity');


print_header("$course->shortname: Virtual Machines", $course->fullname,
                     build_navigation($navlinks),
                    "", "", true, '', user_login_string($SITE), navmenu($course));
                    
if(has_capability('moodle/legacy:editingteacher', $coursecontext, $USER->id, false))
{
	echo "<a href=\"{$CFG->wwwroot}/mod/virtualmachine/my_vms.php?id={$COURSE->id}\">My Vitual Machines</a><br />",
	"<a href=\"{$CFG->wwwroot}/mod/virtualmachine/student_vms.php?id={$COURSE->id}\">Student Vitual Machines</a><br />";
}else{
	echo "<a href=\"{$CFG->wwwroot}/mod/virtualmachine/my_vms.php?id={$COURSE->id}\">My Vitual Machines</a><br />";
}
                    
                    
print_footer($course);

