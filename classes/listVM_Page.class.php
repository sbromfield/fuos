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
		global $COURSE, $CFG;
		
		if(!count($this->vms))
		{
			echo "No Virtual Machines";
		}
		else
		{
			$this->displayHeader();
			foreach($this->vms as $vm)
			{
				echo "<tr style='border-bottom: 1px solid #BBBBBB'>
				<td style='padding: 10px'>{$vm->name}</td>
				<td style='padding: 10px'>{$vm->state}</td>";
				if($vm->state != "RUNNING")
				{
					echo "<td style='padding: 10px'><a href=\"?a=startVM&id={$COURSE->id}&vm={$vm->id}\">Start</a></td>";
					echo "<td style='padding: 10px'>Stop</td>";
					echo "<td style='padding: 10px'>View</td>";
				}
				else
				{
					echo "<td style='padding: 10px'>Start</td>";
					echo "<td style='padding: 10px'><a id='stopBtn' vmname=\"{$vm->name}\" href=\"?a=stopVM&id={$COURSE->id}&vm={$vm->id}\">Stop</a></td>";
					echo "<td style='padding: 10px'><a href=\"?a=viewVM&id={$COURSE->id}&vm={$vm->id}\">View</a></td>";
				}
				echo "<td style='padding: 10px'><a href=\"\">Snapshot</a></td>
				</tr>";

			}
	
			echo "</table>";
			$this->displayBottom();
		}
	}
	
	/**
	 * Displays the contents of the page.
	 */
	public function display2( $allVMs) {
	
			foreach($allVMs as $vm)
			{
		
				echo "<tr style='border-bottom: 1px solid #BBBBBB'>
				<td style='padding: 10px'>{$vm['name']}</td>
				<td style='padding: 10px'>{$vm['hd']}</td>
				<td style='padding: 10px'>{$vm['ram']}</td>
				<td style='padding: 10px'>{$vm['status']}</td>";
				if($vm['status'] != "running")
				{
					echo "<td style='padding: 10px'><a href=\"\">Start</a></td>";
					echo "<td style='padding: 10px'>Stop</td>";
					echo "<td style='padding: 10px'>View</td>";
				}
				else
				{
					echo "<td style='padding: 10px'>Start</td>";
					echo "<td style='padding: 10px'><a id='stopBtn' vmname=\"{$vm['name']}\" href=\"\">Stop</a></td>";
					echo "<td style='padding: 10px'><a href=\"{$CFG->wwwroot}/mod/virtualmachine/view_vm.php?id={$course->id}\">View</a></td>";
				}
				echo "<td style='padding: 10px'><a href=\"\">Snapshot</a></td>
				</tr>";

			}
	
			echo "</table>";

	
	}

	private function displayHeader()
	{
		echo "<table><tr style='border-bottom: 2px solid #666666'>
			<th><b>Name</b></th>
			<th><b>Status</b></th>
			<th colspan=\"4\"><b>Actions</b></th>	
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
