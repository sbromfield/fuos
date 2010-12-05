<?php

require_once("Stubs/dsFacade_Stub.php");
require_once("Stubs/cloudFacade_Stub.php");
require_once("Stubs/Net_SSH2_Stub.php");

/**
 * Cloud_Manager Unit Test
 */
class Test_Cloud_Manager extends UnitTestCase
{

	/**
	 * Constructor
	 */
	public function Test_Cloud_Manager()
	{
		$this->UnitTestCase = "Test Cloud Manager";
	}

	/**
	 * Test Cloud_Manager singleton implementation.
	 * Instance should not be null and its class should be of
	 * type `Cloud_Manager'.
	 */
	public function test_Cloud_Manager_Instance()
	{	
		$Cloud_Manager = Cloud_Manager::getInstance();
		$this->assertNotNull($Cloud_Manager);
		$this->assertEqual(get_class($Cloud_Manager), "Cloud_Manager");
	}
	
	/**
	 * Test Cloud_Manager start operation.
	 * Start virtual machine number 1 should return true
	 * indicating success.
	 */
	public function test_start()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->start(1);
		$this->assertTrue($ret);
	}
	
        /**
         * Test Cloud_Manager stop operation.
	 * Stop virtual machine number 1 should return true
	 * indicating success.
         */
	public function test_stop()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->stop(1);
		$this->assertTrue($ret);
	}

	/**
         * Test Cloud_Manager view operation.
         * View virtual machine number 1 should return an object
	 * which is of type `viewVM_page'.
         */	
	public function test_view()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->view(1);
		$this->assertNotNull($ret);
		$this->assertEqual(get_class($ret), "viewVM_page");
	}

	/**
         * Test Cloud_Manager getState operation.
         * Get state of virtual machine number 1 should return a
         * string indicating it is running.
         */	
	public function test_getState()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->getState(1);
		$this->assertEqual($ret, "RUNNING");
	}
	
	/**
         * Test Cloud_Manager create operation with no arguments.
         * Create virtual machine of type VBOX should return an
	 * array and the first element within the array (the errors
	 * array) should contain elements. The second element on
	 * the array should be 0 or false.
         */
	public function test_create_empty()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->create("VBOX");
		$this->assertTrue(is_array($ret));
		$this->assertTrue(count($ret[0]));
		$this->assertFalse($ret[1]);
	}
	
	/**
         * Test Cloud_Manager create operation with arguments.
         * Create virtual machine of type VBOX should return an
	 * array and the first element within the array (the errors
	 * array) should contain no elements. The second element on
	 * the array should be true.
         */
	public function test_create_filled()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		
		$_POST['vmName'] = "TESTING MACHINE";
		$_POST['cloud'] = "1";
		$_POST['vmNumProcessors'] = "1";
		$_POST['vmRam'] = "256";
		$_POST['vmDiskSize'] = "4096";
		$_POST['iso'] = "Ubuntu";
		
		$ret = $Cloud_Manager->create("VBOX");
		
		$this->assertTrue(is_array($ret));
		$this->assertFalse(count($ret[0]));
		$this->assertTrue($ret[1]);
	}
	
	/**
	 * Test Cloud_Manager create child VM operation.
	 * Create child VM for student should return true
	 * indicating success.
	 */
	public function test_createChildVM()
	{
		$user = new stdclass;
		$user->username = "student";
		$user->id = 1;
		
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->createChildVM(1,$user,1);
		$this->assertTrue($ret);
	}
	
	/**
         * Test Cloud_Manager delete operation.
	 * Delete virtual machine number 1 should return true
	 * indicating success.
         */
	public function test_delete()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->delete(1);
		$this->assertTrue($ret);
	}
	
	/**
         * Test Cloud_Manager set as template operation.
	 * Set as template virtual machine number 1 should return
	 * true indicating success.
         */
	public function test_setAsTemplate()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->setAsTemplate(1);
		$this->assertTrue($ret);
	}
	
	/**
         * Test Cloud_Manager create VM form operation.
         * Create VM Form should return an object which
	 * is of type `viewVM_page'.
         */
	public function test_createVMForm()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->createVMForm("VBOX");
		$this->assertNotNull($ret);
		$this->assertEqual(get_class($ret), "createVM_page");
	}
	
	/**
         * Test Cloud_Manager installation done operation.
	 * Installation done virtual machine number 1 should
	 * return true indicating success.
         */
	public function test_installationDone()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->installationDone(1);
		$this->assertTrue($ret);
	}
	
}
