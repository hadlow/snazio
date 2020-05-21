<?php

class ThemesModel
{
	public function list_themes()
	{
		$html = '';

		$files = get_files(FileSystem::path(array('_', 'themes')), '.xml');

		foreach($files as $filepath)
		{
			$data = simplexml_load_file($filepath);

			$html .= '<div class="col-sm-6 top">
				<a href="">
					<div class="theme">
						<div class="image">
							<img src="' . $data->data->image . '" width="100%" />
						</div>
						
						<p>
							' . $data->data->name . '
						</p>
						
						<span>
							' . $data->data->description . '
						</span>
					</div>
				</a>
			</div>';
		}

		return $html;
	}
}