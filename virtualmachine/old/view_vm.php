<?php
require_once("../../config.php");
require_once("lib.php");

$course = verify_access_and_get_course();

$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);

$navlinks = array();
$navlinks[] = array('name' => "Virtual Machines", 'link' => '', 'type' => 'activity');


$meta = "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js\"></script>";


print_header("$course->shortname: Virtual Machines", $course->fullname,
                     build_navigation($navlinks),
                    "", $meta, true, '', user_login_string($SITE));

?>
    <p>
    <input id=connectionButton name=connectionButton type=button value="Connect">
    <input name=cadButton type=button value="Ctrl-Alt-Del" onClick="return sendCAD()">
    <input name=scanButton type=button value="Scancodes test" onClick="return sendScancodes()">
    </p>
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
    	  RDPWebClient.embedSWF ("<?php echo $CFG->wwwroot ?>/mod/virtualmachine/rdpweb/RDPClientUI.swf", FlashId);
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
      alert("Disconnect reason:\n" + getFlashProperty(flashId, "lastError"));
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
        flash.setProperty("serverPort", "3390");
        flash.setProperty("serverAddress", "<?php echo $_SERVER['HTTP_HOST'] ?>");
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
 
<?php print_simple_box_start('center', '1034px', '', 0, 'generalbox', 'vm_template'); ?> 
  
<?
print_simple_box_end();
print_footer($course);
?>
