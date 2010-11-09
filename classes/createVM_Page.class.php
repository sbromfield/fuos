<?php

require_once "Page.interface.php";

/**
 * The create VM page contains the logic for rendering the web
 * interface for the create virtual machine (vMoodleI01) use case.
 */
class createVM_page implements Page
{
	/**
	 * Contains the contents of the page.
	 */
	public $content;
	public $cloud_data;
	public $extra_form = "";
	public $errors = array();
	
	/**
	 * Contains the form that will be embedded into the page.
	 */
	public $form = '<br /><strong>Create Virtual Machine</strong><br /><br />
	%%ERRORS%%<br />
	<form action="" method="post">
	<table>
		<tr>
			<td style="padding: 7px; text-align: right"><label for="vmName" width="200 px">Virtual Machine Name:</label></td>
			<td><input type="text" name="vmName" id="vmName"/></td>
		</tr>
		<tr>
			<td style="padding: 7px; text-align: right"><label for="vmVideoRam">Select Cloud:</label></td>
			<td>%%CLOUD_SELECT%%</td>
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
		%%EXTRA_FORM%%
		<tr colspan=2>
			<td style="padding: 7px; text-align: right"><input type="submit" value="Save" />
			<input type="submit" value="Cancel" /></td>
		</tr>
	</table>
	</form>';
	
	/**
	 * Displays the contents of the page.
	 */
	public function display() {
		global $COURSE, $SITE;
		
		$navlinks = array();
		$navlinks[] = array('name' => "Virtual Machines", 'link' => '', 'type' => 'activity');

		print_header("$COURSE->shortname: Virtual Machines", $COURSE->fullname,
                     build_navigation($navlinks),
                    "", "", true, '', user_login_string($SITE));
                    
        $cloud_select = '<select id="cloud" name="cloud">';
        foreach($this->cloud_data as $cloud)
        {
        	$cloud_select .= '<option value="' . $cloud->id . '">' . $cloud->name . '</option>';
        }
        $cloud_select .= "</select>";
        
        $form = str_replace('%%CLOUD_SELECT%%', $cloud_select, $this->form);
        $form = str_replace('%%EXTRA_FORM%%', $this->extra_form, $form);
        
        $errors = "";
        if(count($this->errors))
        {
        	$errors .= "<ul>";
        	foreach($this->errors as $error)
        	{
        		$errors .= "<li style='color: red'>$error</li>";
        	}
        	$errors .= "</ul>";
		}
		$form = str_replace('%%ERRORS%%', $errors, $form);
                  
		echo $form;
		print_footer($COURSE);
	}
	
	/**
	 * Allows vMoodle or a cloud strategy to modify the form
	 * to its needs.
	 */
	public function setForm($form) {
		
	}
}

	
