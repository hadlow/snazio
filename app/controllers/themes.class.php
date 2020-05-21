<?php

class Themes extends Controller
{
	public function index()
	{
		// run themes
		$themes = $this->model('ThemesModel');

		// print the header
		$this->view('t/header', array('title' => 'Themes'));

		// view themes data
		$this->view('themes/index', array(
			'themes' => $themes->list_themes()
		));
		
		// print the footer
		$this->view('t/footer');
	}
}