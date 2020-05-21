<div class="heading">
	<div class="title">
		Pages

		<a href="<?php echo ADMIN_URL . 'pages/create'; ?>">
			<span class="btn btn-default"><strong><i class="fa fa-plus-circle"></i>Create new page</strong></span>
		</a>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="list-pages">
			<ul>
				<li>
					<div class="box">
	                	<a href="pages/edit" class="optionfirst">Home</a>

	                	<div class="options">
							<a href="pages/edit/">
								<div class="option" data-toggle="tooltip" title="Edit page">
									<i class="fa fa-pencil"></i>
								</div>
							</a>

							<a href="<?php echo ROOT_URL; ?>" target="_blank">
								<div class="option" data-toggle="tooltip" title="View home">
									<i class="fa fa-link"></i>
								</div>
							</a>
	                	</div>
	                </div>
				</li>
			</ul>

			<?php echo $d['data']; ?>
		</div>
	</div>
</div>

<script type="text/javascript">

var elememt = document.getElementsByClassName('delete');
var confirmIt = function(e)
{
	if(!confirm('Are you sure you want to delete this page? Deleting this page will also delete any subpages!')) e.preventDefault();
};

for(var i = 0, l = elememt.length; i < l; i++)
{
	elememt[i].addEventListener('click', confirmIt, false);
}

</script>
