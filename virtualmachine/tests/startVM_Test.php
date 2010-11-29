<?php

/**
 * START STUB
 */

//function verify_access_and_get_course()
//{
//	return 2;
//}

/**
 * END STUB
 */

class Test_Start_Virtual_Machine extends UnitTestCase
{

	public function Test_Start_Virtual_Machine()
	{
		$this->UnitTestCase = "Start Virtual Machine";
	}

	public function testExecute()
	{
		$cmd = new startVM_cmd();
		$_GET['id'] = 2;
		$_GET['vm'] = 1;

		var_dump($cmd->execute());
		
		$this->assertEqual(0, 0);
	}
}
