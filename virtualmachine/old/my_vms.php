<?php
require_once("../../config.php");
require_once("lib.php");

$course = verify_access_and_get_course();

$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);

$meta = "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js\"></script>
  <script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js\"></script>
  <link rel=\"stylesheet\" href=\"http://jqueryui.com/themes/base/jquery.ui.all.css\">";


$navlinks = array();
$navlinks[] = array('name' => "Virtual Machines", 'link' => '', 'type' => 'activity');


print_header("$course->shortname: Virtual Machines", $course->fullname,
                     build_navigation($navlinks),
                    "", $meta, true, '', user_login_string($SITE));

                    
if(has_capability('moodle/legacy:editingteacher', $coursecontext, $USER->id, false))
{
	echo "<a href=\"{$CFG->wwwroot}/mod/virtualmachine/create_vm.php?id={$COURSE->id}\">Create VM</a><br />";

	$vms = get_vms();
	print_simple_box_start('center', '', '', 0, 'generalbox', 'vm_template');

	if(count($vms)){
		echo "<table><tr style='border-bottom: 2px solid #666666'>
			<th><b>Name</b></th>
			<th><b>HD Size</b></th>
			<th><b>RAM</b></th>
			<th><b>Status</b></th>
			<th colspan=\"4\"><b>Actions</b></th>	
			</tr>";
		foreach($vms as $vm)
		{
		
			echo "<tr style='border-bottom: 1px solid #BBBBBB'>";
			echo "<td style='padding: 10px'>{$vm['name']}</td>";
			echo "<td style='padding: 10px'>{$vm['hd']}</td>";
			echo "<td style='padding: 10px'>{$vm['ram']}</td>";
			echo "<td style='padding: 10px'>{$vm['status']}</td>";
			echo "<td style='padding: 10px'><a id='createTemplateBtn' href=\"\">Create template</a></td>";
			if($vm['status'] != "running")
			{
				echo "<td style='padding: 10px'><a href=\"\">Start</a></td>";
				echo "<td style='padding: 10px'>Stop</td>";
				echo "<td style='padding: 10px'>View</td>";
			}
			else
			{
				echo "<td style='padding: 10px'>Start</td>";
				echo "<td style='padding: 10px'><a id='stopBtn' href=\"\">Stop</a></td>";
				echo "<td style='padding: 10px'><a href=\"{$CFG->wwwroot}/mod/virtualmachine/view_vm.php?id={$course->id}\">View</a></td>";
			}

			echo "<td style='padding: 10px'><a href=\"\">Delete</a></td>";
			echo"</tr>";

		}
	
		echo "</table>";

	}else{
		echo "No VMS";
	}
	
	print_simple_box_end();

}else{
	
	$vms = get_student_vm();
	print_simple_box_start('center', '', '', 0, 'generalbox', 'vm_template');

	if(count($vms)){
		echo "<table><tr style='border-bottom: 2px solid #666666'>
			<th><b>Name</b></th>
			<th><b>HD Size</b></th>
			<th><b>RAM</b></th>
			<th><b>Status</b></th>
			<th colspan=\"4\"><b>Actions</b></th>	
			</tr>";
		foreach($vms as $vm)
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

	}else{
		echo "No VMS";
	}
	
	print_simple_box_end();	
	
}
                    
?>

<div id="confirmStop" title="Confirm Stop">
Are you sure you would like to stop this Virtual Machine?<br /><br />
<center>
<button id="confirmStopYes">Yes</button>
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
<?php
                    
print_footer($course);

?>
