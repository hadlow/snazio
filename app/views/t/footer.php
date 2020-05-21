		<?php

		global $plugin;

		?>
		<p class="footer">Thank you for using <a href="https://www.snaz.io/" target="_blank">Snazio</a>
		 - <a href="https://store.snaz.io" target="_blank">Store</a>
		 - <a href="https://learn.snaz.io" target="_blank">Learn</a>
		 - <a href="https://community.snaz.io" target="_blank">Community</a></p>

		<?php $plugin->run_hooks('admin_last_container'); ?>
	</div>

	<script src="<?php echo ADMIN_URL; ?>assets/js/bootstrap.min.js"></script>
	<script src="<?php echo ADMIN_URL; ?>assets/js/summernote/summernote.min.js"></script>

	<script>
		$(document).ready(function() {
			$('.wysiwyg').summernote({
				height: "300px"
			});
		});
	</script>

	<script type="text/javascript">
		$('.dropdown-menu').bind('click', function (e) { e.stopPropagation() })
    </script>

	<script type="text/javascript">

	$(document).ready(function(){
		$("a").tooltip();
		$(".option").tooltip();
	});

	</script>

    <?php $plugin->run_hooks('admin_footer'); ?>

</body>
</html>
