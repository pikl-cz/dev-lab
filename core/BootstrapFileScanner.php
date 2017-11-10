<?php

namespace Assist;

class BootstrapFileScanner
{
	private $ignore = ['.', '..', 'script', 'style', 'templates', 'BootstrapFileScanner.php', 'FileFolderTree.php', 'function.php', 'System.php', 'runner.php'];	
	
	private $choice;
	
	public function ignore($list = array())
	{
		if (!empty($list))
		{
			foreach($list as $item)
			{			
				$this->ignore[] = $item;				
			}
		}
	}
	
	public function just($list = array())
	{
		$this->choice = $list;
	}
	
	public function add($className)
	{
		$this->choice[] = $className;
	}
	
	private function iterate($list = array())
	{
		foreach ($list as $className)
		{
			//Contain namespaces?
			if (strpos($className, '\\') !== false) {
				$classPieces = explode('\\', $className);			
				$name = end($classPieces);
				$className = $name;
			}
					
			$file = __DIR__ . '/' . $className;
			
			//Contains postfix .php?
			$pos = strpos($className, '.php');
			if ($pos === false) {
				$file .= '.php';
			}					
			
			require_once $file;
		}
	}	
	
	public function run()
	{
		/*
		* TODO: automatically scan also subfolders
		*/
	   require_once __DIR__ . '/FileFolderTree.php';
	   
	   $list = new \Assist\FileFolderTree(__DIR__, true);
	   $list->setIgnoreList($this->ignore);


	   if (empty($this->choice))
	   {
			if (!empty($list->buildTree())) {
			   $this->iterate($list->buildTree());				   
			}	   	      		   
	   } else {
			$this->iterate($this->choice);				   	   
	   }
	}
	
}