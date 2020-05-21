<?php

/*
	@class security
*/
class Security
{
	/*
		@pass
		@bool
	*/
	private $passed = false;
	
	/*
		@errors
		@string
	*/
	private $errors = array();
	
	/*
		@submitted
		@input string
		@output bool
	*/
	public static function submitted($type = 'post')
	{
		// find type specified
		switch($type)
		{
			// if post type used
			case 'post':
				// return if empty or not
				return (!empty($_POST)) ? true : false;
				break;
			
			// if get type used
			case 'get':
				// return if empty or not
				return (!empty($_GET)) ? true : false;
				break;
			
			default:
				return false;
				break;
		}
	}
	
	/*
		@grab
		@input string
		@output string
	*/
	public static function grab($name)
	{
		// if post isset
		if(isset($_POST[$name]))
		{
			// return post
			return $_POST[$name];
		// if get isset
		} elseif(isset($_GET[$name])) {
			// return get
			return $_GET[$name];
		}
		
		// else return nothing
		return '';
	}
	
	/*
		@validate
		@input string,array
		@output bool
	*/
	public function validate($post, $values = array())
	{
		// loop through first set of values (name)
		foreach($values as $name => $options)
		{
			// ini field name
			$fieldName = $name;
			
			// loop through options
			foreach($options as $option => $string)
			{
				// get value of input name
				$value = $post[$name];
				
				// if option is a name value
				if($option == 'name')
				{
					// field name = string
					$fieldName = $string;
				}
				
				// if value is equal to nothing
				if($option === 'required' && empty($value))
				{
					$this->makeError(array($fieldName => $option));
				} else {
					if(!empty($value))
					{
						// find option
						switch($option)
						{
							// if email
							case 'email':
								// if value needs to be email
								if(!isemail($value))
								{
									$this->makeError(array($fieldName => $option));
								}
								
								break;
							
							// if values
							case 'values':
								if(!in_array($value,$string))
								{
									$this->makeError(array($fieldName => $option));
								}
								
								break;
							
							// if number
							case 'number':
								// if value needs to be int
								if(!isint($value))
								{
									$this->makeError(array($fieldName => $option));
								}
								
								break;
						}
					}
				}
			}
		}
		
		// if no errors
		if(empty($this->error))
		{
			$this->passed = true;
		}
		
		return $this;
	}
	
	/*
		@makeError
		@input string
		@output none
	*/
	private function makeError($string = array())
	{
		$this->error[] = $string;
	}
	
	/*
		@error
		@input none
		@output string
	*/
	public function error()
	{
		return $this->error;
	}
	
	/*
		@passed
		@input none
		@output bool
	*/
	public function passed()
	{
		return $this->passed;
	}
	
	/*
		@generateToken
		@input none
		@output string
	*/
	public function generateToken()
	{
		// return a random string of characters and store in session
		return $this->session('token', md5(uniqid()));
	}
	
	/*
		@checkToken
		@input string
		@output bool
	*/
	public function checkToken($token)
	{
		// get token name
		$name = 'token';
		
		// if token created is same as form token
		if($this->sessionExists($name) && $token === $this->getSession($name))
		{
			// delete token session
			$this->sessionDelete($name);
			
			return true;
		} else {
			return false;
		}
	}
	
	/*
		@sessionDelete
		@input string
		@output string
	*/
	public function sessionDelete($name)
	{
		// if session exists
		if($this->sessionExists($name))
		{
			// unset it
			unset($_SESSION[$name]);
		}
	}
	
	/*
		@getSession
		@input string
		@output string
	*/
	public function getSession($name)
	{
		return $_SESSION[$name];
	}
	
	/*
		@sessionExists
		@input string
		@output bool
	*/
	public function sessionExists($name)
	{
		// if session token exists
		if(isset($_SESSION[$name]))
		{
			return true;
		} else {
			return false;
		}
	}
	
	/*
		@session
		@input string,string
		@output string
	*/
	public function session($name,$value)
	{
		// return and create session variable
		return $_SESSION[$name] = $value;
	}
	
	/*
		@makeHash
		@input string,string
		@output string
	*/
	public function makeHash($string,$salt = '')
	{
		return hash('sha256',$string.$salt);
	}
	
	/*
		@salt
		@input int
		@output string
	*/
	public function salt()
	{
		return uniqid(mt_rand(), true);;
	}
	
	/*
		@unique
		@input none
		@output string
	*/
	public function unique()
	{
		return $this->makeHash(uniqid());
	}
	
	/*
		@splitPass
		@input string
		@output array
	*/
	public function splitPass($password)
	{
		// get first 64 characters from string (password)
		$pass = substr($password, 0, 64);
		
		// get last 32 characters from string (salt)
		$salt = substr($password, 65, 98);
		
		// return salt and password back
		return array('password' => $pass,'salt' => $salt);
	}
	
	/*
		@getIP
		@input none
		@output string
	*/
	public function getIP()
	{
		// get client ip
		return $clientIP = $_SERVER['REMOTE_ADDR'];
	}
	
	/*
		@set
		@input string
		@output bool
	*/
	public function set($string = null)
	{
		if(isset($_GET[$string]) && $_GET[$string] != '')
		{
			return true;
		} else {
			return false;
		}
	}
}
