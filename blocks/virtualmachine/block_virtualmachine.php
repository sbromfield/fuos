<?php
/**
 * Virtual Machine Block
 *
 * Block that is displayed only to the teachers of a course.
 * The block shall provide easy access to the teachers' VMs
 * and to the Student VM's.
 *
 * @author Marcos A. Di Pietro
 * @date 10/07/2010
 */
class block_virtualmachine extends block_base {

	/**
	 * Init (hook)
	 *
	 * Needs to set up the Block template and version.
	 * @author Marcos A. Di Pietro
	 * @date 10/07/2010
	 */
	function init() {
		$this->title   = 'Virtual Machines';//get_string('simplehtml', 'block_simplehtml');
		$this->version = 2007101509;
	}
	
	/**
	 * Get Content
	 *
	 * Sets up the content of the block. If the block has no content,
	 * then the block is not displayed. So, since this block is only
	 * to be displayed to instructors, the content of the block shall
	 * remain empty unless the one requesting to get the content is a
	 * teacher.
	 * @author Marcos A. Di Pietro
	 * @date 10/07/2010
	 */
	function get_content() {
		global $COURSE, $USER, $CFG;

		// If content has already been set return it	
		if ($this->content !== NULL) {
			return $this->content;
		}
    
		// Create empty content
		$this->content         =  new stdClass;
		$this->content->text   = '';
		$this->content->footer = '';
      
		// If instructor, then set the content for the block.
		// Let empty otherwise since it is desirable to hide
		// the block from students
		$context = get_context_instance(CONTEXT_COURSE,$COURSE->id); 
		if(has_capability('moodle/legacy:editingteacher', $context, $USER->id, false))
		{
			$this->content->text   = "<a href=\"{$CFG->wwwroot}/mod/virtualmachine/index.php?a=listVM&id={$COURSE->id}\">My Vitual Machines</a><br />";
			$this->content->text   .= "<a href=\"\">Student Virtual Machines</a><br />";
		}

		return $this->content;
	}
  
}
