<?php

class FileSystem
{
	public static function path($path = array())
	{
		$pathstring = '';

		foreach($path as $folder)
		{
			$folder = trim($folder, "/");

			if($folder != '')
			{
				$pathstring .= $folder . '/';
			} else {
				$pathstring .= '';
			}
		}

		return trim(ROOT_DIR . $pathstring, '/');
	}

	public static function url($path = array())
	{
		$pathstring = '';

		foreach($path as $folder)
		{
			$folder = trim($folder, "/");

			if($folder != '')
			{
				$pathstring .= $folder . '/';
			} else {
				$pathstring .= '';
			}
		}
		
		return trim(ROOT_URL . $pathstring, '/');
	}
}