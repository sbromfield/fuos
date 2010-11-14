<?php
/**
 * The Data Store Facade defines and implements insert, update, select, 
 * and delete methods necessary to communicate with the Data Store. 
 */

class cloudFacade
{
//association arrays
	public function selectVMParams($vm_id)
	{
		$vm = NULL;
		if( !$vm = get_record("vMoodle_VBox_VM_Params", "id", intval($vm_id)) )
			return NULL;
		
		return $vm;
	}
	
	public function selectMachine($machine_id)
	{
		$machine = NULL;
		if( !$machine = get_record("vMoodle_VBox_Machines", "id", intval($machine_id)) )
			return NULL;
		
		return $machine;
	}
	
	public function selectMachines()
	{
		$machines = NULL;
		if( !$machines = get_records("vMoodle_VBox_Machines") )
			return NULL;
		
		return $machines;
	}
	
	public function insertVMParams($vm_params)
	{
		// Machine id = nasty hack to comply with moodle
		return insert_record('vMoodle_VBox_VM_Params', $vm_params, false, 'machine_id');
	}
	
	public function updateVMParams($vm_params) {
		return update_record("vMoodle_VBox_VM_Params", $vm_params);
	}
	
	public function deleteVMParams($vm_param_id)
	{
		return delete_records('vMoodle_VBox_VM_Params', 'id', intval($vm_param_id));
	}
}	
