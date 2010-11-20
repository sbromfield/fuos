<?php

require_once "Page.interface.php";

/**
 * The view VM page contains the logic for rendering the web interface
 * for the remote virtual machine connection (vMoodleS02) use case.
 */
class viewVM_page implements Page
{
	/**
	 * Contains the contents of the page.
	 */
	public $content;
	
	/**
	 * Displays the contents of the page.
	 */
	public function display() {
		global $SITE, $COURSE;
		
		$navlinks = array();
		$navlinks[] = array('name' => "Virtual Machines", 'link' => '', 'type' => 'activity');


		$meta = "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js\"></script>";


		print_header("$COURSE->shortname: Virtual Machines", $COURSE->fullname,
                	     build_navigation($navlinks),
                	    "", $meta, true, '', user_login_string($SITE));
        
        print_simple_box_start('center', '1034px', '', 0, 'generalbox', 'vm_template');        	    
		echo $this->content;
		print_simple_box_end();
		print_footer($COURSE);
	}
}
