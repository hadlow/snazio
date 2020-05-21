<?php

/*
|--------------------------------------------------------------------------
| File loader
|--------------------------------------------------------------------------
|
| We can load all the necessary files here ready for the bootstrap object
| to take care of. We only load the core files for bootstrapping to
| make the page load time as fast as possible.
|
| Last modified: 20/11/14
|
*/

/*
	Load all our vendors
*/
require_once(ROOT_DIR . 'app/vendor/autoload.php');

/*
	We need our functions
*/
require_once(ROOT_DIR . 'app/core/filesystem.class.php');
require_once(ROOT_DIR . 'app/core/functions.php');
require_once(ROOT_DIR . 'app/core/plugin.class.php');
require_once(ROOT_DIR . 'app/core/plugin_core.class.php');
require_once(ROOT_DIR . 'app/core/file.class.php');
require_once(ROOT_DIR . 'app/core/folder.class.php');

/*
	Load our bootstrap code
*/
require_once(ROOT_DIR . 'bootstrap/bootstrap.php');
