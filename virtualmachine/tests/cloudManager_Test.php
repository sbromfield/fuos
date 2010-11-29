<?php

/**
 * START STUB
 */

class dsFacade {

	public function selectVM()
	{
		return unserialize('O:8:"stdClass":8:{s:2:"id";s:1:"1";s:4:"name";s:6:"Ubuntu";s:8:"owner_id";s:1:"4";s:10:"isTemplate";s:1:"0";s:9:"parent_id";N;s:5:"state";s:7:"RUNNING";s:8:"cloud_id";s:1:"1";s:17:"installation_done";s:1:"1";}');
	}
	
	public function selectCloud()
	{
		return unserialize('O:8:"stdClass":3:{s:2:"id";s:1:"1";s:4:"type";s:4:"VBOX";s:4:"name";s:22:"Mike Hunt\'s VirtualBox";}');
	}
	
	public function updateVM($vm)
	{
		return true;
	}
	
	public function insertVM()
	{
		return 1;
	}
	
	public function insertUVA()
	{
		return 1;
	}
	
	public function deleteUVA()
	{
		return true;
	}
	
	public function deleteVM()
	{
		return true;
	}
	
	public function selectClouds()
	{
		return unserialize('a:1:{i:1;O:8:"stdClass":3:{s:4:"type";s:4:"VBOX";s:4:"name";s:22:"Mike Hunt\'s VirtualBox";s:2:"id";i:1;}}');
	}
	
}


class cloudFacade {
	public function selectVMParams()
	{
		return unserialize('O:8:"stdClass":8:{s:2:"id";s:2:"71";s:10:"machine_id";s:1:"1";s:8:"rdp_port";s:4:"3392";s:13:"numProcessors";s:1:"1";s:3:"ram";s:3:"512";s:9:"video_ram";s:1:"0";s:4:"pass";s:0:"";s:9:"disk_size";s:5:"10000";}');
	}
	
	public function selectMachines()
	{
		return unserialize('a:1:{i:1;O:8:"stdClass":10:{s:8:"cloud_id";s:1:"1";s:4:"host";s:9:"127.0.0.1";s:4:"port";s:2:"22";s:6:"ws_url";s:0:"";s:4:"user";s:8:"teamfuos";s:4:"pass";s:14:"winteriscoming";s:3:"cpu";s:2:"90";s:9:"avail_ram";s:4:"1024";s:7:"hd_free";s:7:"1000000";s:2:"id";i:1;}}');
	}
	
	public function updateVMParams()
	{	
		return true;
	}
	
	public function selectMachine()
	{
		return unserialize('O:8:"stdClass":10:{s:2:"id";s:1:"1";s:8:"cloud_id";s:1:"1";s:4:"host";s:9:"127.0.0.1";s:4:"port";s:2:"22";s:6:"ws_url";s:0:"";s:4:"user";s:8:"teamfuos";s:4:"pass";s:14:"winteriscoming";s:3:"cpu";s:2:"90";s:9:"avail_ram";s:4:"1024";s:7:"hd_free";s:7:"1000000";}');
	}
	
	public function insertVMParams()
	{
		return 1;
	}
	
	public function deleteVMParams()
	{
		return true;
	}
}


class Net_SSH2 {

	public function login($username, $password)
	{
		return true;
	}

