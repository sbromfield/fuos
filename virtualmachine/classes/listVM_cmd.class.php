<?php

require_once "Command.interface.php";

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

		/**
		 * Verify that the User has access to the current course.
		 */
		$course = verify_access_and_get_course();
		$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
        
        /**
         * Listing type varies depending on the access level of the user.
         * If it is an editing teacher, then a more complex listing is presented.
         * Otherwise, a simpler listing is presented for the students.
         */
		if(has_capability('moodle/legacy:editingteacher', $coursecontext, $USER->id, false))
		{
			/**
			 * The user is and editing teacher. Therefore, the listVM Page 
			 * needs to be displayed. Create an instance of the page, feed it
			 * the desired Virtual Machines it should display, and display it.
			 */
			$ds = new dsFacade();
			$page = new listVM_Page();
			$page->vms = $ds->selectVMsFromUser($USER->id);
			$page->display();
		}
		else
		{
			/**
			 * The user is not an editing teacher (most likely a student). Get
			 * the required GET parameter vm, which indicates what vm to list.
			 */
			$vm_id = required_param("vm", PARAM_INT);

			/**
			 * Verify that the requested Virtual Machine belongs to the 
			 * requesting user.
			 */
			$ds = new dsFacade();
			$UVA = $ds->selectUVAByV($vm_id);
			if(!$UVA || $UVA->user_id != $USER->id)
			{
				die("Unauthorized");
			}
			
			/**
			 * The Virtual Machine does indeed belong to the requesting user.
			 * Therefore, create a listStudentVM Page, feed it with the
			 * requested vm and display the page.
			 */
			$page = new listStudentVM_Page();
			$page->vms = array($ds->selectVM($vm_id));
			$page->display();
		}
                    
	}
}

