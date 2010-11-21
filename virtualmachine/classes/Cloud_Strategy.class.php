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
	public function installationDone($vm_id);
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
		/**
		 * Update Virtual Machine's state to RUNNING.
		 */
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
		/**
		 * Update Virtual Machine's state to HALTED.
		 */
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
		global $USER;

		/**
		 * Verify that all the form parameters are present and validate
		 * successfully.
		 * Name (vmName): has to be alpha numeric and have a length greater than
		 * zero.
		 * Cloud (cloud): has to be numeric and has to exist in the database.
		 */
		$errors = array();
		$ds = new dsFacade();
		if(!ctype_alnum($_POST['vmName']) && strlen($_POST['vmName']) <= 0)
		{
			$errors[] = "Please enter a name";
		}
		
		if(!(is_numeric($_POST['cloud']) && $ds->selectCloud($_POST['cloud'])))
		{
			$errors[] = "Please select valid cloud.";
		}
		
		/**
		 * If no errors have been identified, then insert the Virtual Machine 
		 * into the database.
		 */
		$vm_id = 0;
		if(!count($errors))
		{
			$vm = array('name' => $_POST['vmName'],
						'owner_id' => $USER->id,
						'isTemplate' => 0,
						'state' => 'HALTED',
						'cloud_id' => $_POST['cloud']);
			$vm_id = $ds->insertVM($vm);
		}
		
		return array($errors, $vm_id);
	}
	
	/**
	 * Logic for creating a child Virtual Machine
	 */
	public function createChildVM($vm_id, $user, $assignment_id)
	{
		require_once('dsFacade.class.php');
		require_once('cloudFacade.class.php');
		$ds = new dsFacade();
		$cl = new cloudFacade();
		$vm = $ds->selectVM($vm_id);
		$vm_params = $cl->selectVMParams($vm_id);
		
		$vm->parent_id = $vm->id;
		$vm->name .= "-{$user->username}-{$assignment_id}";
		$vm->state = "HALTED";
		$vm->isTemplate = 0;
		$vm_params->rdp_port = 0;
		$vm_params->machine_id = 0;
		unset($vm->id);
		unset($vm_params->id);

		$vm_nid = $ds->insertVM($vm);
		$vm_params->id = $vm_nid;
		$cl->insertVMParams($vm_params);
		
		$UVA = new stdclass;
		$UVA->vm_id = $vm_nid;
		$UVA->user_id = $user->id;
		$UVA->assignment_id = $assignment_id;
		
		$ds->insertUVA($UVA);
		
		return $vm_nid;
		
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
		require_once("dsFacade.class.php");
		require_once("cloudFacade.class.php");
		$ds = new dsFacade();
		$cl = new cloudFacade();
		$ds->deleteUVA($vm_id);
		return ($ds->deleteVM($vm_id) && $cl->deleteVMParams($vm_id));
	}
	
	/**
	 * Logic for setting a Virtual Machine as template
	 */
	public function setAsTemplate($vm_id) {
		require_once("dsFacade.class.php");
		$ds = new dsFacade();
		$vm = new stdclass;
		$vm->id = $vm_id;
		$vm->isTemplate = 1;
		$ds->updateVM($vm);
	}
	
	/**
	 * Logic for getting the create Virtual Machine form.
	 */
	public function createVMForm() {
	
		require_once("dsFacade.class.php");
		require_once("Cloud_Manager.class.php");
		$ds = new dsFacade();
		$cloud_data = $ds->selectClouds();
		$cloud_select = '<select id="cloud" name="cloud">';
		foreach($cloud_data as $cloud)
		{
			$cloud_select .= '<option value="' . $cloud->id . '">' . $cloud->name . '</option>';
		}
		$cloud_select .= "</select>";
		
		$errors = "";
		if(isset($_POST['errors']) && count($_POST['errors']))
		{
			$errors .= "<ul>";
			foreach(@$_POST['errors'] as $error)
			{
				$errors .= "<li style='color: red'>$error</li>";
			}
			$errors .= "</ul>";
		}
				
		$form = '<br /><strong>Create Virtual Machine</strong><br /><br />
	' . $errors . '<br />
	<form action="" method="post">
	<table>
		<tr>
			<td style="padding: 7px; text-align: right"><label for="vmName" width="200 px">Virtual Machine Name:</label></td>
			<td><input type="text" name="vmName" id="vmName"/></td>
		</tr>
		<tr>
			<td style="padding: 7px; text-align: right"><label for="vmVideoRam">Select Cloud:</label></td>
			<td>' .  $cloud_select . '</td>
		</tr>
		%%EXTRA_FORM%%
		<tr colspan=2>
			<td style="padding: 7px; text-align: right"><input type="submit" value="Save" />
			<input type="submit" value="Cancel" /></td>
		</tr>
	</table>
	</form>';
	
		require_once("createVM_Page.class.php");
		$page = new createVM_Page();
		$page->content = $form;
		return $page;
	}
	
	/**
	 * Logic for installation done.
	 */
	public function installationDone($vm_id) {
		$ds = new dsFacade();
		$vm = new stdclass;
		$vm->id = $vm_id;
		$vm->installation_done = 1;
		return $ds->updateVM($vm);
	}
}
