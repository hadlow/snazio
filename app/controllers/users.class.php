<?php

class Users extends Controller
{
	public function index()
	{
		$users = $this->model('UsersModel');

		// print the header
		$this->view('t/header', array('title' => 'Users'));

		$this->view('users/index', array(
			'data' => $users->view()
		));
		
		// print the footer
		$this->view('t/footer');
	}

	public function edit($username = '')
	{
		if($username == '')
		{
			header("Location: " . ADMIN_URL . "users");
		}

		$secure = new Security;
		
		$users = $this->model('UsersModel');
		$errors = $users->update_user($secure, $username);

		// print the header
		$this->view('t/header', array('title' => 'Edit user'));

		$this->view('users/edit', array(
			'data' => $users->get($username),
			'token' => $secure->generateToken(),
			'errors' => $errors
		));
		
		// print the footer
		$this->view('t/footer');
	}

	public function delete($user)
	{
		// run
		$usermodel = $this->model('UsersModel');
		$usermodel->delete($user);
	}
}