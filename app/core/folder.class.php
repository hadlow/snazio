<?php

class Folder
{
	public static function delete($path)
	{
	    if(is_dir($path) === true)
	    {
	        $files = array_diff(scandir($path), array('.', '..'));

	        foreach ($files as $file)
	        {
	            self::delete(realpath($path) . '/' . $file);
	        }

	        return rmdir($path);

	    } else if(is_file($path) === true) {
	        return unlink($path);
	    }

	    return false;
	}

	public static function get_files_array($directory)
	{
		$files = scandir($directory);
		$breakdown = array();
		
	    foreach($files as $file)
	    {
	    	if($file != '.' && $file != '..')
	        {
	            if(is_dir($directory . '/' . $file))
	            {
	            	$breakdown[$file] = self::get_files_array($directory . '/' . $file);
	            } else {
	            	$breakdown[] = $file;
	            }
	        }
	    }

		return $breakdown;
	}

	public static function get_files($directory, $ext = '')
	{
		$array_items = array();
		
		if($handle = opendir($directory))
		{
			while(false !== ($file = readdir($handle)))
			{
				if(preg_match("/^(^\.)/", $file) === 0)
				{
					if(is_dir($directory. "/" . $file))
					{
						$array_items = array_merge($array_items, self::get_files($directory. "/" . $file, $ext));
					} else {
						$file = $directory . "/" . $file;
						if(!$ext || strstr($file, $ext)) $array_items[] = preg_replace("/\/\//si", "/", $file);
					}
				}
			}
			
			closedir($handle);
		}
		
		return $array_items;
	}
}