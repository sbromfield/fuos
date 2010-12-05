<?php

/**
 * The vMoodle Controller is a very simple class whose job is to re-route
 * a user's request to the appropriate command. From then on, the command is 
 * responsible for handling the logic. The vMoodle Controller is a singleton
 * meaning that only one instance of the vMoodle Controller might exist at
 * any given time.
 */
class vMoodle_Controller
{

	/** 
	 * Unique instance of the vMoodle Controller
	 */
	private static $instance;

	/** 
	 * Private constructor so that the system might not create
	 * many instances of the vMoodle Controller 
	 */
	private function __construct() {
	}
	
	/**
	 * Gets the instance of the vMoodle Controller. If no instance
	 * of the vMoodle Controller exists, then it creates one and
	 * returns it.
	 */
	public static function getInstance() {
		if(!isset(self::$instance))
		{
			$c = __CLASS__;
			self::$instance = new $c;
		}
		
		return self::$instance;
	}
	
	/**
	 * Dispatches the request to the appropriate command.
	 */
	public function dispatch() {
		
		/**
		 * vMoodle Controller has been asked to dispatch the request of a User
		 * to the appropriate command. To determine the appropriate command,
		 * vMoodle Controller looks at the 'a' parameter which oughts to be
		 * delivered through GET.
		 */
		$cmd = NULL;
		if(isset($_GET['a']))
		{
			switch($_GET['a'])
			{
				/**
				 * One case per use case
				 */
				case 'startVM':
					$cmd = new startVM_cmd();
					break;
				
				case 'stopVM':
					$cmd = new stopVM_cmd();
					break;
				
				case 'viewVM':
					$cmd = new viewVM_cmd();
					break;
				
				case 'listVM':
					$cmd = new listVM_cmd();
					break;
					
				case 'createVM':
					$cmd = new createVM_cmd();
					break;
					
				case 'deleteVM':
					$cmd = new deleteVM_cmd();
					break;
					
				case 'duplicateVM':
					$cmd = new duplicateVM_cmd();
					break;
					
				case 'setVMAsTemplate':
					$cmd = new setVMAsTemplate_cmd();
					break;
					
				case 'createVMAssignment':
					$cmd = new createVMAssignment_cmd();
					break;
					
				case 'installationDone':
					$cmd = new installationDone_cmd();
					break;
					
				default:
					// default to something
					return false;
			}
		}
		else
		{
			// default to something
			return false;
		}

		/**
		 * Execute the appropriate command.
		 */
		if($cmd != NULL)
			return $cmd->execute();

		return true;
	}
}
