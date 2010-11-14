<?php

require_once "Command.interface.php";

/**
 * The create VM assignment command encapsulates of the logic for the

 */
class createVMAssignment_cmd implements Command
{
	/**
	 * Executes create Virtual Machine assignment use case logic.
	 */
	public function execute() {
		global $USER;
		
		$assignment_id = required_param("assid", PARAM_INT);
		$assignment = get_record("assignment", "id", $assignment_id);

		// check if student vm already created.
		require_once('classes/dsFacade.class.php');
		$ds = new dsFacade();
		$vmid = NULL;
		if(!$ds->selectUVA($USER->id, $assignment_id))
		{
			/**
			 * Need to create VM for student
			 */
			require_once('classes/Cloud_Manager.class.php');
			$cm = Cloud_Manager::getInstance();
			$vmid = $cm->createChildVM($assignment->var1, $USER, $assignment_id);
		}
		
		global $CFG;
		header("Location: " . $CFG->wwwroot . "/mod/virtualmachine/index.php?a=listVM&id={$course->id}&vm={$vmid}");
		exit();
	}
}
