<?php

class Admin extends Controller
{
	public function __construct()
	{
		global $plugin;
		$plugin->run_hooks('post_load');
	}

	public function index()
	{
		$path = func_get_args();
		$admin_template = $path[0];

		unset($path[0]);
		$args = array_values($path);

		// run admin
		$admin = $this->model('AdminModel');
		$admin->run_admin($admin_template);

		$content_hook = 'admin_content_' . $admin->name();
		$post_hook = 'admin_submit_' . $admin->name();

		$admin->post_admin($post_hook);

		ob_start();
		$this->view('admin/index', array('title' => $admin->title(), 'content' => $content_hook, 'args' => $args));

		$html = ob_get_contents();
		ob_end_clean();
		
		// print the header
		$this->view('t/header', array('title' => $admin->title()));

		echo $html;

		// print the footer
		$this->view('t/footer');
	}
}