<?php

class PagesModel
{
	public function view()
	{
		$pages = $this->list_pages(FileSystem::path(array('_', 'content')));

		// return code
		return $pages;
	}

	public function view_posts()
	{
		global $config;

		$pages = $this->list_posts(FileSystem::path(array('_', 'content', $config['blog_page'])));

		// return code
		return $pages;
	}

	public function edit($path = array())
	{
		$fields = '';
		$vars = '';
		$js = '';
		$vardata = '';
		$patharray = $path;

		$path = implode('/', $path);

		if(!file_exists(FileSystem::path(array('_', 'content', $path)) . '/'))
		{
			header("Location: " . FileSystem::path(array('admin', 'pages')));
		}

		$page = get_page(FileSystem::path(array('_', 'content', $path)) . '/', false);
		$page['link'] = $path;

		// loop through theme xml to find fields
		global $theme;
		$templates = $theme->templates->template;

		foreach($templates as $data)
		{
			if($data->name == $page['template'])
			{
				// using this template
				if(isset($data->form))
				{
					$form = $data->form;
				} else {
					$form = '';
				}
			}
		}

		if($form != '')
		{
			// loop through each input
			foreach($form->input as $data)
			{
				// get field data
				if(file_exists($page['path'] . $data->name . '.md'))
				{
					$filepath = $page['path'] . $data->name . '.md';
					$file = new File($filepath, "r");

					if(filesize($filepath) != 0)
					{
						$fielddata = $file->read();
					} else {
						$fielddata = '';
					}

					$file->close();
				} else {
					$fielddata = '';
				}
				
				// wysiwyg type
				if($data->type == "wysiwyg")
				{
					$vars .= 'var ' . $data->name . ' = $("#' . $data->name . '").code();';
				} else {
					$vars .= 'var ' . $data->name . ' = $("#' . $data->name . '").val();';
				}
				
				$vardata .= $data->name . ': ' . $data->name . ', ';
				$fields .= parse_field($data->type, $data->name, $data->label, $fielddata);
			}

			$fields .= '<input type="hidden" value="' . $page['path'] . '" id="path" />';

			$vars .= 'var title = $("#title").val();';
			$vars .= 'var date = $("#date").val();';
			$vars .= 'var path = $("#path").val();';
			$vars .= 'var index = $("#index").code();';
			$vars .= 'var desc = $("#desc").val();';
			$vardata .= 'title: title, ';
			$vardata .= 'date: date, ';
			$vardata .= 'path: path, ';
			$vardata .= 'index: index, ';
			$vardata .= 'desc: desc';

			$js = $vars . "
			$.ajax({
				url: '" . FileSystem::url(array('admin', 'ajax', 'page_edit')) . "/',
				type: 'post',
				data: {
					" . $vardata . "
				},
				dataType: 'json',
				success: function(data){
					if(data.success !== undefined)
					{
						popup('success');
						console.log(data);
					} else {
						popup('error');
						console.log(data);
					}
				},
				error: function(data){
					popup('error');
					console.log(data);
				}
			});
			";
		} else {
			$fields .= '<input type="hidden" value="' . $page['path'] . '" id="path" />';

			$vars .= 'var title = $("#title").val();';
			$vars .= 'var date = $("#date").val();';
			$vars .= 'var path = $("#path").val();';
			$vars .= 'var index = $("#index").code();';
			$vars .= 'var desc = $("#desc").val();';
			$vardata .= 'title: title, ';
			$vardata .= 'date: date, ';
			$vardata .= 'path: path, ';
			$vardata .= 'index: index, ';
			$vardata .= 'desc: desc';

			$js = $vars . "
			$.ajax({
				url: '" . FileSystem::url(array('admin', 'ajax', 'page_edit')) . "/',
				type: 'post',
				data: {
					" . $vardata . "
				},
				dataType: 'json',
				success: function(data){
					if(data.success !== undefined)
					{
						popup('success');
						console.log(data);
					} else {
						popup('error');
						console.log(data);
					}
				},
				error: function(data){
					popup('error');
					console.log(data);
				}
			});
			";
		}
		
		$data = array_merge($page, array('fields' => $fields), array('js' => $js));

		// return code
		return $data;
	}

	public function create($secure = null, $folder = array())
	{
		// if form submitted
		if(Security::submitted())
		{
			// if token set
			if($secure->checkToken($secure->grab('token')))
			{
				$folder = implode('/', $folder);
				$folder = trim($folder,'/');
				$page = FileSystem::path(array('_', 'content', $folder));

				// validate all inputs
				$secure->validate($_POST, array(
					'title' => array(
						'name' => 'Title',
						'required' => true
					)
				));
				
				// if no errors
				if($secure->passed())
				{
					if(isset($_POST['template']))
					{
						$template = $secure->grab('template');
					} else {
						$template = 'post';
					}

					// create
					$create = array(
						'title' => $secure->grab('title'),
						'path' => $page,
						'template' => $template
					);

					// get fields of template
					global $theme;
					$templates = $theme->templates->template;

					foreach($templates as $data)
					{
						if($data->name == $create['template'])
						{
							// using this template
							$form = $data->form;
						}
					}

					// create page.json file
					$url = clean_url($create['title']);
					$temppath = $create['path'] . '/' . $url . "";
					$extension = '';

					// if page already exists
					while(file_exists($temppath . $extension . '/page.json'))
					{
						$extension = $extension . "1";
					}

					$temppath = $temppath . $extension;
					$newpath = $temppath . '/';

					mkdir($newpath);

					$json = new File($newpath . "page.json", "w");
					$date = new DateTime();

					$meta = array(
						'title' => $create['title'],
						'author' => $_SESSION['user'],
						'date' => $date->getTimestamp(),
						'template' => $create['template']
					);

					$json->write_yaml($meta);
					$json->close();

					$md = fopen($newpath . "index.md", "w");

					// loop through each input
					foreach($form->input as $data)
					{
						// create each md file for field
						$md = fopen($newpath . $data->name . ".md", "w");
					}

					// redirect
					header("Location: " . FileSystem::url(array('admin', 'pages', 'edit', $folder, $url . $extension)));
					
				} else {
					// if errors
					$error = new Error;
					
					// create displayable errors
					$error->validation($secure->error());
					
					// store HTML errors in variable
					return $errors = $error->getValidError();
				}
			} else {
				header("Location: error");
				die();
			}
		}
	}

