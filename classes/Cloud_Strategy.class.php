<?php

/**
 * The Cloud Strategy Interface defines the methods that a cloud strategy
 * should implement.
 */
interface ICloud_Strategy {
	public function start($vm_id);
	public function stop($vm_id);
	public function view($vm_id);
	public function getState($vm_id);
	public function create();
	public function duplicate($vm_id);
	public function delete($vm_id);
	public function setAsTemplate($vm_id);
	public function createVMForm();
}

/**
 * The cloud strategy is an abstract class that implements the cloud strategy
 * interface to provide the primitive logic for interfacing with any type of
 * cloud. Ideally, every strategy will extend the cloud strategy and on each
 * operation call the cloud strategy's logic first and then proceed to the
 * execute cloud specific logic.
 */
abstract class Cloud_Strategy implements ICloud_Strategy
{	
	/**
	 * Logic for starting a Virtual Machine
	 */
	public function start($vm_id) {
		require_once("dsFacade.class.php");
		$ds = new dsFacade();
		$vm = new stdclass;
		$vm->id = $vm_id;
		$vm->state = 'RUNNING';
		return $ds->updateVM($vm);
	}
	
	/**
	 * Logic for stoping a Virtual Machine
	 */
	public function stop($vm_id) {
		require_once("dsFacade.class.php");
		$ds = new dsFacade();
		$vm = new stdclass;
		$vm->id = $vm_id;
		$vm->state = 'HALTED';
		return $ds->updateVM($vm);
	}
	
	/**
	 * Logic for viewing a Virtual Machine
	 */
	public function view($vm_id) {
	}
	
	/**
	 * Logic for getting a Virtual Machine's state
	 */
	public function getState($vm_id) {
	}
	
	/**
	 * Logic for creating a Virtual Machine
	 */
	public function create() {
		die("Parent Create");
	}
	
	/**
	 * Logic for duplicating a Virtual Machine
	 */
	public function duplicate($vm_id) {
	}
	
	/**
	 * Logic for deleting a Virtual Machine
	 */
	public function delete($vm_id) {
	}
	
	/**
	 * Logic for setting a Virtual Machine as template
	 */
	public function setAsTemplate($vm_id) {
	}
	
	/**
	 * Logic for getting the create Virtual Machine form.
	 */
	public function createVMForm() {
		return '';
	}
}
