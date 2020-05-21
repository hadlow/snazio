<div class="heading">
	<div class="title">
		Edit <?php echo $d['data']['title']; ?> <a href="<?php echo ROOT_URL; echo $d['data']['link']; ?>" target="_blank"><i class="fa fa-link"></i></a>

		<a href="<?php echo ADMIN_URL; ?>pages/create">
			<span class="btn btn-default"><strong><i class="fa fa-plus-circle"></i>Create new page</strong></span>
		</a>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<form action="" method="POST" id="editform">
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label>Page title</label>

						<input type="text" class="form-control" value="<?php echo $d['data']['title']; ?>" id="title" />
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group">
						<label>Description</label>

						<input type="text" class="form-control" value="<?php echo $d['data']['description']; ?>" id="desc" />
					</div>
				</div>
			</div>

			<div class="form-group">
				<textarea class="form-control wysiwyg" id="index"><?php echo $d['data']['content']; ?></textarea>
			</div>

			<?php echo $d['data']['fields']; ?>

			<div class="form-group">
				<input type="submit" class="btn btn-primary decu" value="Edit page" />
			</div>
		</form>
	</div>
</div>

<div class="alert alert-success alert-fixed-top" id="success">
	Page successfully updated
</div>

<div class="alert alert-danger alert-fixed-top" id="error">
	Error with updating page
</div>

<script>

function submit()
{
	<?php echo $d['data']['js']; ?>
}

function popup(message)
{
	if(message == 'success')
	{
		$("#success").animate({ top:"+=51", opacity:"1" }, 200).delay(4000).animate({ top:"-=51", opacity:"0" }, 200);
	} else {
		$("#error").animate({ top:"+=51", opacity:"1" }, 200).delay(4000).animate({ top:"-=51", opacity:"0" }, 200);
	}
}

$(window).bind('keydown', function(event){
    if (event.ctrlKey || event.metaKey){
        switch (String.fromCharCode(event.which).toLowerCase()){
	        case 's':
	            event.preventDefault();

	            submit();
	            break;
        }
    }
});

$(document).ready(function(){
	$('.alert-fixed-top').css('top', '-51px').css('opacity', '0');

	$('#editform').submit(function(e){
		e.preventDefault();

		submit();
	});
});

</script>
