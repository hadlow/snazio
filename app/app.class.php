<?php

class Admin_Run
{
	/*
		@controller
		@object
	*/
	private $controller = null;
	
	/*
		@method
		@object
	*/
	private $method = null;
	
	/*
		@ps
		@array
	*/
	private $ps = array();
	
	/*
		@__construct
		@input none
		@output none
	*/
	public function __construct()
	{
		// get URL data
		$this->getUrl();

		if($this->controller == "error")
		{
			$this->controller = "err";
		}
		
		// check if controller exists
		if(file_exists('../app/controllers/' . $this->controller . '.class.php'))
		{
			// get controller
			require_once '../app/controllers/' . $this->controller . '.class.php';
			
			// set controller
			$this->controller = new $this->controller();
			
			// see if method exists
			if(method_exists($this->controller, $this->method))
			{
				// insert parameters
				if(isset($this->ps[0]))
				{
					// run function
					call_user_func_array([$this->controller, $this->method], $this->ps);
				} else {
					// if no parameters
					$this->controller->{$this->method}();
				}
			} else {
				// set default if not exists
				if(method_exists($this->controller, 'index'))
				{
					$this->controller->index();
				}
			}
		} else {
			// if controller doesn't exist, fall back on default home
			require_once '../app/controllers/home.class.php';
			$home = new Home();
			$home->index();
		}
	}
	
	/*
		@getUrl
		@input none
		@output none
	*/
	private function getUrl()
	{
		// if URL set
		if(isset($_GET['url']))
		{
			// get URL
			$url = rtrim($_GET['url'], '/');
			$url = filter_var($url, FILTER_SANITIZE_URL);
			$url = explode('/', $url);
			
			// set controller
			if(isset($url[0]))
			{
				$this->controller = $url[0];
			} else {
				$this->controller = 'home';
			}
			
			// set method
			if(isset($url[1]))
			{
				$this->method = $url[1];
			} else {
				$this->method = 'index';
			}
			
			// if any parameters set
			if(isset($url[2]))
			{
				// loop through url array
				foreach($url as $n => $name)
				{
					switch($n)
					{
						case 0:
							// leave blank
							break;
						
						case 1:
							// leave blank
							break;
						
						default:
							// set parameters
							$this->ps[] = $url[$n];
							break;
					}
				}
			}
		}
	}
}
