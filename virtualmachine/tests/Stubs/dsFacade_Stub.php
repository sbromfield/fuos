<?php

/**
 * dsFacade Stub
 */
class dsFacade {

	/** 
	 * Stub - return a predefined VM object.
	 */
	public function selectVM()
	{
		return unserialize('O:8:"stdClass":8:{s:2:"id";s:1:"1";s:4:"name";s:6:"Ubuntu";s:8:"owner_id";s:1:"4";s:10:"isTemplate";s:1:"0";s:9:"parent_id";N;s:5:"state";s:7:"RUNNING";s:8:"cloud_id";s:1:"1";s:17:"installation_done";s:1:"1";}');
	}
	
	/**
	 * Stub - return a predefined cloud object.
	 */
	public function selectCloud()
	{
		return unserialize('O:8:"stdClass":3:{s:2:"id";s:1:"1";s:4:"type";s:4:"VBOX";s:4:"name";s:22:"Mike Hunt\'s VirtualBox";}');
	}
	
	/**
	 * Stub - return true to indicate success.
	 */
	public function updateVM($vm)
	{
		return true;
	}
	
	/**
	 * Stub - return true to indicate success.
	 */
	public function insertVM()
	{
		return 1;
	}
	
	/**
	 * Stub - return true to indicate success.
	 */
	public function insertUVA()
	{
		return 1;
	}

	/**
         * Stub - return true to indicate success.
         */	
	public function deleteUVA()
	{
		return true;
	}
	
	/**
         * Stub - return true to indicate success.
         */
	public function deleteVM()
	{
		return true;
	}
	
	/**
         * Stub - return predefined array of Cloud objects.
         */
	public function selectClouds()
	{
		return unserialize('a:1:{i:1;O:8:"stdClass":3:{s:4:"type";s:4:"VBOX";s:4:"name";s:22:"Mike Hunt\'s VirtualBox";s:2:"id";i:1;}}');
	}
	
}
