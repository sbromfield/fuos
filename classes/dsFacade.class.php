<?php
/**
 * The Data Store Facade defines and implements insert, update, select, 
 * and delete methods necessary to communicate with the Data Store. 
 */

class dsFacade
{
//association arrays

	public function selectClouds() {
		$cloud = NULL;
		if( !$cloud = get_records("vMoodle_Cloud") )
			return NULL;
			
		return $cloud;
	}
	
	public function selectCloud($cloud_id) {
		$cloud = NULL;
		if( !$cloud = get_record("vMoodle_Cloud", "id", intval($cloud_id)) )
			return NULL;
		
		return $cloud;
	}
	

	public function selectVMs() {
		$vms = NULL;
		if( !$vms = get_records("vMoodle_Virtual_Machine") )
			return NULL;
			
		return $vms;
	}
	
	public function selectVM($vm_id) {
		$vm = NULL;
		if( !$vm = get_record("vMoodle_Virtual_Machine", "id", intval($vm_id)) )
			return NULL;
		
		return $vm;
	}
	public function selectVMsFromUser($user_id) {
		$vms = NULL;
		if( !$vms = get_records("vMoodle_Virtual_Machine", "owner_id", intval($user_id)) )
			return NULL;
		
		return $vms;
	}
	public function insertVM($vm) {}
	public function updateVM($vm) {
		return update_record("vMoodle_Virtual_Machine", $vm);
	}
	public function deleteVM($vm) {}

	public function insertUser($user) {}
	public function updateUser($user) {}
	public function selectUser($user) {}
	public function deleteUser($user) {}

	public function insertVM_Assignment($vma) {}
	public function updateVM_Assignment($vma) {}
	public function selectVM_Assignment($vma) {}
	public function deleteVM_Assignment($vma) {}

	public function insertCourse($course) {}
	public function updateCourse($course) {}
	public function selectCourse($course) {}
	public function deleteCourse($course) {}
	
}	
