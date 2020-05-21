<?php

class AdminModel
{
	private $title;
	private $name;
	private $content;

	public function title(){ return $this->title; }
	public function name(){ return $this->name; }
	public function content(){ return $this->content; }

	public function run_admin($admin_url)
	{
		global $data;

		foreach($data['admin_page'] as $id)
		{
			foreach($id as $admin_page)
			{
				if($admin_page['name'] == $admin_url)
				{
					$this->title = $admin_page['title'];
					$this->name = $admin_page['name'];
				}
			}
		}
	}

	public function post_admin($hook)
	{
		if(Security::submitted())
		{
			global $plugin;
			$plugin->run_hooks($hook);
		}
	}
}