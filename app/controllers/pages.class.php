<?php

class Pages extends Controller
{
	public function index()
	{
		// print the header
		$this->view('t/header', array('title' => 'Pages'));
		
		// run
		$pages = $this->model('PagesModel');

		// view pages data
		$this->view('pages/index', array(
			'data' => $pages->view()
		));
		
		// print the footer
		$this->view('t/footer');
	}

	public function posts()
	{
		// print the header
		$this->view('t/header', array('title' => 'Posts'));
		
		// run
		$pages = $this->model('PagesModel');

		// view pages data
		$this->view('pages/posts', array(
			'data' => $pages->view_posts()
		));
		
		// print the footer
		$this->view('t/footer');
	}

	public function edit()
	{
		// run
		$pages = $this->model('PagesModel');
		$path = func_get_args();
		$data = $pages->edit($path);

		// print the header
		$this->view('t/header', array('title' => 'Edit pages'));

		// view pages data
		$this->view('pages/edit', array(
			'data' => $data
		));
		
		// print the footer
		$this->view('t/footer');
	}

	public function delete()
	{
		$path = func_get_args();

		// run
		$pages = $this->model('PagesModel');
		$pages->delete($path);
	}

	public function create()
	{
		$secure = new Security;
		
		// run
		$pages = $this->model('PagesModel');

		$path = func_get_args();
		
		// run creation code
		$errors = $pages->create($secure, $path);
		
		// print the header
		$this->view('t/header', array('title' => 'Create new page'));
		
		// view home data
		$this->view('pages/create', array(
			'title' => Security::grab('title'),
			'token' => $secure->generateToken(),
			'errors' => $errors,
			'templates' => $pages->templates()
		));
		
		// print the footer
		$this->view('t/footer');
	}

	public function create_post()
	{
		$secure = new Security;
		
		// run
		$pages = $this->model('PagesModel');

		$path = func_get_args();
		
		// run creation code
		$errors = $pages->create($secure, $path);
		
		// print the header
		$this->view('t/header', array('title' => 'Create new post'));
		
		// view home data
		$this->view('pages/create_post', array(
			'title' => Security::grab('title'),
			'token' => $secure->generateToken(),
			'errors' => $errors
		));
		
		// print the footer
		$this->view('t/footer');
	}
}