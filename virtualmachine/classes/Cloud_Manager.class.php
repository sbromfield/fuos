<?php

/**
 * The cloud manager provides vMoodle with an interface to the different
 * clouds. This way, vMoodle can treat each virtual machine the same way
 * without dealing with the precise details of each cloud. The cloud
 * manager will receive a request and will determine what kind of cloud
 * the request belongs to and select the aproriate strategy to deal with
 * the request. In other words, the cloud manager will re-route the
 * request to the indicated handler. Furthermore, the cloud manager is
 * a singleton. Therefore, only one instance of the cloud manager can
 * exist at any given time.
 */
class Cloud_Manager
{

	/** 
	 * Unique instance of the cloud manager
	 */
	private static $instance;

	/** 
	 * Private constructor so that the system might not create
	 * many instances of the cloud_manager 
	 */
	private function __construct() {
	}
	
	/**
	 * Gets the instance of the cloud manager. If no instance
	 * of the cloud manager exists, then it creates one and
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
	 * It infers the appropriate strategy to use based on the
	 * nature of the request.
	 */
	private function getStrategy($crit) {
		global $CFG;

		$s = "";
		if(is_numeric($crit))
		{
			require_once("dsFacade.class.php");
			$ds = new dsFacade();
			$vm = $ds->selectVM($crit);
			if(!$vm) return NULL;
			$cloud = $ds->selectCloud($vm->cloud_id);
			if(!$cloud) return NULL;
			$s = $cloud->type;
		}else{
			$s = $crit;
		}
		
		switch($s)
		{
			case "VBOX":
				require_once("VBox_Strategy.class.php");
				$strategy = new VBox_Strategy();
				break;
		}

		return $strategy;
	}
	
	/**
	 * It re-routes the start operation to the apropriate 
	 * strategy.
	 */
	public function start($vm_id) {
		if(!$s = $this->getStrategy($vm_id)) return false;
		return $s->start($vm_id);
	}
	
	/**
	 * It re-routes the stop operation to the apropriate
	 * strategy.
	 */
	public function stop($vm_id) {
		if(!$s = $this->getStrategy($vm_id)) return false;
		return $s->stop($vm_id);
	}
	
	/**
	 * It re-routes the view operation to the apropriate 
	 * strategy.
	 */
	public function view($vm_id) {
		if(!$s = $this->getStrategy($vm_id)) return "";
		return $s->view($vm_id);
	}
	
	/**
	 * It re-routes the get state operation to the apropriate 
	 * strategy.
	 */
	public function getState($vm_id) {
	}
	
	/**
	 * It re-routes the create operation to the apropriate 
	 * strategy.
	 */
	public function create($strat) {
		if(!$s = $this->getStrategy($strat)) return false;
		return $s->create();
	}
	
	/**
	 * It re-routes the create child operation to the appropriate
	 * strategy.
	 */
	public function createChildVM($vm_id, $user, $assignment_id)
	{
		if(!$s = $this->getStrategy($vm_id)) return false;
		return $s->createChildVM($vm_id, $user, $assignment_id);
	}
	
	/**
	 * It re-routes the duplicate operation to the apropriate 
	 * strategy.
	 */
	public function duplicate($vm_id) {
	}
	
	/**
	 * It re-routes the delete operation to the apropriate 
	 * strategy.
	 */
	public function delete($vm_id) {
		if(!$s = $this->getStrategy($vm_id)) return false;
		return $s->delete($vm_id);
	}
	
	/**
	 * It re-routes the set as template operation to the 
	 * apropriate strategy.
	 */
	public function setAsTemplate($vm_id) {
		if(!$s = $this->getStrategy($vm_id)) return false;
		return $s->setAsTemplate($vm_id);
	}
	
	/**
	 * It re-routes the create VM form operation to the 
	 * apropriate strategy.
	 */
	public function createVMForm($strat) {
		if(!$s = $this->getStrategy($strat)) return "";
		return $s->createVMForm();
	}
}
