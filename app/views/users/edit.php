<div class="heading">
	<div class="title">
		Edit user

		<a href="<?php echo ADMIN_URL; ?>login/register.php">
			<span class="btn btn-default"><strong><i class="fa fa-plus-circle"></i>Create new user</strong></span>
		</a>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<?php echo $d['errors']; ?>

		<form action="" method="POST" id="editform">
			<div class="form-group">
				<label>Full name</label>

				<input type="text" class="form-control" value="<?php echo $d['data']['name']; ?>" name="name" id="name" />
			</div>

			<div class="form-group">
				<label>Email address</label>

				<input type="text" class="form-control" value="<?php echo $d['data']['email']; ?>" name="email" id="email" />
			</div>
			
			<div class="form-group">
				<label>About</label>

				<textarea class="form-control" name="about" id="about" style="height:200px"><?php echo $d['data']['about']; ?></textarea>
			</div>

			<div class="form-group">
				<label>New password (leave blank to keep old password)</label>

				<input type="password" class="form-control" value="" name="password" id="password" />
			</div>

			<div class="form-group">
				<input type="hidden" name="token" value="<?php echo $d['token'] ?>" />
				<input type="submit" class="btn btn-primary" value="Edit user" />
			</div>
		</form>
	</div>
</div>