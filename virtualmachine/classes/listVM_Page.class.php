<?php

require_once "Page.interface.php";

/**
 * The list VM page contains the logic for rendering the web
 * interface for the list instructor virtual machine (vMoodleI21)
 * and list students' virtual machines (vMoodleS13) use cases.
 */
class listVM_page implements Page
{
	/**
	 * Contains the contents of the page.
	 */
	public $content;
	public $vms;
	
	public function display() {
		global $COURSE, $CFG, $SITE;
		
		$meta = "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js\"></script>";
		$meta .= "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js\"></script>";
		$meta .= "<link rel=\"stylesheet\" href=\"http://jqueryui.com/themes/base/jquery.ui.all.css\">"; 
		$navlinks = array();
		$navlinks[] = array('name' => "Virtual Machines", 'link' => '', 'type' => 'activity');
		print_header("$COURSE->shortname: Virtual Machines", $COURSE->fullname,
   	                  build_navigation($navlinks),
   	                 "", $meta, true, '', user_login_string($SITE));
		
		print_simple_box_start('center', '', '', 0, 'generalbox', 'vm_template');
			echo "<strong>Create VM</strong><br />";
			echo "<a href=\"?a=createVM&id={$COURSE->id}&strat=VBOX\">VirtualBox</a><br />";
			print_simple_box_end();
		
		print_simple_box_start('center', '', '', 0, 'generalbox', 'vm_template');
		
		if(!count($this->vms))
		{
			echo "No Virtual Machines";
		}
		else
		{

			require_once("dsFacade.class.php");
			$ds = new dsFacade();
			$single_vms = array();
			$bins = array();
			foreach($this->vms as $vm)
			{
				if($vm->isTemplate) $bins[$vm->id]['template'] = $vm;
				elseif($ds->selectUVAByV($vm->id)) $bins[$vm->parent_id]['childs'][] = $vm;
				else $single_vms[] = $vm;
			}
			
			$this->printSingleVMs($single_vms);
			echo "<hr /><br /><strong>Templates and their Children Virtual Machines</strong><br /><br />";
			foreach($bins as $bin)
			{
				$this->printBin($bin);
			}
						
			$this->displayBottom();
				
		}
		print_simple_box_end();
		print_footer($COURSE);
	}
	
	
	private function printSingleVMs($vms)
	{
		print_simple_box_start('center', '', '', 0, 'generalbox', 'vm_template');
		echo "<strong>Unassigned Virtual Machines</strong><br />";
		if(count($vms))
		{
			$this->displayHeader();
			foreach($vms as $vm)
			{
				$this->printVM($vm);
			}
			echo "</table>";
		}else{
			echo "No single VMs";
		}
		print_simple_box_end();
	}
	
	private function printBin($bin)
	{
		print_simple_box_start('center', '', '', 0, 'generalbox', 'vm_template');
		echo "<strong>Template</strong><br /><br />";
		$this->displayHeader();
		$this->printVM($bin['template']);
		echo "</table><br /><br /><br /><strong>Children</strong><br /><br />";
		$vms = @$bin['childs'];
		if(count($vms))
		{
			$this->displayHeader();
			foreach($vms as $vm)
			{
				$this->printVM($vm);
			}
			echo "</table>";
		}else{
			echo "No children.";
		}
//		print_r($bin['template']);
		print_simple_box_end();
	}
	
	private function printVM($vm)
	{
		global $COURSE;
		echo "<tr style='border-bottom: 1px solid #BBBBBB'>
		<td style='padding: 10px'>{$vm->name}</td>
		<td style='padding: 10px'>{$vm->state}</td>";
		if($vm->state != "RUNNING")
		{
			echo "<td style='padding: 10px'><a href=\"?a=startVM&id={$COURSE->id}&vm={$vm->id}\">Start</a></td>";
			echo "<td style='padding: 10px'>Stop</td>";
			echo "<td style='padding: 10px'>View</td>";
			
			echo "<td style='padding: 10px'><a href=\"?a=duplicateVM&id={$COURSE->id}&vm={$vm->id}\">Duplicate</a></td>";
			if($vm->isTemplate==0)
			{
				echo "<td style='padding: 10px'><a href=\"?a=deleteVM&id={$COURSE->id}&vm={$vm->id}\">Delete</a></td>";
				echo "<td style='padding: 10px'><a href=\"?a=setVMAsTemplate&id={$COURSE->id}&vm={$vm->id}\">Make template</a></td>";
			}
		}
		else
		{
			echo "<td style='padding: 10px'>Start</td>";
			echo "<td style='padding: 10px'><a id='stopBtn' vmname=\"{$vm->name}\" href=\"?a=stopVM&id={$COURSE->id}&vm={$vm->id}\">Stop</a></td>";
			echo "<td style='padding: 10px'><a href=\"?a=viewVM&id={$COURSE->id}&vm={$vm->id}\">View</a></td>";
			echo "<td style='padding: 10px'>Duplicate</td>";
			echo "<td style='padding: 10px'>Delete</td>";
			echo "<td style='padding: 10px'>Make template</td>";
		}
		echo "</tr>";
	}
	

	private function displayHeader()
	{
		echo "<table><tr style='border-bottom: 2px solid #666666'>
			<th><b>Name</b></th>
			<th><b>Status</b></th>
			<th colspan=\"6\"><b>Actions</b></th>
			</tr>";
	}
	
	private function displayBottom()
	{
		global $COURSE;
		/*
		$this->content = <<<EOF
		<div id="confirmStop" title="Confirm Stop">
		Are you sure you would like to stop this Virtual Machine?<br /><br />
		<center>
		<a href="stopVM&id={$COURSE->id}&vm_id=">Yes</a>
		<button id="confirmStopNo" style="margin-left: 40px">No</button>
		</center>
		</div>

		<div id="confirmCreateTemplate" title="Confirm Create Template">
		Are you sure you would like to create a Template off of this Virtual Machine?<br /><br />
		<center>
		<button id="confirmCreateTemplateYes">Yes</button>
		<button id="confirmCreateTemplateNo" style="margin-left: 40px">No</button>
		</center>
		</div>

		<script>

		$("#stopBtn").click(function(e) {
		$( "#confirmStop" ).dialog("open");
		e.preventDefault();
		return false;
		});

		$("#createTemplateBtn").click(function(e) {
		$( "#confirmCreateTemplate" ).dialog("open");
		e.preventDefault();
		return false;
		});

		$(function() {
		$( "#confirmStop" ).dialog({autoOpen:false});
		$( "#confirmCreateTemplate" ).dialog({autoOpen:false});
		});

		</script>
EOF;
*/
		echo $this->content;
	}

}
?>
