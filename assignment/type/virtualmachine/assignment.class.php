<?php // $Id: assignment.class.php,v 1.12.2.3 2009/11/20 08:25:32 skodak Exp $

/**
 * Virtual Machine Assignment Type
 *
 * The Virtual Machine Assignment Type is an extension to the
 * assignment module to allow instructors to assign a VM template
 * to an assignment.
 * @author Marcos A. Di Pietro
 * @date 10/05/2010
 */
class assignment_virtualmachine extends assignment_base {

	/**
	 * Constructor.
	 *
	 * It sets up the the assignment type
	 * @author Marcos A. Di Pietro
	 * @date 10/05/2010
	 */
	function assignment_virtualmachine($cmid='staticonly', $assignment=NULL, $cm=NULL, $course=NULL) {
		parent::assignment_base($cmid, $assignment, $cm, $course);
		$this->type = 'virtualmachine';
	}
    
	/**
	 * View (hook).
	 *
	 * It is called whenever someone requests to see the assignment.
	 * It displays the header, description, due dates and virtual machine link.
	 * @author Marcos A. Di Pietro
	 * @date 10/05/2010
	 */
	function view()
	{
		global $USER;

		$context = get_context_instance(CONTEXT_MODULE,$this->cm->id);
		require_capability('mod/assignment:view', $context);

		add_to_log($this->course->id, "assignment", "view", "view.php?id={$this->cm->id}", $this->assignment->id, $this->cm->id);

		$this->view_header();

		$this->view_intro();
        
		$this->view_vm_template();

		$this->view_dates();

		$this->view_footer();
		
	}

	/**
	 * setup_elements hook.
	 * It adds the Virtual Machine Template field to the assignment creation
	 * and update forms.
	 * @author Marcos A. Di Pietro
	 * @date 10/05/2010
	 */
	function setup_elements($mform)
	{
//		$vm_templates = $this->get_vm_templates();		
		require_once('../mod/virtualmachine/classes/dsFacade.class.php');
		$ds = new dsFacade();
		$vms = $ds->selectVMs();
		$vm_templates = array();
		foreach($vms as $vm)
		{
			// Has to be template
			if($vm->isTemplate != 1) continue;
			$vm_templates[$vm->id] = $vm->name;
		}
		
		$mform->addElement('select', 'var1', get_string('form_assigntemplate', 'assignment_virtualmachine'), $vm_templates);
	}

	/**
	 * View Virtual Machine Template (helper).
	 * It displays the virtual machine assigned to the assignment.
	 * @author Marcos A. Di Pietro
	 * @date 10/05/2010
	 */
	private function view_vm_template()
	{
		global $COURSE, $CFG, $USER;
		print_simple_box_start('center', '', '', 0, 'generalbox', 'vm_template');

		require_once('../../mod/virtualmachine/classes/dsFacade.class.php');
		$ds = new dsFacade();
		$templates = $ds->selectVM($this->assignment->var1);

		echo "<center>";
		echo "<b>Virtual Machine</b>";
		echo "<br /><br />";
		require_once('../../mod/virtualmachine/classes/dsFacade.class.php');
		$ds = new dsFacade();

		$UVA = $ds->selectUVA($USER->id, $this->assignment->id);
		if(!$UVA)
		{
			echo "<a href='{$CFG->wwwroot}/mod/virtualmachine/?a=createVMAssignment&id={$COURSE->id}&assid={$this->assignment->id}'>Create VM</a>";
		}
		else
		{
			echo "<a href='{$CFG->wwwroot}/mod/virtualmachine/?a=listVM&id={$COURSE->id}&vm={$UVA->vm_id}'>Go</a>";
		}
		
		echo "</center>";
		print_simple_box_end();
	}
    
    
    
    
	function delete_instance($assignment) {
		$result = parent::delete_instance($assignment);
    	
		/** Delete vms for all students */
    	
		return $result;
	}
    
    
	/**
	 * Stub
	 */
	private function get_vm_templates()
	{
		return array( 	
			'0' => 'Debian base system',
			'1' => 'Debian base system with X11',
			'2' => 'Windows XP',
			'3' => 'Debian LAMP'
		);
	}
    
}

