<?php
/**
 * Net_SSH2 stub
 */
class Net_SSH2 {

        /**
         * Stub - return true to indicate success.
         */
	public function login($username, $password)
	{
		return true;
	}

        /**
         * Stub - simulate VBoxManage output.
         */
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
