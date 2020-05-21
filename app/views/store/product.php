<div class="row">
	<div class="col-lg-12 product">
		<div class="featured">
			<img src="<?php echo $d['data']['image']; ?>" width="100%" />
		</div>
		
		<div class="inner">
			<h2 class="thin"><?php echo $d['data']['title']; ?> <a class="btn btn-primary" href="<?php echo $d['data']['link']; ?>" target="_blank"><strong>$35</strong> - purchase</a></h2>
			
			<div>
				<p class="top"><strong>Description</strong></p>
				
				<p>
					<?php echo $d['data']['content']; ?>
				</p>
			</div>
			
			<div>
				<p class="top"><strong>Screenshots</strong></p>
			</div>
			
			<a class="btn btn-primary top" href="<?php echo $d['data']['link']; ?>" target="_blank"><strong>$35</strong> - purchase</a>
		</div>
	</div>
</div>