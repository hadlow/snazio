<?php

class AjaxModel
{
	public function partial_edit()
	{
		// if form submitted
		if(Security::submitted())
		{
			// loop through each input
			foreach($_POST as $file => $data)
			{
				// create each md file for field
				$md = new File(FileSystem::path(array('_', 'widgets', $file . ".md")), "w");
				$md->write($data);
				$md->close();
			}

			return json_encode(array('success' => true));
		}
	}

	public function page_edit()
	{
		// if form submitted
		if(Security::submitted())
		{
			$path = $_POST['path'];
			$title = $_POST['title'];
			$index = $_POST['index'];
			$desc = $_POST['desc'];
			
			unset($_POST['path']);
			unset($_POST['title']);
			unset($_POST['index']);
			unset($_POST['desc']);

			if(file_exists($path))
			{
				// update yaml data
				$metafile = new File($path . "page.json", "r");
				$meta = $metafile->read_yaml();
				$metafile->close();
				
				$meta['title'] = $title;
				$meta['description'] = $desc;

				$yaml = new File($path . "page.json", "w");
				$yaml->write_yaml($meta);
				$yaml->close();

				// update index.md
				$mdindex = new File($path . "index.md", "w");
				$mdindex->write($index);
				$mdindex->close();

				// loop through each input
				foreach($_POST as $file => $data)
				{
					// create each md file for field
					$md = new File($path . $file . ".md", "w");
					$md->write($data);
					$md->close();
				}
			}

			return json_encode(array('success' => true, 'data' => $meta));
		}
	}
}
