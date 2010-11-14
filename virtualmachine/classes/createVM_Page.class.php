<?php

require_once "Page.interface.php";

/**
 * The create VM page contains the logic for rendering the web
 * interface for the create virtual machine (vMoodleI01) use case.
 */
class createVM_page implements Page
{
	public $content;
	
	/**
	 * Contains the form that will be embedded into the page.
	 */
	public $form = '';
	
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
		          
		echo $this->content;
		
		print_footer($COURSE);
	}
	
	/**
	 * Allows vMoodle or a cloud strategy to modify the form
	 * to its needs.
	 */
	public function setForm($form) {
		
	}
}

	