	public function delete($path = array())
	{
		$path = implode('/', $path);
		$page = FileSystem::path(array('_', 'content', $path)) . '/';

		// delete the page
		Folder::delete($page);

		header("Location: " . FileSystem::url(array('admin', 'pages')));

		// return code
		return $page;
	}

	public function templates()
	{
		global $theme;
		$html = '';
		$x = 0;
		$checked = '';
		$image = '';

		$templates = $theme->templates->template;

		$html .= '<div class="row templates">';

		foreach($templates as $data)
		{
			$checked = ($x > 0 ? '' : ' checked');

			if(!property_exists($data, 'image'))
			{
				$image = FileSystem::url(array('admin', 'assets', 'images', 'default.png'));
			} else {
				$image = $data->image;
			}

			$html .= '<div class="col-sm-4">
					<label>
						<input type="radio" name="template" value="' . $data->name . '"' . $checked . ' />
						
						<img src="' . $image . '" class="img-responsive" width="100%" />

						<h5><strong>' . $data->title . '</strong></h5>
					</label>
				</div>';

			$x++;
		}

		$html .= '</div>';

		// return code
		return $html;
	}

	private function list_pages($directory)
	{
		global $config;
		$html = '';
		$blog = '';

		$files = scandir($directory);
	    $html .= '<ul>';

	    foreach($files as $file)
	    {
	        if($file != '.' && $file != '..')
	        {
	            if(is_dir($directory . '/' . $file))
	            {
	            	$html .= '<li>';

	            	$page = get_page($directory . '/' . $file . '/');

	            	if($page['url'] != '/' . $config['blog_page'] . '/')
	                {
	                	$blog = '<a href="' . FileSystem::url(array('admin', 'pages', 'create', $page['url'])) . '" data-toggle="tooltip" title="New sub page" class="optionleft">
	                		<i class="fa fa-level-down"></i>
	                	</a>';
	                } else {
	                	$blog = '<a href="' . FileSystem::url(array('admin', 'pages', 'posts')) . '" data-toggle="tooltip" title="View posts" class="optionleft" style="width: 110px; font-size: 12px;">
	                		View posts <i class="fa fa-eye"></i>
	                	</a>';
	                }
	            	
	                $html .= '<div class="box">
	                	<a href="' . FileSystem::url(array('admin', 'pages', 'edit', $page['url'])) . '" class="optionfirst">' . $page['title'] . '</a>
						
						' . $blog . '

	                	<div class="options">
							<a href="' . FileSystem::url(array('admin', 'pages', 'delete', $page['url'])) . '" class="delete">
								<div class="option" data-toggle="tooltip" title="Delete">
									<i class="fa fa-trash"></i>
								</div>
							</a>

							<a href="' . FileSystem::url(array('admin', 'pages', 'edit', $page['url'])) . '">
								<div class="option" data-toggle="tooltip" title="Edit page">
									<i class="fa fa-pencil"></i>
								</div>
							</a>

							<a href="' . FileSystem::url(array($page['url'])) . '" target="_blank">
								<div class="option" data-toggle="tooltip" title="View page">
									<i class="fa fa-link"></i>
								</div>
							</a>
	                	</div>
	                </div>';

	                if($page['url'] != '/' . $config['blog_page'] . '/')
	                {
	                	$html .= $this->list_pages($directory . '/' . $file);
	                }

	                $html .= '</li>';
	            }
	        }
	    }

	    $html .= '</ul>';

	    return $html;
	}

	private function list_posts($directory)
	{
		$html = '';

		$files = scandir($directory);
	    $html .= '<ul>';

	    foreach($files as $file)
	    {
	        if($file != '.' && $file != '..')
	        {
	            if(is_dir($directory . '/' . $file))
	            {
	            	$html .= '<li>';

	            	$page = get_page($directory . '/' . $file . '/');
	            	
	                $html .= '<div class="box">
	                	<a href="' . FileSystem::url(array('admin', 'pages', 'edit', $page['url'])) . '" class="optionfirst">' . $page['title'] . '</a>

	                	<div class="options">
							<a href="' . FileSystem::url(array('admin', 'pages', 'delete', $page['url'])) . '" class="delete">
								<div class="option" data-toggle="tooltip" title="Delete">
									<i class="fa fa-trash"></i>
								</div>
							</a>

							<a href="' . FileSystem::url(array('admin', 'pages', 'edit', $page['url'])) . '">
								<div class="option" data-toggle="tooltip" title="Edit post">
									<i class="fa fa-pencil"></i>
								</div>
							</a>

							<a href="' . FileSystem::url(array($page['url'])) . '" target="_blank">
								<div class="option" data-toggle="tooltip" title="View post">
									<i class="fa fa-link"></i>
								</div>
							</a>
	                	</div>
	                </div>';

	                $html .= $this->list_pages($directory . '/' . $file);

	                $html .= '</li>';
	            }
	        }
	    }

	    $html .= '</ul>';

	    return $html;
	}
}
