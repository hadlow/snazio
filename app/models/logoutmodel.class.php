<?php

class LogoutModel
{
	public function logout()
	{
		// kill sessions
		session_destroy();

		// redirect to login
		header("Location: " . FileSystem::url(array('admin', 'login')));
	}
}