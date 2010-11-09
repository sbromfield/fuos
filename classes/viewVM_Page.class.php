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
		echo $this->content;
	}
}
