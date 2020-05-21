<?php

/*
|--------------------------------------------------------------------------
| Application bootstrap loader
|--------------------------------------------------------------------------
|
| This file performs all the loading required to run the website. The
| boostrap loads all the plugins and gets all the relevant information
| about the website. It then uses that information to compile a page
| to display to the user. It uses the Twig templating engine in order
| to render webpages.
|
| Last modified: 16/12/14
|
*/

use \Michelf\MarkdownExtra;

class App
{
	/*
		Stores all plugin objects

		@plugin
		@var object
	*/
	private $plugin;

	/*
		Stores all data about the theme

		@theme
		@var array
	*/
	private $theme = array();

	/*
		All configuration settings

		@settings
		@var array
	*/
	private $settings = array();

	/*
		Stores the basic root URL

		@root_url
		@var string
	*/
	private $root_url = '';

	/*
		All the vars used on our website

		@twig_vars
		@var array
	*/
	private $twig_vars = array();

	/*
		Get the meta to use in various functions

		@meta
		@var array
	*/
	private $meta = array();

	/*
		The URL of the page the user is on

		@url
		@var string
	*/
	private $url = '';

	/*
		Constructor boots up website

		@__construct
		@param none
		@return void
	*/
	public function __construct()
	{
		// load plugins
		$this->plugin = new Plugin();
		$this->plugin->run_hooks('plugins_loaded');

		// Load all variables we're gonna need
		$this->load_twig_vars();
		$this->set_url();
		$this->root_url = root();
		$this->settings = get_config();

		// Get theme data
		$this->theme = simplexml_load_file(ROOT_DIR . '_/themes/' . $this->settings['theme'] . '/theme.xml');

		// Gather all the data we will use for Twig
		$this->add_twig_data();

		// display output
		echo $this->render_page();
	}

	private function render_page()
	{
		$templatefile = (isset($this->meta['template']) && $this->meta['template']) ? $this->meta['template'] : 'page';

		Twig_Autoloader::register();
		print_r($this->settings);
		$loader = new Twig_Loader_Filesystem(ROOT_DIR . '_/themes/' . $this->settings['theme'] . '/');

		if(!$this->settings['dev_mode'])
		{
			$cache = '_/cache';
		} else {
			$cache = false;
		}

		$twig = new Twig_Environment($loader, array('debug' => false, 'autoescape' => false, 'cache' => $cache));
		$template = $twig->loadTemplate('templates/' . $templatefile . '.html');

		return $template->render($this->twig_vars);
	}

	private function add_custom_meta()
	{
		$author = array();
		$dateFormats = array();

		if(isset($this->meta['author']))
		{
			$author = get_user($this->meta['author']);
		} else {
			$author = array(
				'name' => 'Non existing author'
			);
		}

		if(isset($this->meta['date']))
		{
			$dateFormats = date_formats($this->meta['date']);
		} else {
			$dateFormats = array(
				'timeago' => '',
				'worded' => '',
				'numbered' => ''
			);
		}

		$this->plugin->run_hooks('author', array(&$author));
		$this->plugin->run_hooks('date', array(&$dateFormats));

		$this->twig_vars['author'] = $author;
		$this->twig_vars['date'] = $dateFormats;
	}

	private function add_posts()
	{
		$posts = array();

		if(!empty($this->settings['blog_page']))
		{
			$posts = $this->get_posts(ROOT_DIR . '_/content/' . $this->settings['blog_page']);
		}

		$this->plugin->run_hooks('posts', array(&$posts));

		$this->twig_vars['posts'] = $posts;
	}

	private function add_partials()
	{
		$global = array();

		$partials = $this->theme->widgets;

		foreach($partials[0] as $widget)
		{
			$filepath = ROOT_DIR . '_/widgets/' . $widget->name . '.md';

			if(file_exists($filepath))
			{
				$global[(string)$widget->name] = file_get_contents($filepath);
			}
		}

		$this->plugin->run_hooks('global', array(&$global));

		$this->twig_vars['global'] = $global;
	}

