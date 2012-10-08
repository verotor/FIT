<div id='content'>
	<h2><?php print $navigation_admin->get_page_name(); ?></h2>
<?php

	require_once 'classes/formparser/articles.class.php';
	$articles = new Articles();
	$articles->setDBC($dbc);
	
	if (isset($_POST['formdata']))
	{
		$articles->setFormData($_POST['formdata']);
		
		if ($articles->isFormDataItem('btnClear'))
		{
			$articles->setFormData(null);
		}
		else if ($articles->isFormDataItem('btnUpdate'))
		{
			$articles->updateItem();
		}
		else if ($articles->isFormDataItem('btnDelete'))
		{
			$_GET['action'] = 'delete';
			$_GET['id'] = $articles->getFormDataItem('article_id');
		}
		else if ($articles->isFormDataItem('btnReallyDelete'))
		{
			$articles->deleteItem();
			
			$_GET['action'] = 'show';
		}
		else if ($articles->isFormDataItem('btnSave'))
		{
			$articles->saveItem();
		}
	}
	
	$articleform = false;
	$deleteform = false;
	$showarticles = false;
	
	if ($_GET['action'] == 'add')
	{
		$legend = 'Přidat článek';
		
		$articleform = true;
	}
	else if ($_GET['action'] == 'edit')
	{
		$articles->setFormDataItem('article_id', $_GET['id']);
		$articles->readItem();
		$_POST['formdata'] = $articles->getFormData();
		
		$legend = 'Editovat článek';
		
		$articleform = true;
	}
	else if ($_GET['action'] == 'delete')
	{
		$articles->setFormDataItem('article_id', $_GET['id']);
		$articles->readItem();
		
		$legend = 'Odstranit článek';
		
		$item_what = ' článek ';
		$item_id_key = 'article_id';
		$item_title = $articles->getFormDataItem('article_title');
		
		$formparser = $articles;
		
		$deleteform = true;
	}
	else if ($_GET['action'] == 'show')
	{
		$showarticles = true;
	}

?>
<?php

	if ($articleform)
	{
		include_once 'include/form/articleform.inc';
	}
	
	if ($deleteform)
	{
		include_once 'include/form/deleteform.inc';
	}
	
	if ($showarticles)
	{
		if (isset($_GET['id']))
		{
			$articles->setFormDataItem('article_id', $_GET['id']);
			$articles->readItem();
			
			$articles->publicate_one();
		}
		else
		{
			$articles->load();
			$articles->show();
		}
	}

?>
</div>
