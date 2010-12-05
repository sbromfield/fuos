<?php

require_once "Stubs/commands_Stub.php";

/**
 * vMoodle_Controller Unit Test
 */
class Test_vMoodle_Controller extends UnitTestCase {

	/**
	 * Constructor
	 */
	function vMoodle_Controller_Test()
	{
		$this->UnitTestCase("vMoodle Controller Test");
	}
	
	/**
	 * Test vMoodle_Controller singleton implementation.
	 * Instance should not be null and its class should be of
	 * type `vMoodle_Controller'.
	 */
	public function test_vMoodle_Controller_Instance()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$this->assertNotNull($vMoodle_Controller);
		$this->assertEqual(get_class($vMoodle_Controller), "vMoodle_Controller");
	}
	
	/**
	 * Test vMoodle_Controller dispatch operation by attempting
	 * to dispatch to an invalid command.
	 * It should return false.
	 */
	public function test_vMoodle_Controller_Dispatch_Invalid()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$ret = $vMoodle_Controller::dispatch();
		$this->assertFalse($ret);
	}
	
	/**
	 * Test vMoodle_Controller dispatch operation by attempting
	 * to dispatch to startVM command.
	 * It should return "startVM", indicating correct dispatching.
	 */
	public function test_vMoodle_Controller_Dispatch_startVM()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "startVM";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEqual("startVM", $ret);
	}
	
	/**
	 * Test vMoodle_Controller dispatch operation by attempting
	 * to dispatch to stopVM command.
	 * It should return "stopVM", indicating correct dispatching.
	 */
	public function test_vMoodle_Controller_Dispatch_stopVM()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "stopVM";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEqual("stopVM", $ret);
	}
	
	/**
	 * Test vMoodle_Controller dispatch operation by attempting
	 * to dispatch to viewVM command.
	 * It should return "viewVM", indicating correct dispatching.
	 */
	public function test_vMoodle_Controller_Dispatch_viewVM()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "viewVM";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEqual("viewVM", $ret);
	}
	
	/**
	 * Test vMoodle_Controller dispatch operation by attempting
	 * to dispatch to listVM command.
	 * It should return "listVM", indicating correct dispatching.
	 */
	public function test_vMoodle_Controller_Dispatch_listVM()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "listVM";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEqual("listVM", $ret);
	}
	
	/**
	 * Test vMoodle_Controller dispatch operation by attempting
	 * to dispatch to createVM command.
	 * It should return "createVM", indicating correct dispatching.
	 */
	public function test_vMoodle_Controller_Dispatch_createVM()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "createVM";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEqual("createVM", $ret);
	}
	
	/**
	 * Test vMoodle_Controller dispatch operation by attempting
	 * to dispatch to deleteVM command.
	 * It should return "deleteVM", indicating correct dispatching.
	 */
	public function test_vMoodle_Controller_Dispatch_deleteVM()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "deleteVM";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEqual("deleteVM", $ret);
	}
	
	/**
	 * Test vMoodle_Controller dispatch operation by attempting
	 * to dispatch to setVMAsTemplate command.
	 * It should return "setVMAsTemplate", indicating correct 
	 * dispatching.
	 */
	public function test_vMoodle_Controller_Dispatch_setVMAsTemplate()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "setVMAsTemplate";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEqual("setVMAsTemplate", $ret);
	}
	
	/**
	 * Test vMoodle_Controller dispatch operation by attempting
	 * to dispatch to createVMAssignment command.
	 * It should return "createVMAssignment", indicating correct 
	 * dispatching.
	 */
	public function test_vMoodle_Controller_Dispatch_createVMAssignment()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "createVMAssignment";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEqual("createVMAssignment", $ret);
	}
	
	/**
	 * Test vMoodle_Controller dispatch operation by attempting
	 * to dispatch to installationDone command.
	 * It should return "installationDone", indicating correct 
	 * dispatching.
	 */
	public function test_vMoodle_Controller_Dispatch_installationDone()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "installationDone";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEqual("installationDone", $ret);
	}
}

?>

