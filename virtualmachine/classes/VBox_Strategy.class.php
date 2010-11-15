<?php

require "Cloud_Strategy.class.php";
require_once("classes/ssh/Net/SSH2.php");

/**
 * The Vbox strategy extends the cloud strategy which in turn implements the
 * cloud strategy interface. Unlike the cloud strategy, the Vbox strategy
 * implements cloud specific logic.
 */
class VBox_Strategy extends Cloud_Strategy
{	
	/**
	 * Cloud specific logic for starting a Virtual Machine
	 */
	public function start($vm_id) {
		require_once("cloudFacade.class.php");
		$cl = new cloudFacade();
		if(!$params = $cl->selectVMParams($vm_id)) return false;

		$machines = $cl->selectMachines();
		if(!$machines || !is_array($machines))
		{
			return false;
		};
	
		$ssh = NULL;
		$machine = NULL;
		foreach($machines as $_machine)
		{
			$_ssh = new Net_SSH2($_machine->host);
			if ($_ssh->login($_machine->user, $_machine->pass)) {
		    	$ssh = $_ssh;
		    	$machine = $_machine;
		    	break;
			}
		}
		
		if(!$ssh)
		{
			return false;
		}
		
		require_once("dsFacade.class.php");
		$ds = new dsFacade();
		if(!$vm = $ds->selectVM($vm_id)) return false;

		$output = nl2br($ssh->exec('VBoxManage startvm "' . $vm->name . '" --type vrdp'));

		if(strlen(stristr($output, "ERROR")))
		{
			if("RUNNING" != $this->getState($vm_id))
			{
				return false;
			}
		}
		
		$output = nl2br($ssh->exec('VBoxManage showvminfo "' . $vm->name . '"'));
		preg_match('/VRDP port\:[\ ]*([0-9]*)/i', $output, $pieces);
		
		$vmparam = new stdclass;
		$vmparam->id = $vm_id;
		$vmparam->machine_id = $machine->id;
		$vmparam->rdp_port = $pieces[1];
		$cl->updateVMParams($vmparam);
		
		return parent::start($vm_id);
		
	}
	
	/**
	 * Cloud specific logic for stoping a Virtual Machine
	 */
	public function stop($vm_id) {

		require_once("cloudFacade.class.php");
		$cl = new cloudFacade();
		if(!$params = $cl->selectVMParams($vm_id)) return false;
		if(!$machine = $cl->selectMachine($params->machine_id)) return false;

		$ssh = new Net_SSH2($machine->host);
		if (!$ssh->login($machine->user, $machine->pass)) {
		    return false;
		}
		
		require_once("dsFacade.class.php");
		$ds = new dsFacade();
		if(!$vm = $ds->selectVM($vm_id)) return false;
		
		$output = nl2br($ssh->exec('VBoxManage controlvm "' . $vm->name . '" poweroff'));
//		die($output);
		if(preg_match("/ERROR/i", $output))
		{		
			if("HALTED" != $this->getState($vm_id))
			{
				return false;
			}
		}

		return parent::stop($vm_id);
	}
	
