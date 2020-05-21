<?php

class Store extends Controller
{
	public function index()
	{
		// print the header
		$this->view('t/header', array('title' => 'Store'));
		
		// run
		$store = $this->model('StoreModel');
		
		// view data
		$this->view('store/index',array(
			'themes' => $store->get_themes()
		));
		
		// print the footer
		$this->view('t/footer');
	}
	
	public function product($id = 0)
	{
		// print the header
		$this->view('t/header', array('title' => 'Product'));
		
		// run
		$store = $this->model('StoreModel');
		
		// view data
		$this->view('store/product',array(
			'data' => $store->get($id)
		));
		
		// print the footer
		$this->view('t/footer');
	}
}