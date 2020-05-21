<?php

class Settings extends Controller
{
	public function index()
	{
		global $config;
		$secure = new Security;

		$settings = $this->model('SettingsModel');
		$errors = $settings->update_settings($secure);

		// print the header
		$this->view('t/header', array('title' => 'Settings'));

		// view settings data
		$this->view('settings/index', array(
			'data' => array(
				'website_name' => $config['website_name'],
				'slogan' => $config['slogan'],
				'excerpt_length' => $config['excerpt_length'],
				'dev_mode' => $config['dev_mode'],
				'blog_page' => $settings->blog_page($config['blog_page'])
			),
			'info' => $settings->get_info(),
			'token' => $secure->generateToken(),
			'errors' => $errors
		));
		
		// print the footer
		$this->view('t/footer');
	}

	public function refresh_cache()
	{
		$settings = $this->model('SettingsModel');
		$settings->refresh_cache();
	}
}