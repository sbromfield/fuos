<?php

require "Cloud_Strategy.class.php";

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
		if(!$machine = $cl->selectMachine($params->id)) return false;

		$ssh = new Net_SSH2($machine->host);
		if (!$ssh->login($machine->user, $machine->pass)) {
		    return false;
		}
		
		require_once("dsFacade.class.php");
		$ds = new dsFacade();
		if(!$vm = $ds->selectVM($vm_id)) return false;

		$output = nl2br($ssh->exec('VBoxManage startvm ' . $vm->name . ' --type vrdp'));

		if(strlen(stristr($output, "ERROR")))
		{
			if("RUNNING" != $this->getState($vm_id))
			{
				return false;
			}
		}
		
		return parent::start($vm_id);
		
	}
	
	/**
	 * Cloud specific logic for stoping a Virtual Machine
	 */
	public function stop($vm_id) {

		require_once("cloudFacade.class.php");
		$cl = new cloudFacade();
		if(!$params = $cl->selectVMParams($vm_id)) return false;
		if(!$machine = $cl->selectMachine($params->id)) return false;

		$ssh = new Net_SSH2($machine->host);
		if (!$ssh->login($machine->user, $machine->pass)) {
		    return false;
		}
		
		require_once("dsFacade.class.php");
		$ds = new dsFacade();
		if(!$vm = $ds->selectVM($vm_id)) return false;
		
		$output = nl2br($ssh->exec('VBoxManage controlvm ' . $vm->name . ' poweroff'));
		if(strlen(stristr($output, "ERROR")))
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
    	  RDPWebClient.embedSWF ( "rdpweb/RDPClientUI.swf", FlashId);
    	  //swfobject.embedSWF("rdpweb/RDPClientUI.swf", FlashId, "100", "100", "9.0.0","", flashvars, params, attributes);
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
		if(!$machine = $cl->selectMachine($params->id)) return false;

		$ssh = new Net_SSH2($machine->host);
		if (!$ssh->login($machine->user, $machine->pass)) {
		    return false;
		}
		
		require_once("dsFacade.class.php");
		$ds = new dsFacade();
		if(!$vm = $ds->selectVM($vm_id)) return false;
		
		$output = nl2br($ssh->exec('VBoxManage showvminfo ' . $vm->name));
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

		$errors = array();
		if(!is_numeric($_POST['vmNumProcessors']) > 0)
		{
			$errors[] = "Please enter the number of processors";
		}
		if(!(is_numeric($_POST['vmRam']) && $_POST['vmRam'] > 0))
		{
			$errors[] = "Please enter the amount of RAM.";
		}
		if(!(is_numeric($_POST['vmVideoRam']) && $_POST['vmVideoRam'] > 0))
		{
			$errors[] = "Please enter the amount of video RAM.";
		}
		if(!(is_numeric($_POST['vmDiskSize']) && $_POST['vmDiskSize'] > 0))
		{
			$errors[] = "Please enter the disk size.";
		}
		
		if(count($errors)) return $errors;
		
		/**
		 * Strategy create logic.
		 */
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
	}
	
	/**
	 * Cloud specific logic for setting a Virtual Machine as template
	 */
	public function setAsTemplate($vm_id) {
	}
	
	/**
	 * Cloud specific logic for getting the create Virtual Machine form.
	 */
	public function createVMForm() {
		$vmNumProcessors = (isset($_POST['vmNumProcessors'])) ? $_POST['vmNumProcessors'] : '';
		$vmRam = (isset($_POST['vmRam'])) ? $_POST['vmRam'] : '';
		$vmVideoRam = (isset($_POST['vmVideoRam'])) ? $_POST['vmVideoRam'] : '';
		$vmDiskSize = (isset($_POST['vmDiskSize'])) ? $_POST['vmDiskSize'] : '';
	
		return '		<tr>
			<td style="padding: 7px; text-align: right"><label for="vmNumProcessors"># of processors:</label></td>
			<td><input type="text" name="vmNumProcessors" id="vmNumProcessors" value="' . $vmNumProcessors .'" /></td>
		</tr>
		<tr>
			<td style="padding: 7px; text-align: right"><label for="vmRam">System RAM:</label></td>
			<td><input type="text" name="vmRam" id="vmRam" value="' . $vmRam .'" />&nbsp;MB</td>
		</tr>
		<tr>
			<td style="padding: 7px; text-align: right"><label for="vmVideoRam">Video RAM:</label></td>
			<td><input type="text" name="vmVideoRam" id="vmVideoRam" value="' . $vmVideoRam .'" />&nbsp;MB</td>
		</tr>
		<tr>
			<td style="padding: 7px; text-align: right"><label for="vmDiskSize">Disk Size:</label></td>
			<td><input type="text" name="vmDiskSize" id="vmDiskSize" value="' . $vmDiskSize .'" />&nbsp;MB</td>
		</tr>
		<tr>
			<td style="padding: 7px; text-align: right"><label for="vmPass">Password:</label></td>
			<td><input type="text" name="vmPass" /></td>
		</tr>
		<tr>
			<td style="padding: 7px; text-align: right"><label for="vmConfirmPass">Confirm Password:</label></td>
			<td><input type="text" name="vmConfirmPass" /></td>
		</tr>';
	}
}
