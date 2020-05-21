<div class="heading">
	<div class="title">
		Create Page
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<form action="" method="POST">
			<?php echo $d['errors']; ?>

			<div class="form-group">
				<label for="title">Page title</label>
				<input type="text" class="form-control" value="<?php echo $d['title']; ?>" name="title" id="title" />
			</div>
			
			<label>Select a template</label>
			<?php echo $d['templates']; ?>

			<div class="form-group top">
				<input type="hidden" name="token" value="<?php echo $d['token'] ?>" />
				<input type="submit" class="btn btn-primary" value="Create page" />
			</div>
		</form>
	</div>
</div>