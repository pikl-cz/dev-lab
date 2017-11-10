<?php

namespace Assist;

require_once 'function.php';

class System
{
	//Specify array what part of core must load
	private $load;
	
	public function __construct($load = array()) {
		$this->load = $load;
	}
	
	public function run()
	{
		$queue = [
			'buffer', 
			'files',
			'autoload',
		];

		foreach ($queue as $function)
		{
			if (method_exists($this, $function)) {
				$this->{$function}();	
			}			
		}
	}
	
	/*
	 * Setup buffer to see page during loading
	 */
	private function buffer()
	{
		if (ob_get_contents())
		{
			ob_implicit_flush(true);
			ob_end_flush();
		}
	}
	
	/*
	 * 
	 */
	private function files($className = null)
	{
		require_once 'BootstrapFileScanner.php';
		$bootstrap = new \Assist\BootstrapFileScanner;
		$bootstrap->just($this->load);
		if (!empty($className)) {
			$bootstrap->add($className);
		}
		$bootstrap->run();
	}
	
	private function autoload()
	{
		/*
		 * Autoloading of classes
		 */
		spl_autoload_register(function ($name) {
			if (!empty($name)) {
				$this->files($name);
			}			
			//throw new \Exception("Unable to load $name.");
		});
	}			
}