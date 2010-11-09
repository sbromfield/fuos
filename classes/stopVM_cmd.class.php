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
		require_once("classes/ssh/Net/SSH2.php");
		
		$vmid = required_param('vm', PARAM_INT);
		require_once("Cloud_Manager.class.php");
		$cm = Cloud_Manager::getInstance();
		$cm->stop($vmid);
		
		require_once("classes/listVM_cmd.class.php");
		$cmd = new listVM_cmd();
		echo $cmd->execute();
		
	}
}
