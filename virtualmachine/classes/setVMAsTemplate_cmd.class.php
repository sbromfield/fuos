<?php

require_once "Command.interface.php";

/**
 * The set VM as template command encapsulates of the logic for the
 * flag Virtual Machine template (vMoodle02) use case.
 */
class setVMAsTemplate_cmd implements Command
{
	/**
	 * Executes flag Virtual Machine template use case logic.
	 */
	public function execute() {
		global $CFG;
	
		/**
		 * Verify that the User has access to the current course and that the
		 * User is an editing teacher.
		 */
		$course = verify_access_and_get_course();
		$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
		require_capability('moodle/legacy:editingteacher', $coursecontext);
		
		/**
		 * Require the mandatory GET parameter vm which indicates which
		 * vm should be set as template.
		 */
		$vmid = required_param('vm', PARAM_INT);
		
		/**
		 * Get instance of the Cloud Manager and set Virtual Machine as Template.
		 */
		$cm = Cloud_Manager::getInstance();
		if($cm->setAsTemplate($vmid))
		{
			/**
			 * Set VM as template successfully. Redirect to the Virtual Machine 
			 * listing.
			 */
			header("Location: " . $CFG->wwwroot . "/mod/virtualmachine/?a=listVM&id={$course->id}");
			exit();
		}else{
			echo "An error has happened.";
		}
	}
}
