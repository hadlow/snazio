<?php

class Widgets extends Controller
{
	public function index()
	{
		// print the header
		$this->view('t/header', array('title' => 'Widgets'));
		
		// run
		$widgets = $this->model('WidgetsModel');

		// view widgets data
		$this->view('widgets/index', array(
			'edit_partials' => $widgets->edit_partials()
		));
		
		// print the footer
		$this->view('t/footer');
	}
}