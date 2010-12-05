<?php

require_once "Page.interface.php";

/**
 * The list VM page contains the logic for rendering the web
 * interface for the list instructor virtual machine (vMoodleI21)
 * and list students' virtual machines (vMoodleS13) use cases.
 */
class listVMAssignment_page implements Page
{
	/**
	 * Contains the contents of the page.
	 */
	public $content;
	public $vms;
	public $template;
	public $assignment;
	
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
	
		echo "<b>Assignment:</b>{$this->assignment->name}<br /><br /><br /><strong>Template</strong><br /><br />";	
		$this->displayHeader();
		$this->printVM($this->template);

		echo "</table><br /><br /><br /><strong>Students Virtual Machines</strong><br /><br />";
		if(!count($this->vms))
		{
			echo "No Virtual Machines";
		}
		else
		{

			$this->displayHeader();
			foreach($this->vms as $vm)
			{
				$this->printVM($vm);
			}
			echo "</table>";
		
		}
		print_simple_box_end();
		print_footer($COURSE);
	}
	
	
	private function printVM($vm)
	{
		global $COURSE, $CFG;
		
		if($vm == NULL) return;
		
		echo "<tr style='border-bottom: 1px solid #BBBBBB'>
		<td style='padding: 10px'>";
		if(isset($vm->user))
		{
			echo "<a href=\"{$CFG->wwwroot}/user/view.php?id={$vm->user->id}&course={$_GET['id']}\">{$vm->user->username}</a>";
		}else{
			echo $vm->name;
		}
		echo "</td>
		<td style='padding: 10px'>{$vm->state}</td>";
		if($vm->state != "RUNNING")
		{
			echo "<td style='padding: 10px'><a href=\"?a=startVM&assid={$this->assignment->id}&id={$COURSE->id}&vm={$vm->id}\">Start</a></td>";
			echo "<td style='padding: 10px'>Stop</td>";
			echo "<td style='padding: 10px'>View</td>";
			
			if($vm->isTemplate==0)
			{
				echo "<td style='padding: 10px'><a href=\"?a=deleteVM&assid={$this->assignment->id}&id={$COURSE->id}&vm={$vm->id}\">Delete</a></td>";
			}
		}
		else
		{
			echo "<td style='padding: 10px'>Start</td>";
			echo "<td style='padding: 10px'><a id='stopBtn' vmname=\"{$vm->name}\" href=\"?a=stopVM&assid={$this->assignment->id}&id={$COURSE->id}&vm={$vm->id}\">Stop</a></td>";
			echo "<td style='padding: 10px'><a href=\"?a=viewVM&assid={$this->assignment->id}&id={$COURSE->id}&vm={$vm->id}\">View</a></td>";
			if($vm->isTemplate==0)
			{
				echo "<td style='padding: 10px'>Delete</td>";
			}
		}
		if(isset($vm->user))
		{
			echo "<td style='padding: 10px'><a href=\"{$CFG->wwwroot}/mod/assignment/submissions.php?id={$this->cm->id}&userid={$vm->user->id}&mode=single&offset=0\" onClick=\"javascript:this.target='grade3'; return openpopup('/mod/assignment/submissions.php?id={$this->cm->id}&amp;userid={$vm->user->id}&amp;mode=single&amp;offset=0', 'grade3', 'menubar=0,location=0,scrollbars,resizable,width=780,height=600', 0);\">Grade</a></td>";
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
