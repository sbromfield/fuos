<?php

require_once "Command.interface.php";

/**
 * The stop VM command encapsulates of the logic for the
 * stop Virtual Machine (vMoodleS05) use case.
 */
class stopVM_cmd implements Command
{
	/**
	 * Executes stop Virtual Machine use case logic.
	 */
	public function execute() {
		global $USER, $CFG;;
		
		/**
		 * Verify that the User has access to the current course.
		 */
		$course = verify_access_and_get_course();
		$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
		
		/**
		 * Require the mandatory GET parameter vm which indicates which
		 * vm should be stopped.
		 */
		$vmid = required_param('vm', PARAM_INT);
		
		/**
		 * Verify that the Virtual Machine belongs to the requesting user. A VM
		 * belongs to a User, if the VM is a child of one of its VMs, in which
		 * case, the owner ID indicated ownership. Or, it belongs to a User if
		 * the Virtual Machine is associated to it in a given Assignment.
		 */
		$ds = new dsFacade();
		$UVA = $ds->selectUVAByV($vmid);
		$vm = $ds->selectVM($vmid);
		if($UVA->user_id != $USER->id && $vm->owner_id != $USER->id)
		{
			/**
			 * VM does not belong to the requesting user.
			 */
			die("Unauthorized access.");
		}
		
		/**
		 * Get instance of the Cloud Manager and stop Virtual Machine.
		 */
		$cm = Cloud_Manager::getInstance();
		if($cm->stop($vmid))
		{
			/**
			 * Stopped successfully. Redirect to the Virtual Machine listing.
			 */
			$location = $CFG->wwwroot . "/mod/virtualmachine/?a=listVM&id={$course->id}&vm={$vmid}";
			if(isset($_GET['assid'])) $location .= "&assid={$_GET['assid']}";
			header("Location: " . $location);
			exit();
		}else{
			echo "An error has happened.";
		}
		
	}
}
