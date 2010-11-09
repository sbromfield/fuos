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
//		include "create_vm.php";exit();

		if($_SERVER['REQUEST_METHOD'] == "GET")
		{
			$this->getForm();
		}
		else
		{
			$this->createVM();
		}
	}
	
	private function getForm()
	{
		$course = verify_access_and_get_course();

		$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);

		require_capability('moodle/legacy:editingteacher', $coursecontext);
		
		$strat = required_param("strat", PARAM_ALPHA);

		require_once("createVM_Page.class.php");
		require_once("dsFacade.class.php");
		require_once("Cloud_Manager.class.php");
		$ds = new dsFacade();
		$cm = Cloud_Manager::getInstance();
		
		$page = new createVM_Page();
		if(isset($_POST['errors']) && count($_POST['errors']))
		{
			$page->errors = $_POST['errors'];
		}
		$page->cloud_data = $ds->selectClouds();
		$page->extra_form = $cm->createVMForm($strat);
		$page->display();

	}
	
	private function createVM()
	{
		$course = verify_access_and_get_course();

		$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);

		require_capability('moodle/legacy:editingteacher', $coursecontext);
		
		$strat = required_param("strat", PARAM_ALPHA);
		
		require_once("Cloud_Manager.class.php");
		$cm = Cloud_Manager::getInstance();
		$errors = $cm->create($strat);
		if(count($errors))
		{
			$_POST['errors'] = $errors;
			$this->getForm();
			exit();
		}
	}
}
