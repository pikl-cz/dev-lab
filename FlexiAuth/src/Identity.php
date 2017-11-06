<?php

namespace FlexiAuth;

class Identity extends \Nette\Security\Identity {

	/** @var string */
	private $module;

	/** @var array */
	private $data;
	
	/**
	 * @param  string  module
	 * @param  mixed   identity ID
	 * @param  mixed   roles
	 * @param  array   user data
	 */
	public function __construct($module, $id, $roles = NULL, $data = NULL) 
	{
		$this->setId($id);
		$this->setRoles((array) $roles);
		$this->setModule($module);
		$this->setData((array) $data);
	}
	
	/**
	 * @param  string
	 * @return static
	 */
	public function setModule($module)
	{
		$this->module = $module;
		return $this;
	}

	/**
	 * Name of location where user can be logged
	 * @return string
	 */
	public function getModule()
	{
		return $this->module;
	}

	/**
	 * @param  array
	 */
	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}
	
}
