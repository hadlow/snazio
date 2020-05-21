<?php

class UsersModel
{
	public function view()
	{
		$users = $this->list_users(ROOT_DIR . 'app/config/users');

		// return code
		return $users;
	}

	public function get($username)
	{
		$userpath = ROOT_DIR . 'app/config/users/' . $username . '.json';

		if(file_exists($userpath))
		{
			$userfile = new File($userpath, "r");
			$user = $userfile->read_yaml();
			$userfile->close();
		} else {
			$user = array();

			header("Location: " . ADMIN_URL . "users");
		}
		
		return $user;
	}

	public function delete($path = '')
	{
		echo $user = FileSystem::path(array('app', 'config', 'users', $path)) . '.json';

		// delete the user
		unlink($user);

		header("Location: " . FileSystem::url(array('admin', 'users')));

		// return code
		return $user;
	}

	public function update_user($secure, $username)
	{
		// if form submitted
		if(Security::submitted())
		{
			// if token set
			if($secure->checkToken($secure->grab('token')))
			{
				// validate all inputs
				$secure->validate($_POST, array(
					'name' => array(
						'name' => 'Full name',
						'required' => true
					),
					'email' => array(
						'name' => 'Email address',
						'required' => true
					),
					'about' => array(
						'name' => 'About'
					),
					'password' => array(
						'name' => 'Password'
					)
				));
				
				// if no errors
				if($secure->passed())
				{
					$userpath = ROOT_DIR . 'app/config/users/' . $username . '.json';
					$userfile = new File($userpath, "r");
					$user = $userfile->read_yaml();
					$userfile->close();

					$user['name'] = $secure->grab('name');
					$user['email'] = $secure->grab('email');
					$user['about'] = $secure->grab('about');
					$password = $secure->grab('password');

					// they wanna change the password
					if(!empty($password))
					{
						$salt = $secure->salt();
						$hashedPass = $secure->makeHash($password, $salt);
						$newPass = $hashedPass . 'l' . $salt;

						$user['password'] = $newPass;
					}

					if(file_exists($userpath))
					{
						$userfile = new File($userpath, "w");
						$userfile->write_yaml($user);
						$userfile->close();
					} else {
						header("Location: " . ADMIN_URL . "error");
					}

					return '<div class="alert alert-success">User updated</div>';
					
				} else {
					// if errors
					$error = new Error;
					
					// create displayable errors
					$error->validation($secure->error());
					
					// store HTML errors in variable
					return $errors = $error->getValidError();
				}
			} else {
				header("Location: " . ADMIN_URL . "error");
				die();
			}
		}
	}

	private function list_users($directory)
	{
		$html = '';

		$files = scandir($directory);
	    $html .= '<ul>';

	    foreach($files as $file)
	    {
	        if($file != '.' && $file != '..')
	        {
	            if(!is_dir($directory . '/' . $file))
	            {
	            	$html .= '<li>';

	            	$userfile = new File($directory . '/' . $file, "r");
					$user = $userfile->read_yaml();
					$userfile->close();
	            	
	                $html .= '<div class="box">
	                	<a href="users/edit/' . $user['username'] . '" class="optionfirst">' . $user['name'] . '</a>

	                	<div class="options">
							<a href="users/delete/' . $user['username'] . '" class="delete">
								<div class="option" data-toggle="tooltip" title="Delete">
									<i class="fa fa-trash"></i>
								</div>
							</a>

							<a href="users/edit/' . $user['username'] . '">
								<div class="option" data-toggle="tooltip" title="Edit user">
									<i class="fa fa-pencil"></i>
								</div>
							</a>
	                	</div>
	                </div>';

	                $html .= '</li>';
	            }
	        }
	    }

	    $html .= '</ul>';

	    return $html;
	}
}