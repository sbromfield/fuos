<?php

require_once "Command.interface.php";

/**
 * The create VM command encapsulates of the logic for the
 * create Virtual Machine (vMoodleI01) use case.
 */
class createVM_cmd implements Command
{
	/**
	 * Executes create Virtual Machine use case logic.
	 */
	public function execute() {
	
		/**
		 * Verify that the User has access to the current course and that the
		 * User is an editing teacher.
		 */
		$course = verify_access_and_get_course();
		$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
		require_capability('moodle/legacy:editingteacher', $coursecontext);
		
		/**
		 * Require the mandatory GET parameter strat which indicates which
		 * cloud strategy should be used to forge the create VM form.
		 */
		$strat = required_param("strat", PARAM_ALPHA);

		/**
		 * If the request method is GET, then the form has to be delivered.
		 * Otherwise, the request is most likely POST in which case the create
		 * form has been submitted and creating the Virtual Machine is the
		 * desired outcome.
		 */
		if($_SERVER['REQUEST_METHOD'] == "GET")
		{
			$page = $this->displayForm($strat);		
		}
		else
		{
			$this->createVM($strat);
		}
	}
	
	/**
	 * Displays the create VM form.
	 */ 
	private function displayForm($strat) {

		/**
		 * Get instance of the Cloud Manager and call the create VM form method.
		 */
		$cm = Cloud_Manager::getInstance();
		$page = $cm->createVMForm($strat);
		$page->display();
	}

	/**
	 * Create the Virtual Machine.
	 */
	private function createVM($strat) {
		global $COURSE, $CFG;
		
		/**
		 * Get instance of the Cloud Manager and call the create method.
		 */
		$cm = Cloud_Manager::getInstance();
		list($errors, $vm_id) = $cm->create($strat);
		
		/**
		 * If creating returns errors, display create VM form and the errors.
		 */
		if(count($errors))
		{
			$_POST['errors'] = $errors;
			$page = $cm->createVMForm($strat);
			$page->display();
			exit();
		}
		
		/**
		 * Virtual Machine created successfully. Start virtual machine and
		 * redirect to the Virtual Machine remote console.
		 */
		$cm->start($vm_id);
		header("Location: " . $CFG->wwwroot . "/mod/virtualmachine/?a=viewVM&id={$COURSE->id}&vm={$vm_id}");
		exit();
	}
}
