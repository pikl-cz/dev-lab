<?php

namespace FlexiAuth;

use Nette\Security as NS;

class Authenticator implements \Nette\Security\IAuthenticator
{	
	
	/*
	 * Where user can go
	 */
	const MODULE = null;
	
	/**
     * @param string[] $credentials
     */
	public function authenticate(array $credentials) 
	{
		//return new \FlexiAuth\Identity(self::MODULE, 0);
	}
	
}
