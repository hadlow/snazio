<?php

class Files extends Controller
{
	public function index()
	{
		// print the header
		$this->view('t/header', array('title' => 'Dashboard'));
		
		// view files data
		$this->view('files/index');
		
		$files = $this->model('FilesModel');
		
		// print the footer
		$this->view('t/footer');
	}
}