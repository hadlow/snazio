<div class="heading">
	<div class="title">
		<?php echo $d['title']; ?>
	</div>
</div>

<?php

global $plugin;
$plugin->run_hooks($d['content'], array($d['args']));

?>