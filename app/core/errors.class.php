<?php

/*
	@class error
*/
class Error
{
	/*
		@validError
		@string
	*/
	private $validError = '';
	
	/*
		@vowels
		@array
	*/
	private $vowels = array();
	
	public function __construct()
	{
		$this->vowels = array(
			'a','e','i','o','u','A','E','I','O','U'
		);
	}
	
	/*
		@validation
		@input array
		@ouput string
	*/
	public function validation($error = array())
	{
		// ini variables
		$description = null;
		$display = null;
		
		// loop through errors
		foreach($error as $values)
		{
			// loop through types of errors
			foreach($values as $name => $check)
			{
				$fletter = substr($name,0,1);
				$w = (in_array($fletter,$this->vowels) ? 'an' : 'a');
				
				// switch through errors
				switch($check)
				{
					// if required
					case 'required':
						$description = 'Please enter ' . $w . ' ' . $name;
						break;
					
					// if not email
					case 'email':
						$description = $name . ' needs to be valid.';
						break;
					
					// if exists
					case 'exists':
						$description = $name . ' is already taken, please try another.';
						break;
					
					// if not values
					case 'values':
						$description = $name . ' must be values specified.';
						break;
					
					// if not number
					case 'number':
						$description = $name . ' needs to be a number';
						break;
				}
				
				$display .= '<div class="alert alert-danger">' . $description . '</div>';
				
				$this->validError = $display;
			}
		}
	}
	
	/*
		@getValidError
		@input none
		@output string
	*/
	public function getValidError()
	{
		return $this->validError;
	}
}

?>