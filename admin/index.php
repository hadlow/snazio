<?php

$root = realpath(dirname(__FILE__)) . '/';
echo $root;

if(strpos($root, '\admin') !== false)
{
    $root = str_replace("\admin", "", $root);
} elseif(strpos($root, '/admin') !== false) {
    $root = str_replace("/admin", "", $root);
}

// load app
require_once '../app/config/loader.php';

// start the app
$admin = new Admin_Run();