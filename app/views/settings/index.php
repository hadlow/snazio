<div class="heading">
	<div class="title">
		CMS Settings
	</div>
</div>

<div class="row">
	<div class="col-sm-6 bottom">
		<div class="bg">
			<h4>Control panel settings</h4>

			<form action="" method="POST">
				<?php echo $d['errors']; ?>

				<div class="form-group top">
					<label>Website name</label>

					<input type="text" name="website_name" class="form-control" value="<?php echo $d['data']['website_name']; ?>" />
				</div>

				<div class="form-group">
					<label>Slogan</label>

					<input type="text" name="slogan" class="form-control" value="<?php echo $d['data']['slogan']; ?>" />
				</div>

				<div class="form-group">
					<label>Blog page</label>

					<select name="blog_page" class="form-control">
						<?php echo $d['data']['blog_page']; ?>
					</select>
				</div>

				<div class="form-group">
					<label>Excerpt word length</label>

					<input type="text" name="excerpt_length" class="form-control" value="<?php echo $d['data']['excerpt_length']; ?>" />
				</div>

				<div class="form-group">
					<label>Developer mode</label>

					<div>
						<label><input type="checkbox" name="dev_mode"<?php echo ($d['data']['dev_mode']) ? ' checked' : ''; ?> /></label>
					</div>
				</div>

				<div class="form-group">
					<input type="hidden" name="token" value="<?php echo $d['token'] ?>" />
					<input type="submit" value="Change settings" class="btn btn-primary" />
				</div>
			</form>
		</div>
	</div>

	<div class="col-sm-6">
		<div class="bg">
			<h4>Control panel information</h4>

			<?php echo $d['info']; ?>

			<div class="topsmall">
				<p class="align"><i class="fa fa-check-circle-o big"></i> Root path <a href="<?php echo ROOT_URL; ?>"><?php echo ROOT_URL; ?></a></p>
			</div>
		</div>
	</div>

	<div class="col-sm-6 topmed">
		<div class="bg">
			<h4>Options</h4>

			<div class="btn-group topsmall">
				<a type="button" href="<?php echo ADMIN_URL . 'settings/refresh_cache'; ?>" class="btn btn-default">Refresh cache</a>

				<a type="button" class="btn btn-default">Refresh</a>

				<a type="button" class="btn btn-default">Refresh</a>
			</div>
		</div>
	</div>
</div>