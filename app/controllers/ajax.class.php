<?php

class Ajax extends Controller
{
	public function index()
	{
		// view ajax data
		//$this->view('ajax/index');
		
		$ajax = $this->model('AjaxModel');
	}

	public function partial_edit()
	{
		// view ajax data
		$ajax = $this->model('AjaxModel');

		$this->view('ajax/partial_edit', array(
			'data' => $ajax->partial_edit()
		));
	}

	public function page_edit()
	{
		// view ajax data
		$ajax = $this->model('AjaxModel');

		$this->view('ajax/page_edit', array(
			'data' => $ajax->page_edit()
		));
	}
}