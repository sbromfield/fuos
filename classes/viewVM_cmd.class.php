<?php

require_once "Command.interface.php";
//require_once("../../../config.php");
//require_once("../lib.php");

/**
 * The view VM command encapsulates of the logic for the
 * remote virtual machine connection (vMoodleS02) use case.
 */
class viewVM_cmd implements Command
{
	/**
	 * Executes remote virtual machine connection use case logic.
	 */
	public function execute() {
		global $SITE;
		
		$course = verify_access_and_get_course();

		$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
		
		$vmid = required_param('vm', PARAM_INT);

		$navlinks = array();
		$navlinks[] = array('name' => "Virtual Machines", 'link' => '', 'type' => 'activity');


		$meta = "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js\"></script>";


		print_header("$course->shortname: Virtual Machines", $course->fullname,
                	     build_navigation($navlinks),
                	    "", $meta, true, '', user_login_string($SITE));
                	    
		
		require_once("Cloud_Manager.class.php");
		$cm = Cloud_Manager::getInstance();
		$view = $cm->view($vmid);	
		
		//require_once "viewVM_Page.class.php";
		//$view = new viewVM_Page();
		$view->display();
		
		print_simple_box_start('center', '1034px', '', 0, 'generalbox', 'vm_template');
		print_simple_box_end();
		print_footer($course);
	}
}
