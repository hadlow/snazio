<?php

class Plugins extends Controller
{
	public function index()
	{
		// print the header
		$this->view('t/header', array('title' => 'Plugins'));
		
		// view plugins data
		$this->view('plugins/index');
		
		// run menus
		$plugins = $this->model('PluginsModel');
		
		// print the footer
		$this->view('t/footer');
	}
}