	private function add_fields()
	{
		// Get template fields
		$fields = array();
		$templates = $this->theme->templates->template;

		foreach($templates as $data)
		{
			if($data->name == $this->meta['template'])
			{
				if(isset($data->form))
				{
					$fielddata = $data->form;
				} else {
					$fielddata = '';
				}
			}
		}

		if($fielddata != '')
		{
			foreach($fielddata->input as $input => $data)
			{
				$filepath = ROOT_DIR . '_/content/' . $this->url . '/' . $data->name . '.md';

				if(file_exists($filepath))
				{
					$fields[(string)$data->name] = file_get_contents($filepath);
				}
			}
		}

		$this->plugin->run_hooks('fields', array(&$fields));

		$this->twig_vars['fields'] = $fields;
	}

	private function add_content()
	{
		$content = '';
		$folder = ROOT_DIR . '_/content/' . $this->url .'/';

		if(file_exists($folder . 'index.md'))
		{
			$content = file_get_contents($folder . 'index.md');
			$this->meta = read_file_meta($folder . 'page.json');
		} else {
			$content = file_get_contents(ROOT_DIR . '_/content/' . '404.md');
			$this->meta = read_file_meta(ROOT_DIR . '_/content/' . '/404.json');

			header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
		}

		$this->plugin->run_hooks('content', array(&$content));

		$content = parse_content($content);
		$this->twig_vars['content'] = $content;
	}

	private function add_meta()
	{
		$this->plugin->run_hooks('meta', array(&$this->meta));
		$this->twig_vars['meta'] = $this->meta;
	}

	private function add_twig_data()
	{
		// Add some data we already have
		$this->twig_vars['theme_url'] = $this->root_url . '_/' . basename(ROOT_DIR . '_/themes/') . '/' . $this->settings['theme'];
		$this->twig_vars['base_url'] = CUR_URL;

		$this->plugin->run_hooks('settings', array(&$this->settings));
		$this->twig_vars['settings'] = $this->settings;

		// Add all the stuff that requires processing
		$this->add_content();
		$this->add_meta();
		$this->add_fields();
		$this->add_partials();
		$this->add_posts();
		$this->add_custom_meta();
	}

	private function set_url()
	{
		$request_url = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
		$script_url  = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : '';

		if($request_url != $script_url) $url = trim(preg_replace('/'. str_replace('/', '\/', str_replace('index.php', '', $script_url)) .'/', '', $request_url, 1), '/');
		$this->url = preg_replace('/\?.*/', '', $url);
		$this->plugin->run_hooks('request_url', array(&$url));
	}

	private function load_twig_vars()
	{
		$this->twig_vars = array(
			'settings' => null,
			'base_dir' => '',
			'base_url' => '',
			'theme_dir' => '',
			'theme_url' => '',
			'meta' => null,
			'content' => '',
			'posts' => array(),
			'is_front_page' => false,
			'fields' => null,
			'global' => null,
			'author' => '',
			'date' => array()
		);
	}

	private function get_posts($directory)
	{
		$posts = array();

		$files = scandir($directory);

	    foreach($files as $file)
	    {
	        if($file != '.' && $file != '..')
	        {
	            if(is_dir($directory . '/' . $file))
	            {
	            	$post = get_page($directory . '/' . $file . '/');

	            	$post_formatted = array(
	            		'title' => $post['title'],
	            		'link' => $this->root_url . $post['url'],
	            		'excerpt' => $post['excerpt'],
	            		'content' => $post['content'],
	            		'date' => date_formats($post['date']),
	            		'author' => get_user($post['author'])
	            	);

					$posts[$post['date']] = $post_formatted;
	            }
	        }
	    }

	    krsort($posts);

	    return $posts;
	}
}