	public function exec($cmd)
	{
		return "Oracle VM VirtualBox Command Line Management Interface Version 3.2.10
(C) 2005-2010 Oracle Corporation
All rights reserved.

Name:            Ubuntu
Guest OS:        Other/Unknown
UUID:            c881b204-2ad2-4573-b3ec-b5aa0ea3d123
Config file:     /home/teamfuos/.VirtualBox/Machines/Ubuntu/Ubuntu.xml
Hardware UUID:   c881b204-2ad2-4573-b3ec-b5aa0ea3d123
Memory size:     512MB
Page Fusion:     off
VRAM size:       8MB
HPET:            off
Number of CPUs:  1
Synthetic Cpu:   off
CPUID overrides: None
Boot menu mode:  message and menu
Boot Device (1): Floppy
Boot Device (2): DVD
Boot Device (3): HardDisk
Boot Device (4): Not Assigned
ACPI:            on
IOAPIC:          off
PAE:             off
Time offset:     0 ms
RTC:             local time
Hardw. virt.ext: on
Hardw. virt.ext exclusive: on
Nested Paging:   on
Large Pages:     off
VT-x VPID:       on
State:           running (since 2010-11-28T16:48:27.707000000)
Monitor count:   1
3D Acceleration: off
2D Video Acceleration: off
Teleporter Enabled: off
Teleporter Port: 0
Teleporter Address:
Teleporter Password:
Storage Controller Name (0):            SATA CONTROLLER
Storage Controller Type (0):            IntelAhci
Storage Controller Instance Number (0): 0
Storage Controller Max Port Count (0):  30
Storage Controller Port Count (0):      30
Storage Controller Name (1):            IDE CONTROLLER
Storage Controller Type (1):            PIIX4
Storage Controller Instance Number (1): 0
Storage Controller Max Port Count (1):  2
Storage Controller Port Count (1):      2
SATA CONTROLLER (0, 0): /home/teamfuos/.VirtualBox/Machines/Ubuntu/Snapshots/{2cff8506-dc4c-4ed4-bf55-2a4efef8b687}.vdi (UUID: 2cff8506-dc4c-4ed4-bf55-2a4efef8b687)
IDE CONTROLLER (0, 0): Empty
NIC 1:           disabled
NIC 2:           disabled
NIC 3:           disabled
NIC 4:           disabled
NIC 5:           disabled
NIC 6:           disabled
NIC 7:           disabled
NIC 8:           disabled
Pointing Device: USB Tablet
Keyboard Device: USB Keyboard
UART 1:          disabled
UART 2:          disabled
Audio:           disabled
Clipboard Mode:  Bidirectional
Video mode:      640x480x32
VRDP:            enabled (Address 0.0.0.0, Ports 3390-3450, MultiConn: on, ReuseSingleConn: on, Authentication type: null)
VRDP port:       3392
Video redirection: disabled
USB:             enabled

USB Device Filters:

<none>

Available remote USB devices:

<none>

Currently Attached USB Devices:

<none>

Shared folders:  <none>

VRDP Connection:    not active
Clients so far:     0

Guest:

OS type:                             Other
Additions active:                    no
Configured memory balloon size:      0 MB

Snapshots:

   Name: Base (UUID: 8aa7b6f5-6fb2-4b8a-8a2a-8bc4afb914d7) *

";
	}
}



/**
 * END STUB
 */

class Test_Cloud_Manager extends UnitTestCase
{

	public function Test_Cloud_Manager()
	{
		$this->UnitTestCase = "Test Cloud Manager";
	}

	public function test_Cloud_Manager_Instance()
	{	
		$Cloud_Manager = Cloud_Manager::getInstance();
		$this->assertNotNull($Cloud_Manager);
		$this->assertEqual(get_class($Cloud_Manager), "Cloud_Manager");
	}
	
	public function test_start()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->start(1);
		$this->assertTrue($ret);
	}
	
	public function test_stop()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->stop(1);
		$this->assertTrue($ret);
	}
	
	public function test_view()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->view(1);
		$this->assertNotNull($ret);
		$this->assertEqual(get_class($ret), "viewVM_page");
	}
	
	public function test_getState()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->getState(1);
		$this->assertEqual($ret, "RUNNING");
	}
	
	public function test_create_empty()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->create("VBOX");
		$this->assertTrue(is_array($ret));
		$this->assertTrue(count($ret[0]));
		$this->assertFalse($ret[1]);
	}
	
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
	
	public function test_createChildVM()
	{
		$user = new stdclass;
		$user->username = "student";
		$user->id = 1;
		
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->createChildVM(1,$user,1);
		$this->assertTrue($ret);
	}
	
	public function test_delete()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->delete(1);
		$this->assertTrue($ret);
	}
	
	public function test_setAsTemplate()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->setAsTemplate(1);
		$this->assertTrue($ret);
	}
	
	public function test_createVMForm()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->createVMForm("VBOX");
		$this->assertNotNull($ret);
		$this->assertEqual(get_class($ret), "createVM_page");
	}
	
	public function test_installationDone()
	{
		$Cloud_Manager = Cloud_Manager::getInstance();
		$ret = $Cloud_Manager->installationDone(1);
		$this->assertTrue($ret);
	}
	
}