	/**
	 * Cloud specific logic for viewing a Virtual Machine
	 */
	public function view($vm_id) {	
		require_once("cloudFacade.class.php");
		$cl = new cloudFacade();
		if(!$vm = $cl->selectVMParams($vm_id)) return "";
		if(!$machine = $cl->selectMachine($vm->machine_id)) return "";
		
		$host = $machine->host;
		if($machine->host == "127.0.0.1" || $machine->host == "localhost")
		{
			$host = $_SERVER['HTTP_HOST'];
		}
		$port = $vm->rdp_port;
		
		require_once("viewVM_Page.class.php");
		$page = new viewVM_Page();
		$page->content = <<<EOF
	<div id="FlashRDPContainer" style='width: 100%;'>
    	<div id="FlashRDP" style='width: 100%;'></div>
	</div>
	
  <div id="Information"></div>

  <iframe style="height:0px;width:0px;visibility:hidden" src="about:blank">
     this frame prevents back forward cache in Safari
  </iframe>

  <script type="text/javascript">
	var fFlashLoaded = false;
    var FlashVersion = "";
    var FlashId = "FlashRDP";
    
    
    $().ready(function(){
    	$.getScript('rdpweb/webclient.js');
	    $.getScript('rdpweb/swfobject.js', function() {
			  Init();
		});
    });

    function Init()
    {
    
		if(!fFlashLoaded) {  
		    var flashvars = {};
      	var params = {};
      	params.wmode="opaque";
      	params.menu="true";
      	params.bgcolor="#FFFFFF";
      	params.quality="low";
      	params.allowScriptAccess="always";
      	params.flashId=FlashId;
	
        var attributes = {};
		
	      document.getElementById("Information").innerHTML = "Loading Flash...";
    	  //RDPWebClient.embedSWF ( "rdpweb/RDPClientUI.swf", FlashId);
    	  swfobject.embedSWF("rdpweb/RDPClientUI.swf", FlashId, "100", "100", "9.0.0","", flashvars, params, attributes);
		}
    }
    
    function getFlashProperty(id, name)
    {
    	var flash = RDPWebClient.getFlashById(id);
  		return (flash ? flash.getProperty(name) : '');
    }
    
    /*
     * RDP client event handlers.
     * They will be called when the flash movie is ready and some event occurs.
     * Note: the function name must be the "flash_id" + "event name".
     */
    function RDPWebEventLoaded(flashId)
    {
      /* The movie loading is complete, the flash client is ready. */
      fFlashLoaded = true;
      FlashVersion = getFlashProperty(flashId, "version");
      document.getElementById("Information").innerHTML = "Version: " + FlashVersion;
      
      Connect();
    }
    function RDPWebEventConnected(flashId)
    {
      /* RDP connection has been established */
      document.getElementById("Information").innerHTML =
          "Version: " + FlashVersion + ". Connected to " + getFlashProperty(flashId, "serverAddress");
    }
    function RDPWebEventServerRedirect(flashId)
    {
      /* RDP connection has been established */
      document.getElementById("Information").innerHTML =
          "Version: " + FlashVersion + ". Redirection by " + getFlashProperty(flashId, "serverAddress");
    }
    function RDPWebEventDisconnected(flashId)
    {
      /* RDP connection has been lost */
      alert("Disconnect reason:" + getFlashProperty(flashId, "lastError"));
      document.InputForm.connectionButton.value = "Connect";
      document.InputForm.onsubmit=function() {return Connect();}
      document.getElementById("Information").innerHTML = "Version: " + FlashVersion;
    }
    
    /* 
     * Examples how to call a flash method from HTML.
     */
    function Connect()
    {
      if (fFlashLoaded != true)
      {
          return false;
      }
      
      /* Do something with the input form:
       *
       * to hide:      document.getElementById("InputForm").style.display = 'none';
       * to redisplay: document.getElementById("InputForm").style.display = 'block';
       *
       * Just rename the button and attach another submit action.
       */
      //document.InputForm.connectionButton.value = "Disconnect";
      //document.InputForm.onsubmit=function() {return Disconnect();}
      
      var flash = RDPWebClient.getFlashById(FlashId);

      if (flash)
      {
        /* Setup the client parameters. */
        flash.setProperty("serverPort", {$port});
        flash.setProperty("serverAddress",  "{$host}" );
        flash.setProperty("logonUsername", "");
        flash.setProperty("logonPassword", "");
        
		    flash.width = "1024";
        flash.height = "768";
        flash.setProperty("displayWidth", "1024");
        flash.setProperty("displayHeight", "768");
      
        document.getElementById("Information").innerHTML =
            "Version: " + FlashVersion +". Connecting to " + getFlashProperty(FlashId, "serverAddress") + "...";
        
        /* Establish the connection. */
        flash.connect();
      }
      
      /* If false is returned, the form will not be submitted and we stay on the same page. */
      return false;
    }
    
    function Disconnect()
    {
      var flash = RDPWebClient.getFlashById(FlashId);
      if (flash)
      {
        flash.disconnect();
      }
      
      /* Restore the "Connect" form. */
      document.InputForm.connectionButton.value = "Connect";
      document.InputForm.onsubmit=function() {return Connect();}
      
      /* If false is returned, the form will not be submitted and we stay on the same page. */
      return false;
    }
    function sendCAD()
    {
      var flash = RDPWebClient.getFlashById(FlashId);
      if (flash)
      {
        flash.keyboardSendCAD();
      }
      
      /* If false is returned, the form will not be submitted and we stay on the same page. */
      return false;
    }
    function sendScancodes()
    {
      var flash = RDPWebClient.getFlashById(FlashId);
      if (flash)
      {
        flash.keyboardSendScancodes('1f 9f 2e ae 1e 9e 31 b1');
      }
      
      /* If false is returned, the form will not be submitted and we stay on the same page. */
      return false;
    }
  </script>
EOF;
		return $page;
	}
	
