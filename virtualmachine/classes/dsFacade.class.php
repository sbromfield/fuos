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
	public function insertVM($vm) {
		$vm_id = insert_record('vMoodle_Virtual_Machine', $vm);
		return $vm_id;
	}
	public function updateVM($vm) {
		return update_record("vMoodle_Virtual_Machine", $vm);
	}
	public function deleteVM($vm_id) {
		return delete_records('vMoodle_Virtual_Machine', 'id', intval($vm_id));
	}

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
	
	public function selectUVA($user, $assignment)
	{	
		return get_record("vMoodle_UVA", "user_id", $user, "assignment_id", $assignment);
	}
	
	public function selectUVAByV($vm_id)
	{
		return get_record("vMoodle_UVA", "vm_id", $vm_id);
	}

	public function selectUVAByA($assignment_id)
	{
		return get_records("vMoodle_UVA", "assignment_id", $assignment_id);
	}
	
	public function deleteUVA($vm_id)
	{
		return delete_records('vMoodle_UVA', 'vm_id', intval($vm_id));
	}
	
	public function insertUVA($UVA)
	{
		return insert_record('vMoodle_UVA', $UVA);
	}
	
	
}	
