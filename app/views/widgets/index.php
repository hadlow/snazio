<div class="heading">
	<div class="title">
		Widgets
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<form action="" method="POST" id="editform">
			<?php echo $d['edit_partials']['partials']; ?>

			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Edit widgets" />
			</div>
		</form>
	</div>
</div>

<div class="alert alert-success alert-fixed-top" id="success">
	Widgets successfully updated
</div>

<div class="alert alert-danger alert-fixed-top" id="error">
	Error with updating widgets
</div>

<script type="text/javascript">

function submit()
{
	<?php echo $d['edit_partials']['js']; ?>
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

	$('#editform').submit(function(){
		event.preventDefault();

		submit();
	});
});

</script>