	/**
	 * Cloud specific logic for getting a Virtual Machine's state
	 */
	public function getState($vm_id) {
		require_once("cloudFacade.class.php");
		$cl = new cloudFacade();
		if(!$params = $cl->selectVMParams($vm_id)) return false;
		if(!$machine = $cl->selectMachine($params->machine_id)) return false;

		$ssh = new Net_SSH2($machine->host);
		if (!$ssh->login($machine->user, $machine->pass)) {
		    return false;
		}
		
		require_once("dsFacade.class.php");
		$ds = new dsFacade();
		if(!$vm = $ds->selectVM($vm_id)) return false;
		
		$output = nl2br($ssh->exec('VBoxManage showvminfo "' . $vm->name . '"'));
		$state = "";
		preg_match('/State\: ([a-z\ ]*)/i', $output, $pieces);

		switch(trim($pieces[1]))
		{
			case "powered off":
				$state = "HALTED";
				break;
			case "running":
				$state = "RUNNING";
				break;
		}

		return $state;
	}
	
	/**
	 * Cloud specific logic for creating a Virtual Machine
	 */
	public function create() {

		/**
		 * Verify that all the form parameters are present and validate
		 * successfully.
		 * Processors (vmNumProcessors): has to be numeric and greater than
		 * zero.
		 * RAM (vmRam): has to be numeric and greater than zero.
		 * Disk Size (vmDiskSize): has to be numeric and greater than zero.
		 */
		$errors = array();
		if(!is_numeric($_POST['vmNumProcessors']) > 0)
		{
			$errors[] = "Please enter the number of processors";
		}
		if(!(is_numeric($_POST['vmRam']) && $_POST['vmRam'] > 0))
		{
			$errors[] = "Please enter the amount of RAM.";
		}
		if(!(is_numeric($_POST['vmDiskSize']) && $_POST['vmDiskSize'] > 0))
		{
			$errors[] = "Please enter the disk size.";
		}
		
		/**
		 * Invoke parent creation logic. Time complexity of parent is constant
		 * time.
		 */
		list($parent_errors, $vm_id) = parent::create();
		
		/**
		 * Check if parent creation logic failed to create VM given that no
		 * errors where detected in the parent creation logic.
		 */
		if($vm_id == 0 && !count($parent_errors))
		{
			$errors[] = "An unexpected error happened.";
		}
		
		/**
		 * Check if errors have been detected. If so, return errors.
		 */
		$errors = array_merge($parent_errors, $errors);
		if(count($errors)) return array($errors, 0);
		
		/**
		 * Strategy create logic.
		 */
		$cl = new cloudFacade();
		
		/**
		 * Insert Virtual Machine Parameters. Time complexity for VM Params 
		 * insertion is constant time.
		 */
		$vm_params = array( 'id' => $vm_id,
							'machine_id' => 'NULL',
							'rdp_port' => 0,
							'numProcessors' => $_POST['vmNumProcessors'],
							'ram' => $_POST['vmRam'],
							'video_ram' => 0,
							'pass' => '',
							'disk_size' => $_POST['vmDiskSize']);
							
		if(!$cl->insertVMParams($vm_params))
		{
			/**
			 * If Virtual Machine Parameters insertion failed, undo changes and
			 * return errors.
			 * Undoing changes complexity is m, where m is the number of 
			 * machines in the cloud.
			 */
			$ds = new dsFacade();
			$ds->deleteVM($vm_id);
			$errors[] = 'Unable to insert VM Params.';
			return array($errors, 0);
		}

		/**
		 * Get Random SSH connection to one of the machines in the cloud to
		 * create the Virtual Machine in the cloud. Time complexity is m, where
		 * m is the number of machines in the cloud.
		 */
		list($ssh, $machine) = $this->getSSHConnection();		
		
		/**
		 * If unable to get an SSH connection, undo changes and return errors.
		 * Undoing changes complexity is m, where m is the number of 
		 * machines in the cloud.
		 */
		if(!$ssh)
		{
			$ds = new dsFacade();
			parent::delete($vm_id);
			$errors[] = 'Unable to get SSH Connection.';
			return array($errors, 0);
		}
		
		/**
		 * Select Virtual Machine created by parent.
		 */
		$ds = new dsFacade();
		if(!$vm = $ds->selectVM($vm_id))
		{
			/**
			 * If unable to fetch Virtual Machine created by parent, undo changes 
			 * and return errors.
			 * Undoing changes complexity is m, where m is the number of 
			 * machines in the cloud.
			 */
			parent::delete($vm_id);
			$errors[] = 'Unable to select VM';
			return array($errors, 0);
		}

		/**
		 * Create Virtual Machine in the hypervisor.
		 */
		$output = $ssh->exec(
			"VBoxManage createvm --name \"{$vm->name}\" --register"
		);
		if(strlen(stristr($output, "ERROR")))
		{
			/**
			 * If unable to create virtual machine in the hypervisor, undo 
			 * changes and return errors.
			 * Undoing changes complexity is m, where m is the number of 
			 * machines in the cloud.
			 */
			parent::delete($vm_id);
			$errors[] = 'Virtualbox error at creating VM.';
			return array($errors, 0);
		}
		
		/**
		 * Create Virtual Machine's VDI file in the cloud.
		 */
		$output = $ssh->exec(
			"VBoxManage createvdi --filename \"{$vm->name}.vdi\" "
			. "--size {$vm_params['disk_size']} --remember"
		);
		if(strlen(stristr($output, "ERROR")))
		{
			/**
			 * If unable to create virtual machine's VDIA in the cloud, undo 
			 * changes and return errors.
			 * Undoing changes complexity is m, where m is the number of 
			 * machines in the cloud.
			 */
			parent::delete($vm_id);
			$ssh->exec("VBoxManage unregistervm \"{$vm->name}\" --delete");
			$errors[] = 'Virtualbox error at creating VDI';
			return array($errors, 0);
		}
		
		/**
		 * Determine what ISO to use as the installation media.
		 */
		$iso = "";
		switch($_POST['iso'])
		{
			case 'Arch':
				$iso = 'archlinux.iso';
				break;
			case 'Ubuntu':
				$iso = 'ubuntu.iso';
				break;
		}
		
		/**
		 * Configure Virtual Machine. This is broken down into the following:
		 * 1. Create a SATA Controller.
		 * 2. Attach VDI to Sata Controller.
		 * 3. Modify the Virtual Machine to adjust memory and cpus requirements
		 *    as well as other parameters that are required for proper operation.
		 * 4. Create an IDE Controller.
		 * 5. Attatch ISO to IDE Controller.
		 */
		$output = $ssh->exec(
			"VBoxManage storagectl \"{$vm->name}\" --name \"SATA CONTROLLER\" "
			. "--add sata"
		);
		$output .= $ssh->exec(
			"VBoxManage storageattach \"{$vm->name}\" "
			. "--storagectl \"{SATA CONTROLLER}\" --port 0 --device 0 "
			. "--type hdd --medium \"{$vm->name}.vdi\""
		);
		$output .= $ssh->exec(
			"VBoxManage modifyvm \"{$vm->name}\" --memory {$vm_params['ram']} "
			. "--cpus {$vm_params['numProcessors']} --vrdp on "
			. "--vrdpmulticon on --vrdpreusecon on --vrdpport 3390-3450 "
			. "--mouse usbtablet --keyboard usb"
		);
		$output = $ssh->exec(
			"VBoxManage storagectl \"{$vm->name}\" --name \"IDE CONTROLLER\" "
			. "--add ide"
		);
		$output = $ssh->exec(
			"VBoxManage storageattach \"{$vm->name}\" "
			. "--storagectl \"IDE CONTROLLER\" --type dvddrive --port 0 "
			. "--device 0 --medium ~/ISOS/" . $iso
		);
		if(strlen(stristr($output, "ERROR")))
		{
			/**
			 * If unable to configure virtual machine, undo changes and 
			 * return errors.
			 * Undoing changes complexity is m, where m is the number of 
			 * machines in the cloud.
			 */
			$this->delete($vm_id);
			$errors[] = 'Virtualbox error at setting up Virtual Machine.';
			return array($errors, 0);
		}
		
		return array($errors, $vm->id);
		 
	}
	
