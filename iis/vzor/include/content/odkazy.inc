<div id="content">
	<h2><?php print $navigation->get_page_name(); ?></h2>
<?php

	require_once 'classes/formparser/links.class.php';
	$links = new Links();
	$links->setDBC($dbc);
	
	$links->load_active();
	$links->publicate();

?>
</div>
