<?php

// display all errors
//error_reporting(E_ALL);
ini_set("display_errors", 1);

/*
	Define our main app path. Used everywhere
*/
define('ROOT_DIR', realpath(dirname(__FILE__)) . '/');
//define('CUR_URL', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
define('CUR_URL', "http://snazio.test");

/*
	Loader
*/
require_once('bootstrap/loader.php');

/*
	Create the instance of our app/website
*/
$app = new App();
