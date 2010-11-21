<?php

require_once "Command.interface.php";

/**
 * The installation done command encapsulates the logic for the
 * installation done use case.
 */
class installationDone_cmd implements Command
{
	/**
	 * Executes installation done use case logic.
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
		 * vm should be flagged as installed.
		 */
		$vmid = required_param('vm', PARAM_INT);
		
		/**
		 * Get instance of the Cloud Manager and unmount ISO.
		 */
		$cm = Cloud_Manager::getInstance();
		if($cm->installationDone($vmid))
		{
			/**
			 * Installation done successfully. Redirect to the Virtual Machine listing.
			 */
			$location = $CFG->wwwroot . "/mod/virtualmachine/index.php?a=listVM&id={$course->id}";
			if(isset($_GET['assid'])) $location .= "&assid={$_GET['assid']}";
			header("Location: " . $location);
			exit();
		}else{
			echo "An error has happened";
		}
			
	}
}
