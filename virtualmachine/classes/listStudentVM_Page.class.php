<?php

require_once "Page.interface.php";

/**
 * The list VM page contains the logic for rendering the web
 * interface for the list instructor virtual machine (vMoodleI21)
 * and list students' virtual machines (vMoodleS13) use cases.
 */
class listStudentVM_page implements Page
{
	/**
	 * Contains the contents of the page.
	 */
	public $content;
	public $assignment;
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
		
		$due = ($this->assignment->timedue - time() <= 0) ? true : false;
		
		print_simple_box_start('center', '', '', 0, 'generalbox', 'vm_template');
		
		if(!count($this->vms))
		{
			echo "No Virtual Machines";
		}
		elseif($due)
		{
			echo "Assignment is already past due date.";
		}
		else
		{
			$this->displayHeader();
			foreach($this->vms as $vm)
			{
				$cm = Cloud_Manager::getInstance();
				$vm->state = $cm->getState($vm->id);
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
				echo "</tr>";
			}
	
			echo "</table>";
			$this->displayBottom();
		}
		print_simple_box_end();
		print_footer($COURSE);
	}
	

	private function displayHeader()
	{
		echo "<table><tr style='border-bottom: 2px solid #666666'>
			<th><b>Name</b></th>
			<th><b>Status</b></th>
			<th colspan=\"5\"><b>Actions</b></th>
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
