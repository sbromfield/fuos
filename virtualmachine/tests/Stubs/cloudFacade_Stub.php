<?php
/**
 * cloudFacade stub
 */
class cloudFacade {

	/**
         * Stub - return predifined VMParams object.
         */
	public function selectVMParams()
	{
		return unserialize('O:8:"stdClass":8:{s:2:"id";s:2:"71";s:10:"machine_id";s:1:"1";s:8:"rdp_port";s:4:"3392";s:13:"numProcessors";s:1:"1";s:3:"ram";s:3:"512";s:9:"video_ram";s:1:"0";s:4:"pass";s:0:"";s:9:"disk_size";s:5:"10000";}');
	}

        /**
         * Stub - return predifined array of Machine objects.
         */	
	public function selectMachines()
	{
		return unserialize('a:1:{i:1;O:8:"stdClass":10:{s:8:"cloud_id";s:1:"1";s:4:"host";s:9:"127.0.0.1";s:4:"port";s:2:"22";s:6:"ws_url";s:0:"";s:4:"user";s:8:"teamfuos";s:4:"pass";s:14:"winteriscoming";s:3:"cpu";s:2:"90";s:9:"avail_ram";s:4:"1024";s:7:"hd_free";s:7:"1000000";s:2:"id";i:1;}}');
	}
	
        /**
         * Stub - return true to indicate success.
	 */
	public function updateVMParams()
	{	
		return true;
	}
	
        /**
         * Stub - return predifined Machine object.
         */
	public function selectMachine()
	{
		return unserialize('O:8:"stdClass":10:{s:2:"id";s:1:"1";s:8:"cloud_id";s:1:"1";s:4:"host";s:9:"127.0.0.1";s:4:"port";s:2:"22";s:6:"ws_url";s:0:"";s:4:"user";s:8:"teamfuos";s:4:"pass";s:14:"winteriscoming";s:3:"cpu";s:2:"90";s:9:"avail_ram";s:4:"1024";s:7:"hd_free";s:7:"1000000";}');
	}
	
        /**
         * Stub - return true to indicate success.
         */
	public function insertVMParams()
	{
		return 1;
	}
	
        /**
         * Stub - return true to indicate success.
         */
	public function deleteVMParams()
	{
		return true;
	}
}
