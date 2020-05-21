<?php

// get config
require_once '../../app/config/config.php';

// load core classes
require_once '../../app/core/functions.php';
//require_once '../../app/core/errors.class.php';
require_once '../../app/core/security.class.php';

// if user not logged in
if(!isset($_SESSION['user']))
{
	header("Location: " . ADMIN_URL . "login");
} else {
	
}

// ini class
$secure = new Security;

$errors = null;

// if form submitted
if(Security::submitted())
{
	// if token matches
	if($secure->checkToken($secure->grab('token')))
	{
		// validate all inputs
		$secure->validate($_POST, array(
			'username' => array(
				'name' => 'Username',
				'required' => true
			),
			'password' => array(
				'name' => 'Password',
				'required' => true
			),
			'email' => array(
				'name' => 'Email Address',
				'required' => true
			),
			'fname' => array(
				'name' => 'First name'
			),
			'lname' => array(
				'name' => 'Last name'
			),
			'about' => array(
				'name' => 'About'
			)
		));
		
		// if no errors
		if($secure->passed())
		{
			$username = $secure->grab('username');
			$password = $secure->grab('password');
			$email = $secure->grab('email');
			$fname = $secure->grab('fname');
			$lname = $secure->grab('lname');
			$about = $secure->grab('about');
			
			// create password hash
			$salt = $secure->salt();
			$hashedPass = $secure->makeHash($password, $salt);
			$newPass = $hashedPass . 'l' . $salt;
			
			$userroot = '../../app/config/users/' . $username . '.yaml';
			if(file_exists($userroot))
			{
				$errors = '<div class="alert alert-danger">Email or password are incorrect. Please try again.</div>';
			} else {
				$userarray = array(
					'username' => $username,
					'password' => $newPass,
					'email' => $email,
					'fname' => $fname,
					'lname' => $lname,
					'about' => $about
				);

				$useryaml = Spyc::YAMLDump($userarray);

				$md = fopen($userroot, "w");
				fwrite($md, $useryaml);

				header("Location: index.php");
			}
		} else {
			// if errors
			$error = new Error;
			
			// create displayable errors
			$error->validation($secure->error());
			
			// store HTML errors in variable
			$errors = $error->getValidError();
		}
	} else {
		// redirect to error page
		//header("Location: " . ADMIN_URL . "login/error");
		die($secure->grab('token'));
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	
	<title>Register User</title>
	
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,700,300' rel='stylesheet' type='text/css'>
	<link href="<?php echo ADMIN_URL; ?>login/assets/css/bootstrap.css" rel="stylesheet">
	<link href="<?php echo ADMIN_URL; ?>login/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
	
	<meta content-type: text/html; charset=ISO-8859-1 />
	<meta http-equiv="Content-Type" content="cache" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="shortcut icon" href="<?php echo ADMIN_URL; ?>login/assets/images/icons/icon16.png">
	<link rel="apple-touch-icon" href="<?php echo ADMIN_URL; ?>login/assets/images/icons/icon32.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo ADMIN_URL; ?>login/assets/images/icons/icon72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo ADMIN_URL; ?>login/assets/images/icons/icon14.png">
	
</head>

<body style="padding:0 !important;">

	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="login-panel panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Create account</h3>
					</div>
					
					<div class="panel-body">
						<?php echo $errors; ?>
						
						<form role="form" action="" method="POST">
							<fieldset>
								<div class="form-group">
									<label for="username">Username</label>
									
									<input type="text" name="username" id="username" maxlength="255" autofocus class="form-control" />
								</div>

								<div class="form-group">
									<label for="password">Password</label>
									
									<input type="password" name="password" id="password" maxlength="255" class="form-control" />
								</div>

								<div class="form-group">
									<label for="email">Email address</label>
									
									<input type="text" name="email" id="email" maxlength="255" class="form-control" />
								</div>

								<div class="form-group">
									<label for="fname">First name</label>
									
									<input type="text" name="fname" id="fname" maxlength="255" class="form-control" />
								</div>

								<div class="form-group">
									<label for="lname">Last name</label>
									
									<input type="text" name="lname" id="lname" maxlength="255" class="form-control" />
								</div>

								<div class="form-group">
									<label for="about">About</label>
									
									<textarea name="about" id="about" maxlength="255" class="form-control"></textarea>
								</div>
								
								<div class="form-group">
									<input type="hidden" value="<?php echo $secure->generateToken(); ?>" name="token" />
									<input type="submit" class="btn btn-md btn-primary" value="Register" />
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
    </div>

</body>
</html>