<?php

namespace Assist;

class Password
{
	public function getHash($pass) 
	{
		return password_hash($pass, PASSWORD_DEFAULT);
	}
	
	public function verify($pass, $hash) 
	{
		if (password_verify($pass, $hash)) {
			echo 'Password is valid!';
		} else {
			echo 'Invalid password.';
		}
	}
}