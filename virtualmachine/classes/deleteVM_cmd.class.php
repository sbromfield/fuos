<?php

require_once "Command.interface.php";

/**
 * The delete VM command encapsulates of the logic for the
 * delete Virtual Machine (vMoodle_I4) use case.
 */
class deleteVM_cmd implements Command
{
	/**
	 * Executes delete Virtual Machine use case logic.
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
		 * vm should be deleted.
		 */
		$vmid = required_param('vm', PARAM_INT);
		
		/**
		 * Get instance of the Cloud Manager and delete Virtual Machine.
		 */
		$cm = Cloud_Manager::getInstance();
		if($cm->delete($vmid))
		{
			/**
			 * Deleted successfully. Redirect to the Virtual Machine listing.
			 */
			header("Location: " . $CFG->wwwroot . "/mod/virtualmachine/index.php?a=listVM&id={$course->id}");
			exit();
		}else{
			echo "An error has happened";
		}
			
	}
}
