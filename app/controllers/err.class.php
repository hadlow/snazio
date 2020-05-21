<?php

class Err extends Controller
{
	public function index()
	{
		// print the header
		$this->view('t/header', array('title' => 'Error'));
		
		// view error data
		$this->view('error/index');
		
		// print the footer
		$this->view('t/footer');
	}
}