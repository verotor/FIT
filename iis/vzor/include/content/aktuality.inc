<div id="content">
	<h2><?php print $navigation->get_page_name(); ?></h2>
<?php

	require_once 'classes/formparser/news.class.php';
	$news = new News();
	$news->setDBC($dbc);
	
	if (isset($_GET['id']))
	{
		$news->setFormDataItem('new_id', $_GET['id']);
		$news->readItem();
		
		$news->publicate_one();
	}
	else
	{
		$news->load_active();
		$news->publicate();
	}

?>
</div>
