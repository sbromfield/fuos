<?php
require_once 'PHPUnit.php';

/**
 * START STUB
 */

class startVM_cmd { function execute() { return "startVM"; } }
class stopVM_cmd { function execute() { return "stopVM"; } }
class viewVM_cmd { function execute() { return "viewVM"; } }
class listVM_cmd { function execute() { return "listVM"; } }
class createVM_cmd { function execute() { return "createVM"; } }
class deleteVM_cmd { function execute() { return "deleteVM"; } }
class setVMAsTemplate_cmd { function execute() { return "setVMAsTemplate"; } }
class createVMAssignment_cmd { function execute() { return "createVMAssignment"; } }
class installationDone_cmd { function execute() { return "installationDone"; } }

/**
 * END STUB
 */

class Test_vMoodle_Controller extends PHPUnit_TestCase
{
	function vMoodle_Controller_Test($name)
	{
		$this->PHPUnit_TestCase($name);
	}

	public function test_vMoodle_Controller_Instance()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$this->assertNotNull($vMoodle_Controller);
		$this->assertEquals(get_class($vMoodle_Controller), "vMoodle_Controller");
	}
	
	public function test_vMoodle_Controller_Dispatch_Invalid()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$ret = $vMoodle_Controller::dispatch();
		$this->assertFalse($ret);
	}
	
	public function test_vMoodle_Controller_Dispatch_startVM()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "startVM";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEquals("startVM", $ret);
	}
	
	public function test_vMoodle_Controller_Dispatch_stopVM()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "stopVM";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEquals("stopVM", $ret);
	}
	
	public function test_vMoodle_Controller_Dispatch_viewVM()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "viewVM";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEquals("viewVM", $ret);
	}
	
	public function test_vMoodle_Controller_Dispatch_listVM()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "listVM";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEquals("listVM", $ret);
	}
	
	public function test_vMoodle_Controller_Dispatch_createVM()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "createVM";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEquals("createVM", $ret);
	}
	
	public function test_vMoodle_Controller_Dispatch_deleteVM()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "deleteVM";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEquals("deleteVM", $ret);
	}
	
	public function test_vMoodle_Controller_Dispatch_setVMAsTemplate()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "setVMAsTemplate";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEquals("setVMAsTemplate", $ret);
	}
	
	public function test_vMoodle_Controller_Dispatch_createVMAssignment()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "createVMAssignment";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEquals("createVMAssignment", $ret);
	}
	public function test_vMoodle_Controller_Dispatch_installationDone()
	{
		$vMoodle_Controller = vMoodle_Controller::getInstance();
		$_GET['a'] = "installationDone";
		$ret = $vMoodle_Controller::dispatch();
		$this->assertEquals("installationDone", $ret);
	}
}

