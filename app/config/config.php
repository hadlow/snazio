<?php

// display all errors
error_reporting(E_ALL);
ini_set("display_errors", 1);

// set user session
session_start();

// get admin url
$url = $_SERVER['REQUEST_URI'];
$url = explode('/', $url);

// get rid of blank value
unset($url[0]);
$durl = array();

// loop through url
foreach($url as $path)
{
	if($path == 'admin')
	{
		break;
	} else {
		$durl[] = $path;
	}
}

// create new admin url
$url = implode('/', $durl);

global $root;
global $data;

if($url != "")
{
	$url = $url . '/';
}

// define it
define('ADMIN_URL', "http://$_SERVER[HTTP_HOST]/" . $url . "admin/");
define('ROOT_URL', "http://$_SERVER[HTTP_HOST]/" . $url . "");
define('ROOT_DIR', $root);

/*
	The content directory. Where all our content and page files are stored
*/
define('CONTENT_DIR', ROOT_DIR . '_/content/');

/*
	The content extension. Specify the extension of our page files
*/
define('CONTENT_EXT', '.md');