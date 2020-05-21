<?php

class Plugin
{
	protected $plugins = array();

	public function __construct()
	{
		$this->load_plugins();
	}

	protected function load_plugins()
	{
		$this->plugins = array();

		$plugins = get_files(FileSystem::path(array('_', 'plugins')), '.php');

		if(!empty($plugins))
		{
			foreach($plugins as $plugin)
			{
				include_once($plugin);

				$plugin_name = preg_replace("/\\.[^.\\s]{3}$/", '', basename($plugin));

				if(class_exists($plugin_name))
				{
					$obj = new $plugin_name;
					$this->plugins[] = $obj;
				}
			}
		}
	}

	public function run_hooks($hook_id, $args = array())
	{
		$output = "";

		if(!empty($this->plugins))
		{
			foreach($this->plugins as $plugin)
			{
				if(is_callable(array($plugin, $hook_id)))
				{
					try
					{
						$output .= call_user_func_array(array($plugin, $hook_id), $args);
					} catch(Exception $e)
					{
						echo "Error";
					}
				}
			}
		}

		return $output;
	}

	public function run_apps()
	{
		global $data;
		$apps = '';

		if(isset($data['admin_page']))
		{
			foreach($data['admin_page'] as $id)
			{
				foreach($id as $admin => $app)
				{
					if($app['app'])
					{
						$name = $app['name'];
						$title = $app['title'];
						$url = FileSystem::url(array('admin', 'admin', 'index', $name));
						$icon = FileSystem::url(array('_', 'plugins', $name, 'icon.png'));

						$apps .= '<div class="app"><a href="' . $url . '" title="' . $title . '"><img src="' . $icon . '" /></a></div>';
					}
				}
			}

			return $apps;
		}

		return false;
	}
}