	/**
	 * Cloud specific logic for creating a child Virtual Machine
	 */
	public function createChildVM($vm_id, $user, $assignment_id)
	{
		$vm_nid = parent::createChildVM($vm_id, $user, $assignment_id);
		
		require_once("dsFacade.class.php");
		require_once("cloudFacade.class.php");
		$cl = new cloudFacade();
		$ds = new dsFacade();
		$parent_vm = $ds->selectVM($vm_id);
		$vm = $ds->selectVM($vm_nid);
		$vm_params = $cl->selectVMParams($vm_nid);
		
		list($ssh, $machine) = $this->getSSHConnection();
		echo nl2br($ssh->exec('VBoxManage createvm --name "' . $vm->name . '" --register'));
		$output = nl2br($ssh->exec('VBoxManage storagectl "' . $vm->name .'" --name "SATA CONTROLLER" --add sata'));
		$output .= nl2br($ssh->exec('VBoxManage storageattach "' . $vm->name . '" --storagectl "SATA CONTROLLER" --port 0 --device 0 --type hdd --medium "' . $parent_vm->name . '.vdi"'));
		$output .= nl2br($ssh->exec('VBoxManage modifyvm "' . $vm->name . '" --memory ' . $vm_params->ram . ' --cpus ' . $vm_params->numProcessors . ' --vrdp on --vrdpmulticon on --vrdpreusecon on --vrdpport 3390-3450 --mouse usbtablet --keyboard usb'));
		$output = $ssh->exec('VBoxManage storagectl "' . $vm->name . '" --name "IDE CONTROLLER" --add ide');
		
		return $vm_nid;
		
	}
	
