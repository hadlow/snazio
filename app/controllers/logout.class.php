<?php

class Logout extends Controller
{
	public function index()
	{
		$logout = $this->model('LogoutModel');

		$logout->logout();
	}
}