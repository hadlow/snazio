<?php

class Cloud extends Controller
{
	public function index()
	{
		// print the header
		$this->view('t/header', array('title' => 'Dashboard'));
		
		// view cloud data
		$this->view('cloud/index');
		
		$cloud = $this->model('CloudModel');
		
		// print the footer
		$this->view('t/footer');
	}
}