<?php

class Home extends Controller
{
	public function index()
	{
		$home = $this->model('HomeModel');

		// print the header
		$this->view('t/header', array('title' => 'Home'));
		
		// view home data
		$this->view('home/index', array('widgets' => $home->widgets()));
		
		// print the footer
		$this->view('t/footer');
	}
}