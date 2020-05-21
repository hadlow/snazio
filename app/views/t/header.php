<?php

global $config;
global $user;
global $plugin;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<title><?php echo $config['website_name']; ?> | <?php echo $d['title']; ?></title>
	
	<link href="<?php echo ADMIN_URL; ?>assets/css/bootstrap.css" rel="stylesheet">
	<link href="<?php echo ADMIN_URL; ?>assets/css/summernote/summernote.css" rel="stylesheet">
	<link href="<?php echo ADMIN_URL; ?>assets/css/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,700,300' rel='stylesheet' type='text/css'>
	
	<link href="<?php echo ADMIN_URL; ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">
	
	<meta content-type: text/html; charset=ISO-8859-1 />
	<meta http-equiv="Content-Type" content="cache" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="shortcut icon" href="<?php echo ADMIN_URL; ?>assets/images/icon.png" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
	<script src="<?php echo ADMIN_URL; ?>assets/js/cookie.js"></script>
	
	<link rel="shortcut icon" href="<?php echo ADMIN_URL; ?>assets/images/icons/icon16.png">
	<link rel="apple-touch-icon" href="<?php echo ADMIN_URL; ?>assets/images/icons/icon32.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo ADMIN_URL; ?>assets/images/icons/icon72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo ADMIN_URL; ?>assets/images/icons/icon14.png">

	<?php $plugin->run_hooks('admin_head'); ?>
	
</head>
<body>

<?php $plugin->run_hooks('admin_before_header'); ?>

<header>
	<button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target=".navmenu">
		<span class="icon-bar"></span>

		<span class="icon-bar"></span>

		<span class="icon-bar"></span>
	</button>
	
	<div class="header-bar">
		<div class="item left">
			<a href="<?php echo ADMIN_URL; ?>">
				<img src="<?php echo ADMIN_URL; ?>assets/images/icons/icon64.png" height="40" />
			</a>
		</div>

		<div class="btn-group pull-right" style="margin-right:15px; border-right:1px solid #DDD;">
			<a type="button" class="item right border dropdown-toggle" data-toggle="dropdown">
				<i class="fa fa-user"></i>
			</a>

			<ul class="dropdown-menu" role="menu">
				<li class="nolink">
					<p>
						<strong><?php echo $user['name']; ?></strong><br />
						<small><?php echo $user['email']; ?></small>
					</p>

					<a class="nopadding btn btn-default" href="<?php echo ADMIN_URL . 'users/edit/' . $user['username']; ?>">Edit profile</a>
				</li>

				<li class="divider"></li>
				
				<li class="nolink">
					<p>
						<strong><?php echo $config['website_name']; ?></strong><br />
						<small><?php echo ROOT_URL; ?></small>
					</p>

					<div class="btn-group">
						<a class="btn btn-default nopadding" href="<?php echo ROOT_URL; ?>">View site</a>
						<a class="btn btn-default nopadding" href="<?php echo ADMIN_URL; ?>settings">Settings</a>
					</div>
				</li>

				<li class="divider"></li>

				<li class="nolink" style="height:58px; margin:-9px 0 -5px 0;"><a class="btn btn-default nopadding" style="float:right;" href="<?php echo ADMIN_URL . 'logout'; ?>">Logout</a></li>
			</ul>
		</div>

		<div class="btn-group pull-right">
			<a type="button" class="item right border dropdown-toggle" data-toggle="dropdown">
				<i class="fa fa-th-large"></i>
			</a>

			<ul class="dropdown-menu tip" style="width:360px; padding:20px;" role="menu">
				<?php echo $plugin->run_apps(); ?>
			</ul>
		</div>
	</div>
</header>

<?php $plugin->run_hooks('admin_after_header'); ?>

<aside>
	<div class="navmenu navmenu-inverse navmenu-fixed-left offcanvas-sm">
		<div class="bottom"></div>
		
		<nav>
			<ul class="nav">
				<h6>CONTENT</h6>
				
				<li>
					<a href="<?php echo ADMIN_URL; ?>home"><i class="fa fa-home"></i>Home</a>
				</li>
				
				<li>
					<a href="<?php echo ADMIN_URL; ?>pages"><i class="fa fa-edit"></i>Pages</a>
				</li>

				<li>
					<a href="<?php echo ADMIN_URL; ?>widgets"><i class="fa fa-wrench"></i>Widgets</a>
				</li>
				
				<li>
					<a href="<?php echo ADMIN_URL; ?>files"><i class="fa fa-file-text"></i>Files</a>
				</li>
			</ul>
			
			<div class="divider"></div>
			
			<ul class="nav">
				<h6>Addons</h6>
				
				<li>
					<a href="<?php echo ADMIN_URL; ?>store"><i class="fa fa-shopping-cart"></i>Store</a>
				</li>

				<li>
					<a href="<?php echo ADMIN_URL; ?>themes"><i class="fa fa-paint-brush"></i>Themes</a>
				</li>
				
				<li>
					<a href="<?php echo ADMIN_URL; ?>plugins"><i class="fa fa-plug"></i>Plugins</a>
				</li>
			</ul>
			
			<div class="divider"></div>
			
			<ul class="nav">
				<h6>Extras</h6>
				
				<li>
					<a href="<?php echo ADMIN_URL; ?>settings"><i class="fa fa-cog"></i>Settings</a>
				</li>
				
				<li>
					<a href="<?php echo ADMIN_URL; ?>users"><i class="fa fa-users"></i>Users</a>
				</li>
			</ul>
		</nav>
	</div>
</aside>

<div class="container">
<?php $plugin->run_hooks('admin_first_container'); ?>