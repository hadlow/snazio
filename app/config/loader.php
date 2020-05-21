<?php

// get config
require_once '../app/config/config.php';

// vendors
require_once(ROOT_DIR . 'app/vendor/autoload.php');

// load core classes
require_once '../app/core/filesystem.class.php';
//require_once '../app/core/errors.class.php';
require_once '../app/core/security.class.php';
require_once '../app/core/plugin_core.class.php';
require_once '../app/core/plugin.class.php';
require_once '../app/core/controller.class.php';
require_once '../app/core/file.class.php';
require_once '../app/core/folder.class.php';
require_once '../app/core/functions.php';
require_once '../app/app.class.php';

// if user not logged in
if(!isset($_SESSION['user']))
{
	header("Location: " . ADMIN_URL . "login");
} else {
	if(!file_exists(ROOT_DIR . 'app/config/users/' . $_SESSION['user'] . '.json'))
	{
		header("Location: " . ADMIN_URL . "logout");
	} else {
		$user = json_decode(file_get_contents(FileSystem::path(array('app', 'config', 'users', $_SESSION['user'] . '.json'))), true);
	}
}

$config = get_config();

$xml = FileSystem::path(array('_', 'themes', $config['theme'], 'theme.xml'));
$theme = simplexml_load_file($xml);

$data = array();

$plugin = new Plugin;
$plugin->run_hooks('admin_init');
