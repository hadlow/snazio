<?php
global $config;
?>

<div class="heading">
	<div class="title">
		<a href="<?php echo ADMIN_URL . 'pages'; ?>" title="Pages"><span class="fa fa-arrow-circle-left"></span></a> Posts

		<a href="<?php echo ADMIN_URL . 'pages/create_post/' . $config['blog_page']; ?>">
			<span class="btn btn-default"><strong><i class="fa fa-plus-circle"></i>Create new post</strong></span>
		</a>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="list-pages">
			<?php echo $d['data']; ?>
		</div>
	</div>
</div>

<script type="text/javascript">

var elememt = document.getElementsByClassName('delete');
var confirmIt = function(e)
{
	if(!confirm('Are you sure you want to delete this post?')) e.preventDefault();
};

for(var i = 0, l = elememt.length; i < l; i++)
{
	elememt[i].addEventListener('click', confirmIt, false);
}

</script>

<script type="text/javascript">

$(document).ready(function(){
    $("a").tooltip();
    $(".option").tooltip();
});

</script>