	/**
	 * Cloud specific logic for duplicating a Virtual Machine
	 */
	public function duplicate($vm_id) {
	}
	
	/**
	 * Cloud specific logic for deleting a Virtual Machine
	 */
	public function delete($vm_id) {
		require_once("dsFacade.class.php");
		$ds = new dsFacade();
		if(!$vm = $ds->selectVM($vm_id))
		{
			return false;
		}

		if($vm->isTemplate)
		{
			return false;
		}
		
		if($vm->isTemplate != 0) return false;
		
		require_once("cloudFacade.class.php");
		$cl = new cloudFacade();
		if(!$params = $cl->selectVMParams($vm_id)) return false;

		$machines = $cl->selectMachines();
		if(!$machines || !is_array($machines))
		{
			return false;
		};
	
		$ssh = NULL;
		foreach($machines as $_machine)
		{
			$_ssh = new Net_SSH2($_machine->host);
			if ($_ssh->login($_machine->user, $_machine->pass)) {
		    	$ssh = $_ssh;
		    	break;
			}
		}
		
		if(!$ssh)
		{
			return false;
		}
		
		$ssh->exec('VBoxManage storagectl ' . $vm->name . ' --name "SATA CONTROLLER" --remove');
		$ssh->exec('VBoxManage storagectl ' . $vm->name . ' --name "IDE CONTROLLER" --remove');
		$ssh->exec('VBoxManage unregistervm ' . $vm->name . ' --delete');
		$ssh->exec('VBoxManage unregisterimage disk ' . $vm->name . '.vdi --delete');
		
		return parent::delete($vm_id);
	}
	
