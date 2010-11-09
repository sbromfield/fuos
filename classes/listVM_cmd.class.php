<?php

require_once "Command.interface.php";
require_once "listVM_Page.class.php";

/**
 * The list VM command encapsulates of the logic for the
 * list instructor virtual machine (vMoodleI21) and list
 * students' virtual machines (vMoodleS13) use cases.
 */
class listVM_cmd implements Command
{
	/**
	 * Executes list instructor virtual machine and list
	 * students' virtual machines use cases logic.
	 */
	public function execute() {
		global $USER, $CFG, $SITE;

		$page = new listVM_page();
		$course = verify_access_and_get_course();

		$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);


		$meta = "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js\"></script>
			  <script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js\"></script>
			  <link rel=\"stylesheet\" href=\"http://jqueryui.com/themes/base/jquery.ui.all.css\">";
			  
			  


		$navlinks = array();
		$navlinks[] = array('name' => "Virtual Machines", 'link' => '', 'type' => 'activity');


		print_header("$course->shortname: Virtual Machines", $course->fullname,
   	                  build_navigation($navlinks),
   	                 "", $meta, true, '', user_login_string($SITE));

                    
		if(has_capability('moodle/legacy:editingteacher', $coursecontext, $USER->id, false))
		{
			print_simple_box_start('center', '', '', 0, 'generalbox', 'vm_template');
			echo "<strong>Create VM</strong><br />";
			echo "<a href=\"?a=createVM&id={$course->id}&strat=VBOX\">VirtualBox</a><br />";
			print_simple_box_end();
			
			print_simple_box_start('center', '', '', 0, 'generalbox', 'vm_template');

			require_once("dsFacade.class.php");
			$ds = new dsFacade();
			$page->vms = $ds->selectVMsFromUser($USER->id);
			$page->display();


		}else{
	
			$vms = get_student_vm();
			print_simple_box_start('center', '', '', 0, 'generalbox', 'vm_template');

			if(count($vms)){
				$page->viewColumn();
				$page->display2($vms);
			}else{
				echo "No VMS";
			}
		}
	
		print_simple_box_end();
		print_footer($course);
                    
	}
}

