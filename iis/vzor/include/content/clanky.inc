<div id="content">
	<h2><?php print $navigation->get_page_name(); ?></h2>
<?php

	require_once 'classes/formparser/articles.class.php';
	$articles = new Articles();
	$articles->setDBC($dbc);
	
	if (isset($_GET['id']))
	{
		$articles->setFormDataItem('article_id', $_GET['id']);
		$articles->readItem();
		
		$articles->publicate_one();
	}
	else
	{
		$articles->load_active();
		$articles->publicate();
	}

?>
</div>
