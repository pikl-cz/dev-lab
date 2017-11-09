<?php

namespace FlexiAuth;

class Assistent {

	/*
	 * Make a strong password
	 */
	public static function calculateHash($password, $salt) 
	{
		return sha1(md5($password.$salt));
	}

}
