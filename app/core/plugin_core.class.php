<?php

class Plugin_Core
{
	protected function post_data($root = '', $data = array())
	{
		// if form submitted
		if(Security::submitted())
		{
			// get new folder number
			$c = 0;

			while(file_exists($root . '/data/' . $c))
			{
				$c++;
			}

			$newpath = $root . '/data/' . $c;
			mkdir($newpath);

			foreach($data as $name => $content)
			{
				$file = fopen($newpath . "/" . $name . ".md", "w");
				fwrite($file, $content);
			}

			return true;
		}
	}

	protected function get_data($root = '', $id = '')
	{
		$data = array();
		$path = $root . '/data/' . $id;
		$x = 0;

		if($id == '')
		{
			while(file_exists($path . $x))
			{
				$files = get_files_array($path . $x);

				foreach($files as $filename)
				{
					$element = substr($filename, 0, -3);
					$content = file_get_contents($path . '' . $x . '/' . $filename);

					$data[$x][$element] = $content;
				}

				$x++;
			}
		} else {
			if(file_exists($path))
			{
				$files = get_files_array($path);

				foreach($files as $filename)
				{
					$element = substr($filename, 0, -3);
					$content = file_get_contents($path . '/' . $filename);

					$data[$element] = $content;
				}
			} else {
				return false;
			}
		}

		return $data;
	}

	protected function update_data($root = '', $id, $data = array())
	{
		// if form submitted
		if(Security::submitted())
		{
			$newpath = $root . '/data/' . $id;

			foreach($data as $name => $content)
			{
				if(file_exists($newpath . "/" . $name . ".md"))
				{
					$file = fopen($newpath . "/" . $name . ".md", "w");
					fwrite($file, $content);
				}
			}
		}
	}

	protected function delete_data($root = '', $id)
	{
		$path = $root . '/data/' . $id . '/';

		if(file_exists($path))
		{
			delete_contents($path);

			return true;
		}
	}

	protected function admin_page($fields = array())
	{
		global $data;

		$defaults = array(
			'name' => '',
			'title' => '',
			'app' => false
		);

		$data['admin_page'][] = array($fields);
	}

	protected function add_home_widget($widget)
	{
		global $data;

		$data['home_widgets'][] = $widget;
	}
}