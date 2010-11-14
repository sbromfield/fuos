<?php
require_once("../../config.php");
require_once("lib.php");

$course = verify_access_and_get_course();

$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);


$navlinks = array();
$navlinks[] = array('name' => "Virtual Machines", 'link' => '', 'type' => 'activity');

require_capability('moodle/legacy:editingteacher', $coursecontext);

print_header("$course->shortname: Virtual Machines", $course->fullname,
                     build_navigation($navlinks),
                    "", "", true, '', user_login_string($SITE));
                    
/**
 * Start Lionel Nimmo
 */
?> 
<br /><strong>Create Virtual Machine<strong><br /><br />
<form action="createVMObj.php" method="post">
<table>
	<tr>
		<td style="padding: 7px; text-align: right"><label for="vmName" width="200 px">Virtual Machine Name:</label></td>
		<td><input type="text" name="vmName" id="vmName"/></td>
	</tr>
<!--	<tr>
		<td style="padding: 7px; text-align: right"><label for="vmNumProcessors"># of processors:</label></td>
		<td><input type="text" name="vmNumProcessors" id="vmNumProcessors"/></td>
	</tr> -->
	<tr>
		<td style="padding: 7px; text-align: right"><label for="vmRam">System RAM:</label></td>
		<td><input type="text" name="vmRam" id="vmRam" />&nbsp;MB</td>
	</tr>
<!--	<tr>
		<td style="padding: 7px; text-align: right"><label for="vmVideoRam">Video RAM:</label></td>
		<td><input type="text" name="vmVideoRam" id="vmVideoRam"/>&nbsp;MB</td>
	</tr> -->
	<tr>
		<td style="padding: 7px; text-align: right"><label for="vmPeripheral1">Peripheral 1:</label></td>
		<td><select name="vmPeripheral1" id="vmPeripheral1">
		<option value="none">--none--</option>
		<option value="Disk-Drive">Disk Drive</option>
		<option value="CD-Rom">CD Rom</option>
		</select></td>
	</tr>
	<tr>
		<td style="padding: 7px; text-align: right"><label for="vmPeripheral2">Peripheral 2:</label></td>
		<td><select name="vmPeripheral2" id="vmPeripheral2">
		<option value="none">--none--</option>
		<option value="Disk-Drive">Disk Drive</option>
		<option value="CD-Rom">CD Rom</option>
		</select></td>
	</tr>
	<tr>
		<td style="padding: 7px; text-align: right"><label for="iso">Select ISO:</label></td>
		<td><select name="iso" id="iso">
		<option value="Windows XP">Windows XP</option>
		<option value="Debian">Debian</option>
		<option value="Ubuntu">Ubuntu</option>
		<option value="FreeBSD">FreeBSD</option>
		</select></td>
	</tr>
	<tr>
		<td style="padding: 7px; text-align: right"><label for="vmPass">Password:</label></td>
		<td><input type="text" name="vmPass" /></td>
	</tr>
	<tr>
		<td style="padding: 7px; text-align: right"><label for="vmConfirmPass">Confirm Password:</label></td>
		<td><input type="text" name="vmConfirmPass" /></td>
	</tr>
	<tr colspan=2>
		<td style="padding: 7px; text-align: right"><input type="submit" value="Save" />
		<input type="submit" value="Cancel" /></td>
	</tr>
</table>
</form>
<?php
/**
 * End Lionel Nimmo 
 */                    
print_footer($course);

