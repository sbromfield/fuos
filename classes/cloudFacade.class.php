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
}	
