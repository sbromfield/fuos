<?php

require_once "Command.interface.php";

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
		global $SITE, $USER;
		
		/**
		 * Verify that the User has access to the current course.
		 */
		$course = verify_access_and_get_course();
		$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
		
		/**
		 * Require the mandatory GET parameter vm which indicates which
		 * vm the user is requestiong to view.
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
		if(@$UVA->user_id != $USER->id && $vm->owner_id != $USER->id)
		{
			/**
			 * VM does not belong to the requesting user.
			 */
			die("Unauthorized access.");
		}
		
		/**
		 * Get instance of the Cloud Manager and view Virtual Machine.
		 */
		$cm = Cloud_Manager::getInstance();
		$view = $cm->view($vmid);	
		$view->display();
		
	}
}