	/**
	 * Cloud specific logic for setting a Virtual Machine as template
	 */
	public function setAsTemplate($vm_id) {
		parent::setAsTemplate($vm_id);
		
		require_once("cloudFacade.class.php");
		$cl = new cloudFacade();

		list($ssh, $machine) = $this->getSSHConnection();
		
		require_once("dsFacade.class.php");
		$ds = new dsFacade();
		if(!$vm = $ds->selectVM($vm_id)) return false;
		
		echo nl2br($ssh->exec("VBoxManage snapshot {$vm->name} take Base"));
		
		return true;
	}
	
	/**
	 * Cloud specific logic for getting the create Virtual Machine form.
	 */
	public function createVMForm() {
		$page = parent::createVMForm();
		
		$vmNumProcessors = (isset($_POST['vmNumProcessors'])) ? $_POST['vmNumProcessors'] : '';
		$vmRam = (isset($_POST['vmRam'])) ? $_POST['vmRam'] : '';
		$vmVideoRam = (isset($_POST['vmVideoRam'])) ? $_POST['vmVideoRam'] : '';
		$vmDiskSize = (isset($_POST['vmDiskSize'])) ? $_POST['vmDiskSize'] : '';
	
		$form_extra = '		<tr>
			<td style="padding: 7px; text-align: right"><label for="vmNumProcessors"># of processors:</label></td>
			<td><input type="text" name="vmNumProcessors" id="vmNumProcessors" value="' . $vmNumProcessors .'" /></td>
		</tr>
		<tr>
			<td style="padding: 7px; text-align: right"><label for="vmRam">System RAM:</label></td>
			<td><input type="text" name="vmRam" id="vmRam" value="' . $vmRam .'" />&nbsp;MB</td>
		</tr>
		<!--tr>
			<td style="padding: 7px; text-align: right"><label for="vmVideoRam">Video RAM:</label></td>
			<td><input type="text" name="vmVideoRam" id="vmVideoRam" value="' . $vmVideoRam .'" />&nbsp;MB</td>
		</tr-->
		<tr>
			<td style="padding: 7px; text-align: right"><label for="vmDiskSize">Disk Size:</label></td>
			<td><input type="text" name="vmDiskSize" id="vmDiskSize" value="' . $vmDiskSize .'" />&nbsp;MB</td>
		</tr>
		<tr>
			<td style="padding: 7px; text-align: right"><label for="iso">Select ISO:</label></td>
			<td><select name="iso" id="iso">
			<option value="Arch">Arch Linux</option>
			<option value="Ubuntu">Ubuntu</option>
			</select></td>
		</tr>
		<!--tr>
			<td style="padding: 7px; text-align: right"><label for="vmPass">Password:</label></td>
			<td><input type="text" name="vmPass" /></td>
		</tr>
		<tr>
			<td style="padding: 7px; text-align: right"><label for="vmConfirmPass">Confirm Password:</label></td>
			<td><input type="text" name="vmConfirmPass" /></td>
		</tr-->';
		
		$page->content = str_replace('%%EXTRA_FORM%%', $form_extra, $page->content);
		
		return $page;
	}
	
	
	private function getSSHConnection($machine=NULL)
	{
		require_once("cloudFacade.class.php");
		$cl = new cloudFacade();

		$machines = array();
		if(!$machine)
		{
			$machines = $cl->selectMachines();
		}else{
			$machines[] = $machine;
		}
	
		$ssh = NULL;
		$machine = NULL;
		foreach($machines as $_machine)
		{
			$_ssh = new Net_SSH2($_machine->host);
			if ($_ssh->login($_machine->user, $_machine->pass)) {
		    	$ssh = $_ssh;
		    	$machine = $_machine;
		    	break;
			}
		}
		
		return array( $ssh, $machine );
	}
}
