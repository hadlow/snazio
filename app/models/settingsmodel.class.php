<?php

class SettingsModel
{
	public function get_info()
	{
		$html = '';

		// see if .htaccess or web.config files exist
		if(file_exists(ROOT_DIR . '.htaccess') || file_exists(ROOT_DIR . 'web.config'))
		{
			$html .= '<div class="top"><p class="align"><i class="fa fa-check-circle-o big"></i> Folders secured</p></div>';
			$html .= '<div class="topsmall"><p class="align"><i class="fa fa-check-circle-o big"></i> User accounts secured</p></div>';
		} else {
			$html .= '<div class="top"><p class="align"><i class="fa fa-times-circle-o big"></i> Folders secured</p></div>';
			$html .= '<div class="topsmall"><p class="align"><i class="fa fa-times-circle-o big"></i> User accounts secured</p></div>';
		}

		return $html;
	}

	public function refresh_cache()
	{
		$path = ROOT_DIR . '_/cache/';

		if(file_exists($path))
		{
			Folder::delete($path);
			mkdir($path);

			header("Location: " . ADMIN_URL . "settings/");
		}
	}

	private function list_pages($directory, $spacing = '', $blog_page = '')
	{
		global $config;
		$html = '';
		$blog = '';
		$checked = '';

		$files = scandir($directory);

	    foreach($files as $file)
	    {
	        if($file != '.' && $file != '..')
	        {
	            if(is_dir($directory . '/' . $file))
	            {
	            	$page = get_page($directory . '/' . $file . '/');

	            	if($blog_page == trim($page['url'], '/'))
	            	{
	            		$checked = ' selected="selected"';
	            	} else {
	            		$checked = '';
	            	}
	            	
	                $html .= '<option value="' . trim($page['url'], '/') . '"' . $checked . '>' . $spacing . '' . $page['title'] . '</option>';

	                if($page['url'] != '' . $config['blog_page'] . '/')
	                {
	                	$html .= $this->list_pages($directory . '/' . $file, $spacing . "&#160;&#160;");
	                }
	            }
	        }
	    }

	    return $html;
	}

	public function blog_page($blog)
	{
		$html = '';
		$html = $this->list_pages(FileSystem::path(array('_', 'content')), '', $blog);

		return $html;
	}

	public function update_settings($secure)
	{
		// if form submitted
		if(Security::submitted())
		{
			// if token set
			if($secure->checkToken($secure->grab('token')))
			{
				// validate all inputs
				$secure->validate($_POST, array(
					'website_name' => array(
						'name' => 'Website name',
						'required' => true
					),
					'slogan' => array(
						'name' => 'Slogan',
						'required' => true
					),
					'blog_page' => array(
						'name' => 'Blog page'
					),
					'excerpt_length' => array(
						'name' => 'Excerpt Length',
						'required' => true
					)
				));
				
				// if no errors
				if($secure->passed())
				{
					global $config;

					$config['website_name'] = $secure->grab('website_name');
					$config['slogan'] = $secure->grab('slogan');
					$config['blog_page'] = $secure->grab('blog_page');
					$config['excerpt_length'] = $secure->grab('excerpt_length');

					if(isset($_POST['dev_mode']))
					{
						$config['dev_mode'] = 1;
					} else {
						$config['dev_mode'] = 0;
					}

					if(file_exists(FileSystem::path(array('app', 'config', 'config.json'))))
					{
						$yaml = new File(FileSystem::path(array('app', 'config', 'config.json')), "w");
						$yaml->write_yaml($config);
						$yaml->close();
					} else {
						header("Location: " . FileSystem::url(array('admin', 'error')));
					}

					return '<div class="alert alert-success">Settings updated</div>';
					
				} else {
					// if errors
					$error = new Error;
					
					// create displayable errors
					$error->validation($secure->error());
					
					// store HTML errors in variable
					return $errors = $error->getValidError();
				}
			} else {
				header("Location: " . ADMIN_URL . "error");
				die();
			}
		}
	}
}