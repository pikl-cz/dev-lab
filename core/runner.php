<?php

namespace Assist;

class Runner
{
	//Specify array what part of core must load
	private $load;
	
	public function __construct($load = array()) {
		$this->load = $load;
	}
	
	public function just($load = array())
	{
		$this->load = $load;
		$this->run();
	}
	
	public function run()
	{
		try {
			/*
			 * Setup bootstrap
			 */
			require_once __DIR__ . '/System.php';
			$system = new \Assist\System($this->load);
			$system->run();

		} catch (Exception $e) {
			echo $e->getMessage(), "\n";
		}		
	}
	
}

$run = new \Assist\Runner();
//dump(get_declared_classes());die;